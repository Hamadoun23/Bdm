<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\CampagneAideVersement;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CampagneAideVersementController extends Controller
{
    public function store(Request $request, Campagne $campagne): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'semaine_debut' => 'required|date',
            'montant_carburant' => 'required|integer|min:0',
            'montant_credit_tel' => 'required|integer|min:0',
        ]);

        $user = User::findOrFail($request->user_id);
        if (! $user->isCommercial() || ! $campagne->userEstSignataireContrat($user)) {
            return back()->withErrors(['user_id' => 'Ce commercial ne fait pas partie des signataires de cette campagne.']);
        }

        CampagneAideVersement::create([
            'campagne_id' => $campagne->id,
            'user_id' => $user->id,
            'semaine_debut' => $request->semaine_debut,
            'montant_carburant' => (int) $request->montant_carburant,
            'montant_credit_tel' => (int) $request->montant_credit_tel,
            'enregistre_par' => auth()->id(),
        ]);

        return back()->with('success', 'Versement enregistré. Le commercial doit confirmer la réception.');
    }

    public function destroy(Campagne $campagne, CampagneAideVersement $versement): RedirectResponse
    {
        if ($versement->campagne_id !== $campagne->id) {
            abort(404);
        }
        if ($versement->accuse_at) {
            return back()->withErrors(['versement' => 'Impossible de supprimer un versement déjà accusé réception.']);
        }
        $versement->delete();

        return back()->with('success', 'Versement supprimé.');
    }
}
