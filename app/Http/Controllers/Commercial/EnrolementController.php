<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\EnrolementClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EnrolementController extends Controller
{
    public function index(Request $request): Response
    {
        $query = EnrolementClient::with(['user', 'agence', 'campagne']);

        $user = $request->user();
        if ($user?->isCommercial()) {
            $query->where('user_id', $user->id);
        }

        $canManage = (bool) $user?->isCommercial();
        $canSeeCommercial = (bool) ($user?->isAdmin() || $user?->isDirection());

        $enrolements = $query->latest()->paginate(15);

        return Inertia::render('Enrolements/Index', [
            'canManage' => $canManage,
            'canSeeCommercial' => $canSeeCommercial,
            'enrolements' => [
                'data' => $enrolements->getCollection()->map(fn (EnrolementClient $e) => [
                    'id' => $e->id,
                    'date' => $e->created_at->format('d/m/Y H:i'),
                    'client_nom' => trim($e->prenom.' '.$e->nom),
                    'telephone' => $e->telephone,
                    'adresse' => $e->adresse,
                    'commercial' => $e->user->name ?? '-',
                    'agence' => $e->agence->nom ?? '-',
                    'peut_supprimer' => $e->peutEtreModifieOuSupprimeParCommercial(),
                ])->values(),
                'links' => $enrolements->linkCollection(),
                'from' => $enrolements->firstItem(),
                'to' => $enrolements->lastItem(),
                'total' => $enrolements->total(),
            ],
        ]);
    }

    public function create(Request $request): Response
    {
        Campagne::syncStatuts();
        $user = $request->user();
        $agenceId = $user->agence_id ? (int) $user->agence_id : null;
        $campagnesOuvertes = $agenceId
            ? Campagne::getActivesPourAgence($agenceId)
                ->where('type', Campagne::TYPE_ENROLEMENT_APP)
                ->filter(fn (Campagne $c) => $c->estEngageCommercial($user->id))
                ->values()
            : collect();
        $peutEnroler = $agenceId && $campagnesOuvertes->isNotEmpty();
        $contratAccepte = $campagnesOuvertes->contains(fn (Campagne $c) => $c->commercialAAccepteContrat($user->id));

        return Inertia::render('Enrolements/Create', [
            'campagnesOuvertes' => $campagnesOuvertes->map(fn (Campagne $c) => [
                'id' => $c->id,
                'nom' => $c->nom,
                'date_fin' => $c->date_fin->format('d/m/Y'),
            ])->values(),
            'peutEnroler' => $peutEnroler,
            'contratAccepte' => $contratAccepte,
        ]);
    }

    public function destroy(Request $request, EnrolementClient $enrolement): RedirectResponse
    {
        $user = $request->user();
        if (! $user?->isCommercial() || (int) $enrolement->user_id !== (int) $user->id) {
            abort(403);
        }

        if (! $enrolement->peutEtreModifieOuSupprimeParCommercial()) {
            return redirect()
                ->route('enrolements.index')
                ->with('error', 'Suppression impossible : plus de '.EnrolementClient::DELAI_SUPPRESSION_COMMERCIAL_HEURES.' h se sont écoulées depuis l’enregistrement.');
        }

        $enrolement->delete();

        return redirect()->route('enrolements.index')->with('success', 'Enrôlement supprimé.');
    }
}
