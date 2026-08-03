<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\CampagneAideVersement;
use App\Models\ContratPrestationReponse;
use App\Services\ContratPrestationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContratPrestationController extends Controller
{
    public function show(Request $request, ContratPrestationService $service): Response
    {
        Campagne::syncStatuts();
        $user = $request->user();
        $campagne = $user->agence_id
            ? Campagne::getActivesPourAgence((int) $user->agence_id)->first(fn (Campagne $c) => $c->estEngageCommercial($user->id))
            : null;
        $campagne?->loadMissing('contratArticles');

        if (! $campagne || ! $campagne->userEstSignataireContrat($user)) {
            return Inertia::render('Commercial/Contrat/NoCampagne');
        }

        $reponse = ContratPrestationReponse::firstOrCreate(
            ['campagne_id' => $campagne->id, 'user_id' => $user->id],
            ['statut' => ContratPrestationReponse::STATUT_EN_ATTENTE]
        );

        $verrou5j = $campagne->contrat_publie_at && $campagne->contratDelaiExpire();
        $peutRepondre = $campagne->contrat_publie_at
            && ! $verrou5j
            && $reponse->statut === ContratPrestationReponse::STATUT_EN_ATTENTE;

        $d = $service->donneesPourTemplate($campagne, $user);
        $versements = CampagneAideVersement::query()
            ->where('campagne_id', $campagne->id)
            ->where('user_id', $user->id)
            ->orderByDesc('semaine_debut')
            ->get();

        $echeance = $campagne->contrat_publie_at?->copy()->addDays(5);
        $nomPresta = $user->prenom ? trim($user->prenom.' '.$user->name) : $user->name;

        return Inertia::render('Commercial/Contrat/Show', [
            'campagne' => [
                'nom' => $campagne->nom,
                'date_debut' => $campagne->date_debut->format('d/m/Y'),
                'date_fin' => $campagne->date_fin->format('d/m/Y'),
                'contrat_publie_at' => (bool) $campagne->contrat_publie_at,
                'aide_hebdo_active' => $campagne->aide_hebdo_active,
            ],
            'user' => [
                'adresse_contrat' => $user->adresse_contrat,
                'piece_identite_ref' => $user->piece_identite_ref,
            ],
            'reponse' => [
                'statut' => $reponse->statut,
                'repondu_at' => $reponse->repondu_at?->format('d/m/Y H:i'),
            ],
            'verrou5j' => (bool) $verrou5j,
            'peutRepondre' => (bool) $peutRepondre,
            'echeance' => $echeance?->format('d/m/Y H:i'),
            'document' => [
                'representant_nom' => $campagne->contrat_representant_nom,
                'nom_presta' => $nomPresta,
                'contact_presta' => $user->telephone ?: '—',
                'adresse' => $user->adresse_contrat ?: '………………………',
                'piece_id' => $user->piece_identite_ref ?: '………………………',
                'lundi_effectif' => $d['lundi_effectif']->format('d/m/Y'),
                'date_fin' => $campagne->date_fin->format('d/m/Y'),
                'nom_campagne' => $campagne->nom,
                'articles' => $campagne->contratArticles->map(fn ($a) => ['titre' => $a->titre, 'contenu' => $a->contenu])->values(),
                'emolument_forfait' => number_format($campagne->contrat_emolument_forfait, 0, ',', ' '),
                'forfait_communication' => number_format($campagne->contrat_forfait_communication, 0, ',', ' '),
                'forfait_deplacement' => number_format($campagne->contrat_forfait_deplacement, 0, ',', ' '),
                'prime_meilleur_vendeur' => number_format($campagne->prime_meilleur_vendeur, 0, ',', ' '),
                'aide_hebdo_active' => $campagne->aide_hebdo_active,
                'aide_hebdo_montant' => number_format($campagne->aide_hebdo_montant, 0, ',', ' '),
                'aide_hebdo_carburant' => number_format($campagne->aide_hebdo_carburant, 0, ',', ' '),
                'aide_hebdo_credit_tel' => number_format($campagne->aide_hebdo_credit_tel, 0, ',', ' '),
                'clause_libre' => $campagne->contrat_clause_libre,
                'lieu_signature' => $campagne->contrat_lieu_signature,
                'date_signature_affichee' => $d['date_signature_affichee'],
            ],
            'versements' => $versements->map(fn (CampagneAideVersement $v) => [
                'id' => $v->id,
                'semaine_debut' => $v->semaine_debut->format('d/m/Y'),
                'montant_carburant' => number_format($v->montant_carburant, 0, ',', ' '),
                'montant_credit_tel' => number_format($v->montant_credit_tel, 0, ',', ' '),
                'accuse_at' => $v->accuse_at?->format('d/m/Y H:i'),
            ])->values(),
        ]);
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
        $campagne = $user->agence_id
            ? Campagne::getActivesPourAgence((int) $user->agence_id)->first(fn (Campagne $c) => $c->estEngageCommercial($user->id))
            : null;

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
