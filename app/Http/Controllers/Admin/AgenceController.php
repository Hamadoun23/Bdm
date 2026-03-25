<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Stock;
use App\Models\TypeCarte;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgenceController extends Controller
{
    public function index(): View
    {
        $agences = Agence::with(['chef', 'stocks.typeCarte'])->get();
        return view('admin.agences.index', compact('agences'));
    }

    public function create(): View
    {
        $chefs = User::where('role', 'chef_agence')->get();
        return view('admin.agences.create', compact('chefs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'chef_id' => 'nullable|exists:users,id',
        ]);

        $agence = Agence::create($request->only('nom', 'adresse', 'chef_id'));
        if ($agence->chef_id) {
            User::where('id', $agence->chef_id)->update(['agence_id' => $agence->id]);
        }
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
        $chefs = User::where('role', 'chef_agence')->get();
        return view('admin.agences.edit', compact('agence', 'chefs'));
    }

    public function update(Request $request, Agence $agence): RedirectResponse
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'chef_id' => 'nullable|exists:users,id',
        ]);
        $oldChefId = $agence->chef_id;
        $agence->update($request->only('nom', 'adresse', 'chef_id'));
        if ($oldChefId) {
            User::where('id', $oldChefId)->update(['agence_id' => null]);
        }
        if ($agence->chef_id) {
            User::where('id', $agence->chef_id)->update(['agence_id' => $agence->id]);
        }
        return redirect()->route('admin.agences.index')->with('success', 'Agence mise à jour.');
    }

    public function destroy(Agence $agence): RedirectResponse
    {
        $agence->users()->update(['agence_id' => null]);
        $agence->delete();
        return redirect()->route('admin.agences.index')->with('success', 'Agence supprimée.');
    }
}
