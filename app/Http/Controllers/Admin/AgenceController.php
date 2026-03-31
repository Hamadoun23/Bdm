<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Stock;
use App\Models\TypeCarte;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgenceController extends Controller
{
    public function index(): View
    {
        $agences = Agence::with(['stocks.typeCarte'])->get();

        return view('admin.agences.index', compact('agences'));
    }

    public function create(): View
    {
        return view('admin.agences.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $agence = Agence::create([
            'nom' => $request->input('nom'),
            'adresse' => null,
            'chef_id' => null,
        ]);

        foreach (TypeCarte::orderBy('code')->get() as $tc) {
            Stock::create([
                'type_carte_id' => $tc->id,
                'quantite' => 0,
                'agence_id' => $agence->id,
            ]);
        }

        return redirect()->route('admin.agences.index')->with('success', 'Agence créée.');
    }

    public function edit(Agence $agence): View
    {
        return view('admin.agences.edit', compact('agence'));
    }

    public function update(Request $request, Agence $agence): RedirectResponse
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $agence->update([
            'nom' => $request->input('nom'),
            'adresse' => null,
        ]);

        return redirect()->route('admin.agences.index')->with('success', 'Agence mise à jour.');
    }

    public function destroy(Agence $agence): RedirectResponse
    {
        if ($agence->chef_id) {
            $agence->update(['chef_id' => null]);
        }
        $agence->users()->update(['agence_id' => null]);
        $agence->delete();

        return redirect()->route('admin.agences.index')->with('success', 'Agence supprimée.');
    }
}
