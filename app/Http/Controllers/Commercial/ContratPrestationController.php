<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\CampagneAideVersement;
use App\Models\ContratPrestationReponse;
use App\Services\ContratPrestationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContratPrestationController extends Controller
{
    public function show(Request $request, ContratPrestationService $service): View
    {
        Campagne::syncStatuts();
        $user = $request->user();
        $campagne = Campagne::getActiveForAgence($user->agence_id);
        $campagne?->loadMissing('contratArticles');

        if (! $campagne || ! $campagne->userEstSignataireContrat($user)) {
            return view('commercial.contrat.no-campagne');
        }

        $reponse = ContratPrestationReponse::firstOrCreate(
            ['campagne_id' => $campagne->id, 'user_id' => $user->id],
            ['statut' => ContratPrestationReponse::STATUT_EN_ATTENTE]
        );

        $verrou5j = $campagne->contrat_publie_at && $campagne->contratDelaiExpire();
        $peutRepondre = $campagne->contrat_publie_at
            && ! $verrou5j
            && $reponse->statut === ContratPrestationReponse::STATUT_EN_ATTENTE;

        $donneesContrat = $service->donneesPourTemplate($campagne, $user);
        $versements = CampagneAideVersement::query()
            ->where('campagne_id', $campagne->id)
            ->where('user_id', $user->id)
            ->orderByDesc('semaine_debut')
            ->get();

        $echeance = $campagne->contrat_publie_at?->copy()->addDays(5);

        return view('commercial.contrat.show', compact(
            'user', 'campagne', 'reponse', 'verrou5j', 'peutRepondre', 'donneesContrat', 'versements', 'echeance'
        ));
    }

    public function accepter(Request $request): RedirectResponse
    {
        return $this->majReponse($request, ContratPrestationReponse::STATUT_ACCEPTE);
    }

    public function rejeter(Request $request): RedirectResponse
    {
        return $this->majReponse($request, ContratPrestationReponse::STATUT_REJETE);
    }

    private function majReponse(Request $request, string $statut): RedirectResponse
    {
        Campagne::syncStatuts();
        $user = $request->user();
        $campagne = Campagne::getActiveForAgence($user->agence_id);

        if (! $campagne || ! $campagne->userEstSignataireContrat($user)) {
            return redirect()->route('commercial.contrat')->with('error', 'Campagne ou habilitation invalide.');
        }

        $reponse = ContratPrestationReponse::where('campagne_id', $campagne->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($campagne->contratDelaiExpire() || $reponse->statut !== ContratPrestationReponse::STATUT_EN_ATTENTE) {
            return redirect()->route('commercial.contrat')->with('error', 'Vous ne pouvez plus modifier votre réponse (délai de 5 jours dépassé ou décision déjà enregistrée).');
        }

        if (! $campagne->contrat_publie_at) {
            return redirect()->route('commercial.contrat')->with('error', 'Le contrat n’a pas encore été publié par l’administrateur.');
        }

        $reponse->update([
            'statut' => $statut,
            'repondu_at' => now(),
        ]);

        $msg = $statut === ContratPrestationReponse::STATUT_ACCEPTE
            ? 'Contrat accepté. Merci.'
            : 'Contrat refusé. La direction en sera informée.';

        return redirect()->route('commercial.contrat')->with('success', $msg);
    }

    public function accuserVersement(Request $request, CampagneAideVersement $versement): RedirectResponse
    {
        $user = $request->user();
        if ($versement->user_id !== $user->id) {
            abort(403);
        }
        if ($versement->accuse_at) {
            return back()->with('error', 'Ce versement est déjà accusé réception.');
        }

        $request->validate([
            'accuse_commentaire' => 'nullable|string|max:1000',
        ]);

        $versement->update([
            'accuse_at' => now(),
            'accuse_commentaire' => $request->accuse_commentaire,
        ]);

        return back()->with('success', 'Réception des aides enregistrée.');
    }
}
