<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\TypeCarte;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TypeCarteController extends Controller
{
    public function index(): View
    {
        $types = TypeCarte::orderBy('code')->get();
        return view('admin.types_cartes.index', compact('types'));
    }

    public function create(): View
    {
        return view('admin.types_cartes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'prix' => 'required|integer|min:0',
            'actif' => 'boolean',
        ]);

        $code = Str::upper(Str::slug($request->code, '_'));
        if ($code === '') {
            return back()->withErrors(['code' => 'Code invalide.'])->withInput();
        }
        if (TypeCarte::where('code', $code)->exists()) {
            return back()->withErrors(['code' => 'Ce code existe déjà.'])->withInput();
        }

        TypeCarte::create([
            'code' => $code,
            'prix' => (int) $request->prix,
            'actif' => $request->boolean('actif'),
        ]);

        return redirect()->route('admin.types-cartes.index')->with('success', 'Type de carte créé.');
    }

    public function edit(TypeCarte $types_carte): View
    {
        return view('admin.types_cartes.edit', ['typeCarte' => $types_carte]);
    }

    public function update(Request $request, TypeCarte $types_carte): RedirectResponse
    {
        $request->validate([
            'prix' => 'required|integer|min:0',
            'actif' => 'boolean',
        ]);

        $types_carte->update([
            'prix' => (int) $request->prix,
            'actif' => $request->boolean('actif', true),
        ]);

        return redirect()->route('admin.types-cartes.index')->with('success', 'Type de carte mis à jour.');
    }

    public function destroy(TypeCarte $types_carte): RedirectResponse
    {
        if (!$types_carte->peutEtreSupprime()) {
            return redirect()->route('admin.types-cartes.index')
                ->with('error', 'Impossible de supprimer : des ventes ou mouvements utilisent encore ce type.');
        }

        DB::transaction(function () use ($types_carte) {
            Stock::where('type_carte_id', $types_carte->id)->delete();
            $types_carte->delete();
        });

        return redirect()->route('admin.types-cartes.index')->with('success', 'Type de carte supprimé.');
    }
}
