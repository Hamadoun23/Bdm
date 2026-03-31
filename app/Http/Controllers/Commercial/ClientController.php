<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function edit(Request $request, Client $client): View
    {
        $this->authorizeCommercialOwnClient($request, $client);
        $client->load('typeCarte');

        return view('commercial.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $this->authorizeCommercialOwnClient($request, $client);

        $validated = $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'ville' => 'nullable|string|max:100',
            'quartier' => 'nullable|string|max:100',
            'carte_identite' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
        ]);

        if ($request->hasFile('carte_identite')) {
            if ($client->carte_identite) {
                Storage::disk('public')->delete($client->carte_identite);
            }
            $validated['carte_identite'] = $request->file('carte_identite')->store('cartes-identite', 'public');
        } else {
            unset($validated['carte_identite']);
        }

        $client->update($validated);

        return redirect()->route('ventes.index')->with('success', 'Informations client mises à jour.');
    }

    public function destroy(Request $request, Client $client): RedirectResponse
    {
        $this->authorizeCommercialOwnClient($request, $client);

        if (! $client->peutEtreSupprimeParCommercial()) {
            return redirect()->route('commercial.clients.edit', $client)
                ->with('error', 'La suppression n’est plus possible : plus de '.Client::DELAI_SUPPRESSION_COMMERCIAL_HEURES.' heures se sont écoulées depuis l’enregistrement du client.');
        }

        if ($client->carte_identite) {
            Storage::disk('public')->delete($client->carte_identite);
        }

        $client->delete();

        return redirect()->route('ventes.index')->with('success', 'Fiche client supprimée (ventes associées supprimées également).');
    }

    private function authorizeCommercialOwnClient(Request $request, Client $client): void
    {
        $user = $request->user();
        if (! $user->isCommercial() || (int) $client->user_id !== (int) $user->id) {
            abort(403, 'Vous ne pouvez modifier que vos propres clients.');
        }
    }
}
