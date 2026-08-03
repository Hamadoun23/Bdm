<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AgenceController extends Controller
{
    public function index(): Response
    {
        $agences = Agence::query()->orderBy('ordre')->orderBy('nom')->get();

        return Inertia::render('Admin/Agences/Index', [
            'agences' => $agences->map(fn (Agence $a) => [
                'id' => $a->id,
                'ordre' => $a->ordre,
                'nom' => $a->nom,
            ])->values(),
        ]);
    }

    public function create(): Response
    {
        $ordreSuggest = (int) (Agence::max('ordre') ?? 0) + 1;

        return Inertia::render('Admin/Agences/Create', [
            'ordreSuggest' => $ordreSuggest,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'ordre' => 'required|integer|min:0',
            'nom' => 'required|string|max:255',
        ]);

        Agence::create([
            'ordre' => (int) $request->input('ordre'),
            'nom' => $request->input('nom'),
            'adresse' => null,
            'chef_id' => null,
        ]);

        return redirect()->route('admin.agences.index')->with('success', 'Agence créée.');
    }

    public function edit(Agence $agence): Response
    {
        return Inertia::render('Admin/Agences/Edit', [
            'agence' => [
                'id' => $agence->id,
                'ordre' => $agence->ordre,
                'nom' => $agence->nom,
            ],
        ]);
    }

    public function update(Request $request, Agence $agence): RedirectResponse
    {
        $request->validate([
            'ordre' => 'required|integer|min:0',
            'nom' => 'required|string|max:255',
        ]);

        $agence->update([
            'ordre' => (int) $request->input('ordre'),
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
