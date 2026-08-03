<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Campagne;
use App\Models\User;
use App\Models\Vente;
use App\Services\CampagneRapportService;
use App\Services\CampagneStatsScope;
use App\Services\PrimeService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private PrimeService $primeService,
        private CampagneRapportService $campagneRapportService,
    ) {}

    public function index(Request $request): Response
    {
        $user = $request->user();

        if (! $user) {
            return Inertia::render('Dashboard', ['variant' => 'guest']);
        }

        if ($user->isAdmin()) {
            return $this->dashboardAdmin($user, false);
        }

        if ($user->isDirection()) {
            return $this->dashboardAdmin($user, true);
        }

        if ($user->isCommercialTelephonique()) {
            return $this->dashboardTelephonique($user);
        }

        return $this->dashboardCommercial($user);
    }

    private function userContext($user): array
    {
        $user->loadMissing('agence');
        $displayName = trim(($user->prenom ?? '').' '.($user->name ?? ''));
        if ($displayName === '') {
            $displayName = $user->name ?? '—';
        }

        return [
            'display_name' => $displayName,
            'is_admin' => $user->isAdmin(),
            'agence_nom' => $user->agence?->nom,
        ];
    }

    private function dashboardAdmin($user, bool $directionReadOnly): Response
    {
        Campagne::syncStatuts();
        $campagneIdsStats = CampagneStatsScope::idsPour(null);
        $fenetreStats = CampagneStatsScope::fenetreDates(null);
        $libelleStatsCampagne = CampagneStatsScope::libelle(null);

        $baseVentesStats = Vente::query();
        CampagneStatsScope::appliquerSurVentes($baseVentesStats, null);

        $ventesTotal = (clone $baseVentesStats)->count();
        $ventesMois = $fenetreStats
            ? (clone $baseVentesStats)->whereBetween('created_at', [$fenetreStats['debut'], $fenetreStats['fin']])->count()
            : 0;
        if ($fenetreStats) {
            $classement = $this->primeService->getClassementPourCampagnesIds(
                $campagneIdsStats,
                $fenetreStats['debut'],
                $fenetreStats['fin']
            );
        } else {
            $classement = collect();
        }

        $campagnesTotal = Campagne::count();
        $campagnesActivesListe = Campagne::where('actif', true)->orderByDesc('date_debut')->get();
        $campagneActive = $campagnesActivesListe->first();
        $campagnesEnCours = Campagne::where('statut', Campagne::STATUT_EN_COURS)->count();
        $campagnesProgrammees = Campagne::where('statut', Campagne::STATUT_PROGRAMMEE)->count();

        $venteTrend = $this->campagneRapportService
            ->agregerVentesParPeriode(clone $baseVentesStats, 'semaine')
            ->pluck('total_ventes')
            ->slice(-6)
            ->values();

        $pctCommerciauxActifs = $classement->count() > 0
            ? (int) round(($classement->where('total_ventes', '>', 0)->count() / $classement->count()) * 100)
            : 0;

        return Inertia::render('Dashboard', [
            'variant' => 'admin',
            'user' => $this->userContext($user),
            'readOnly' => $directionReadOnly,
            'ventesTotal' => $ventesTotal,
            'ventesMois' => $ventesMois,
            'venteTrend' => $venteTrend,
            'pctCommerciauxActifs' => $pctCommerciauxActifs,
            'classement' => $classement->take(5)->values(),
            'campagnesTotal' => $campagnesTotal,
            'campagneActive' => $campagneActive ? [
                'nom' => $campagneActive->nom,
                'date_debut' => $campagneActive->date_debut->format('d/m/Y'),
                'date_fin' => $campagneActive->date_fin->format('d/m/Y'),
            ] : null,
            'campagnesActivesListe' => $campagnesActivesListe->map(fn (Campagne $c) => [
                'nom' => $c->nom,
                'date_debut' => $c->date_debut->format('d/m/Y'),
                'date_fin' => $c->date_fin->format('d/m/Y'),
            ])->values(),
            'campagnesEnCours' => $campagnesEnCours,
            'campagnesProgrammees' => $campagnesProgrammees,
            'libelleStatsCampagne' => $libelleStatsCampagne,
            'agencesCount' => Agence::count(),
            'commerciauxCount' => User::where('role', 'commercial')->count(),
        ]);
    }

    private function dashboardTelephonique($user): Response
    {
        $user->load('agence');
        Campagne::syncStatuts();
        $agenceId = $user->agence_id ? (int) $user->agence_id : null;
        $campagneActive = $agenceId ? Campagne::getActiveForAgence($agenceId) : null;
        $signataire = (bool) ($campagneActive && $campagneActive->userEstSignataireContrat($user));

        return Inertia::render('Dashboard', [
            'variant' => 'telephonique',
            'user' => $this->userContext($user),
            'campagneActive' => $campagneActive ? [
                'nom' => $campagneActive->nom,
                'date_debut' => $campagneActive->date_debut->format('d/m/Y'),
                'date_fin' => $campagneActive->date_fin->format('d/m/Y'),
            ] : null,
            'signataire' => $signataire,
        ]);
    }

    private function dashboardCommercial($user): Response
    {
        $user->load('agence');
        Campagne::syncStatuts();
        $agenceId = $user->agence_id ? (int) $user->agence_id : null;

        // Campagnes réellement ouvertes ET où ce commercial est engagé (pas seulement "agence couverte") —
        // sépare strictement les deux univers vente / enrôlement, y compris dans les stats affichées.
        $engagees = $agenceId
            ? Campagne::getActivesPourAgence($agenceId)->filter(fn (Campagne $c) => $c->estEngageCommercial($user->id))
            : collect();
        $venteOuvertes = $engagees->where('type', Campagne::TYPE_VENTE_CARTE)->values();
        $enrolementOuvertes = $engagees->where('type', Campagne::TYPE_ENROLEMENT_APP)->values();
        $peutVendre = $venteOuvertes->isNotEmpty();
        $peutEnroler = $enrolementOuvertes->isNotEmpty();

        // --- Stats vente, bornées aux campagnes de type vente_carte ---
        $campagnesStatsVente = Campagne::getCampagnesPourStats($agenceId)->where('type', Campagne::TYPE_VENTE_CARTE)->values();
        $campagneIdsStatsVente = $campagnesStatsVente->pluck('id')->map(fn ($id) => (int) $id)->all();

        if ($campagneIdsStatsVente !== []) {
            $fenetreStatsVente = [
                'debut' => $campagnesStatsVente->min(fn (Campagne $c) => $c->date_debut)->copy()->startOfDay(),
                'fin' => $campagnesStatsVente->max(fn (Campagne $c) => $c->date_fin)->copy()->endOfDay(),
            ];
            $mesVentes = Vente::query()
                ->where('user_id', $user->id)
                ->whereIn('campagne_id', $campagneIdsStatsVente)
                ->count();
            $classement = $this->primeService->getClassementPourCampagnesIds(
                $campagneIdsStatsVente,
                $fenetreStatsVente['debut'],
                $fenetreStatsVente['fin'],
                false,
                $agenceId
            );
        } else {
            $mesVentes = 0;
            $classement = collect();
        }

        $rangIdx = $classement->search(fn ($c) => (int) $c['user_id'] === (int) $user->id);
        $monRang = $rangIdx !== false ? (int) $classement->values()[$rangIdx]['rang'] : null;

        // --- Stats enrôlement, bornées aux campagnes de type enrolement_app ---
        $campagnesStatsEnrolement = Campagne::getCampagnesPourStats($agenceId)->where('type', Campagne::TYPE_ENROLEMENT_APP)->values();
        $campagneIdsStatsEnrolement = $campagnesStatsEnrolement->pluck('id')->map(fn ($id) => (int) $id)->all();
        $mesEnrolements = $campagneIdsStatsEnrolement !== []
            ? \App\Models\EnrolementClient::query()
                ->where('user_id', $user->id)
                ->whereIn('campagne_id', $campagneIdsStatsEnrolement)
                ->count()
            : 0;

        return Inertia::render('Dashboard', [
            'variant' => 'commercial',
            'user' => $this->userContext($user),
            'peutVendre' => $peutVendre,
            'peutEnroler' => $peutEnroler,
            'vente' => [
                'mesVentes' => $mesVentes,
                'monRang' => $monRang,
                'libelleStatsCampagne' => $campagnesStatsVente->pluck('nom')->join(', ') ?: null,
                'campagneActive' => $venteOuvertes->first() ? [
                    'nom' => $venteOuvertes->first()->nom,
                    'date_fin' => $venteOuvertes->first()->date_fin->format('d/m/Y'),
                ] : null,
                'campagnesOuvertes' => $venteOuvertes->map(fn (Campagne $c) => [
                    'nom' => $c->nom,
                    'date_fin' => $c->date_fin->format('d/m/Y'),
                ])->values(),
            ],
            'enrolement' => [
                'mesEnrolements' => $mesEnrolements,
                'campagneActive' => $enrolementOuvertes->first() ? [
                    'nom' => $enrolementOuvertes->first()->nom,
                    'date_fin' => $enrolementOuvertes->first()->date_fin->format('d/m/Y'),
                ] : null,
                'campagnesOuvertes' => $enrolementOuvertes->map(fn (Campagne $c) => [
                    'nom' => $c->nom,
                    'date_fin' => $c->date_fin->format('d/m/Y'),
                ])->values(),
            ],
        ]);
    }
}
