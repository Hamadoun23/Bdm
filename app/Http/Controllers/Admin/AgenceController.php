<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgenceController extends Controller
{
    public function index(): View
    {
        $agences = Agence::query()->orderBy('ordre')->orderBy('nom')->get();

        return view('admin.agences.index', compact('agences'));
    }

    public function create(): View
    {
        $ordreSuggest = (int) (Agence::max('ordre') ?? 0) + 1;

        return view('admin.agences.create', compact('ordreSuggest'));
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

    public function edit(Agence $agence): View
    {
        return view('admin.agences.edit', compact('agence'));
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
