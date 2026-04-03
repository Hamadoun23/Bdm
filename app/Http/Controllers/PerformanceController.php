<?php

namespace App\Http\Controllers;

use App\Models\Campagne;
use App\Models\Client;
use App\Models\TypeCarte;
use App\Models\User;
use App\Models\Vente;
use App\Services\PrimeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PerformanceController extends Controller
{
    public function __construct(
        private PrimeService $primeService
    ) {}

    public function index(Request $request): View
    {
        Campagne::syncStatuts();
        $ctx = $this->performanceContext($request);

        $campagneRef = $ctx['campagnePerformances'];
        $classementComplet = $campagneRef !== null
            ? $this->primeService->getClassementPourCampagne($campagneRef, $ctx['dateDebut'], $ctx['dateFin'])
            : $this->primeService->getClassementBetween($ctx['dateDebut'], $ctx['dateFin'], $ctx['agenceId']);

        $statsQuery = Vente::query()
            ->whereBetween('created_at', [$ctx['dateDebut'], $ctx['dateFin']])
            ->when($campagneRef, fn ($q) => $q->where('campagne_id', $campagneRef->id))
            ->when($ctx['agenceId'], fn ($q) => $q->where('agence_id', $ctx['agenceId']));

        $stats = [
            'total_ventes' => (clone $statsQuery)->count(),
            'par_type' => (clone $statsQuery)
                ->selectRaw('type_carte_id, COUNT(*) as total')
                ->groupBy('type_carte_id')
                ->pluck('total', 'type_carte_id'),
        ];

        $typesCartes = TypeCarte::orderBy('code')->get();

        $user = $request->user();
        $vueCommerciale = $user && ($user->isCommercial() || $user->isCommercialTelephonique());
        $vueChef = false;

        $classement = $classementComplet;
        $classementLigneTop1 = null;
        $ligneCommercialConnecte = null;

        if ($vueCommerciale) {
            $classementLigneTop1 = $classementComplet->first();
            $idx = $classementComplet->search(fn ($c) => (int) $c['user_id'] === (int) $user->id);
            $ligneCommercialConnecte = $idx !== false ? $classementComplet->values()[$idx] : null;
            $mesVentesPeriode = (int) Vente::query()
                ->where('user_id', $user->id)
                ->whereBetween('created_at', [$ctx['dateDebut'], $ctx['dateFin']])
                ->when($campagneRef, fn ($q) => $q->where('campagne_id', $campagneRef->id))
                ->count();
            $monRang = $ligneCommercialConnecte['rang'] ?? null;
            $stats['mes_ventes'] = $mesVentesPeriode;
            $stats['mon_rang'] = $monRang;
        }

        return view('performance.index', [
            'classement' => $classement,
            'classementLigneTop1' => $classementLigneTop1,
            'ligneCommercialConnecte' => $ligneCommercialConnecte,
            'stats' => $stats,
            'agenceId' => $ctx['agenceId'],
            'typesCartes' => $typesCartes,
            'vueCommerciale' => $vueCommerciale,
            'vueChef' => $vueChef,
            'user' => $user,
            'libellePeriode' => $ctx['libellePeriode'],
            'campagnePerformances' => $ctx['campagnePerformances'],
            'dateDebut' => $ctx['dateDebut'],
            'dateFin' => $ctx['dateFin'],
            'filtreIntervalle' => $ctx['filtreIntervalle'],
            'du' => $ctx['du'],
            'au' => $ctx['au'],
        ]);
    }

    public function show(Request $request, User $user): View
    {
        Campagne::syncStatuts();
        $viewer = $request->user();
        if (! $viewer) {
            throw new AccessDeniedHttpException;
        }

        $ctx = $this->performanceContext($request);
        if (! $this->canViewCommercialDetail($viewer, $user, $ctx['agenceId'])) {
            throw new AccessDeniedHttpException('Vous ne pouvez pas consulter le détail de ce commercial.');
        }

        if (! in_array($user->role, ['commercial', 'commercial_telephonique'], true)) {
            throw new NotFoundHttpException('Utilisateur non trouvé.');
        }

        $user->load('agence');

        $campagneCtx = $ctx['campagnePerformances'];

        $ventesBase = Vente::query()
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$ctx['dateDebut'], $ctx['dateFin']])
            ->when($campagneCtx, fn ($q) => $q->where('campagne_id', $campagneCtx->id));

        $ventes = (clone $ventesBase)
            ->with(['client', 'typeCarte', 'agence'])
            ->orderByDesc('created_at')
            ->get();

        $parType = (clone $ventesBase)
            ->selectRaw('type_carte_id, COUNT(*) as total, COALESCE(SUM(montant), 0) as montant_total')
            ->groupBy('type_carte_id')
            ->get()
            ->keyBy('type_carte_id');

        $clientIds = $ventes->pluck('client_id')->unique()->filter()->values();
        $clients = $clientIds->isEmpty()
            ? collect()
            : Client::query()
                ->whereIn('id', $clientIds)
                ->with('typeCarte')
                ->orderBy('nom')
                ->orderBy('prenom')
                ->get();

        $typesCartes = TypeCarte::orderBy('code')->get();
        $displayName = $user->prenom ? trim($user->prenom.' '.$user->name) : $user->name;

        $queryParams = array_filter([
            'du' => $ctx['du'],
            'au' => $ctx['au'],
            'agence' => $ctx['agenceId'],
        ], fn ($v) => $v !== null && $v !== '');

        return view('performance.show', [
            'commercial' => $user,
            'displayName' => $displayName,
            'ventes' => $ventes,
            'clients' => $clients,
            'parType' => $parType,
            'typesCartes' => $typesCartes,
            'libellePeriode' => $ctx['libellePeriode'],
            'dateDebut' => $ctx['dateDebut'],
            'dateFin' => $ctx['dateFin'],
            'agenceId' => $ctx['agenceId'],
            'queryParams' => $queryParams,
        ]);
    }

    private function canViewCommercialDetail(User $viewer, User $commercial, ?int $agenceContext): bool
    {
        if ($viewer->isAdmin() || $viewer->isDirection()) {
            if ($agenceContext !== null && $commercial->agence_id !== $agenceContext) {
                return false;
            }

            return true;
        }

        if ($viewer->isCommercial() || $viewer->isCommercialTelephonique()) {
            return $viewer->id === $commercial->id;
        }

        return false;
    }

    /**
     * @return array{
     *     dateDebut: Carbon,
     *     dateFin: Carbon,
     *     libellePeriode: string,
     *     agenceId: int|null,
     *     filtreIntervalle: bool,
     *     du: string|null,
     *     au: string|null,
     *     campagnePerformances: Campagne|null
     * }
     */
    private function performanceContext(Request $request): array
    {
        $user = $request->user();

        if ($user?->isAdmin() || $user?->isDirection()) {
            $agenceId = $request->query('agence') ? (int) $request->query('agence') : null;
        } elseif ($user?->isCommercial() || $user?->isCommercialTelephonique()) {
            $agenceId = $user->agence_id !== null && $user->agence_id !== '' ? (int) $user->agence_id : null;
        } else {
            $agenceId = null;
        }

        $du = $request->query('du');
        $au = $request->query('au');
        $filtreIntervalle = $request->filled('du') && $request->filled('au');

        $campagnePerformances = Campagne::getCampagnePourPerformances($agenceId);

        if ($filtreIntervalle) {
            $dateDebut = Carbon::parse($du)->startOfDay();
            $dateFin = Carbon::parse($au)->endOfDay();
            if ($dateDebut->gt($dateFin)) {
                [$dateDebut, $dateFin] = [$dateFin->copy()->startOfDay(), $dateDebut->copy()->endOfDay()];
            }
            $libellePeriode = 'Du '.$dateDebut->format('d/m/Y').' au '.$dateFin->format('d/m/Y');
        } elseif ($request->filled('periode')) {
            $periodeMois = $request->query('periode');
            $dateDebut = Carbon::parse($periodeMois.'-01')->startOfMonth();
            $dateFin = $dateDebut->copy()->endOfMonth();
            $libellePeriode = $dateDebut->locale('fr')->translatedFormat('F Y');
        } elseif ($campagnePerformances) {
            $dateDebut = $campagnePerformances->date_debut->copy()->startOfDay();
            $dateFin = $campagnePerformances->date_fin->copy()->endOfDay();
            $libellePeriode = 'Campagne « '.$campagnePerformances->nom.' » ('.$dateDebut->format('d/m/Y').' – '.$dateFin->format('d/m/Y').')';
        } else {
            $dateDebut = Carbon::now()->startOfDay();
            $dateFin = Carbon::now()->endOfDay();
            $libellePeriode = 'Aucune campagne trouvée';
        }

        return [
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'libellePeriode' => $libellePeriode,
            'agenceId' => $agenceId,
            'filtreIntervalle' => $filtreIntervalle,
            'du' => $filtreIntervalle ? (string) $du : null,
            'au' => $filtreIntervalle ? (string) $au : null,
            'campagnePerformances' => $campagnePerformances,
        ];
    }
}
