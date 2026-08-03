<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypeCarte;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TypeCarteController extends Controller
{
    public function index(): Response
    {
        $types = TypeCarte::orderBy('code')->get();

        return Inertia::render('Admin/TypesCartes/Index', [
            'types' => $types->map(fn (TypeCarte $t) => [
                'id' => $t->id,
                'code' => $t->code,
                'actif' => (bool) $t->actif,
            ])->values(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/TypesCartes/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|max:50',
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
            'actif' => $request->boolean('actif'),
        ]);

        return redirect()->route('admin.types-cartes.index')->with('success', 'Type de carte créé.');
    }

    public function edit(TypeCarte $types_carte): Response
    {
        return Inertia::render('Admin/TypesCartes/Edit', [
            'typeCarte' => [
                'id' => $types_carte->id,
                'code' => $types_carte->code,
                'actif' => (bool) $types_carte->actif,
            ],
        ]);
    }

    public function update(Request $request, TypeCarte $types_carte): RedirectResponse
    {
        $request->validate([
            'actif' => 'boolean',
        ]);

        $types_carte->update([
            'actif' => $request->boolean('actif', true),
        ]);

        return redirect()->route('admin.types-cartes.index')->with('success', 'Type de carte mis à jour.');
    }

    public function destroy(TypeCarte $types_carte): RedirectResponse
    {
        if (! $types_carte->peutEtreSupprime()) {
            return redirect()->route('admin.types-cartes.index')
                ->with('error', 'Impossible de supprimer : des ventes ou clients utilisent encore ce type.');
        }

        $types_carte->delete();

        return redirect()->route('admin.types-cartes.index')->with('success', 'Type de carte supprimé.');
    }
}
