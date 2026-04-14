<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Campagne;
use App\Models\Client;
use App\Models\TypeCarte;
use App\Models\User;
use App\Models\Vente;
use App\Services\CampagneRapportService;
use App\Services\PrimeService;
use App\Services\SpreadsheetExportService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PerformanceController extends Controller
{
    public function __construct(
        private PrimeService $primeService,
        private CampagneRapportService $campagneRapportService,
        private SpreadsheetExportService $spreadsheetExportService
    ) {}

    public function index(Request $request): View
    {
        Campagne::syncStatuts();
        $ctx = $this->performanceContext($request);

        $user = $request->user();
        $vueCommerciale = $user && ($user->isCommercial() || $user->isCommercialTelephonique());
        // Admin / direction : filtre ventes par agence aligné sur le tableau. Commercial : pas de filtre sur ventes
        // dans le JOIN du classement — sinon les autres agences du campagne ont 0 vente et le 1ᵉʳ / les rangs sont faux.
        $filtreVentesAgencePourClassement = ($user?->isAdmin() || $user?->isDirection())
            ? $ctx['agenceId']
            : null;

        $campagneRef = $ctx['campagneRef'];
        $classementComplet = $campagneRef !== null
            ? $this->primeService->getClassementPourCampagne($campagneRef, $ctx['dateDebut'], $ctx['dateFin'], false, $filtreVentesAgencePourClassement)
            : $this->primeService->getClassementBetween($ctx['dateDebut'], $ctx['dateFin'], $ctx['agenceId'], false, $filtreVentesAgencePourClassement);

        $baseVentes = $this->ventesQueryPerformance($ctx['dateDebut'], $ctx['dateFin'], $ctx['agenceId'], $campagneRef);
        $stats = $this->aggregatePerformanceStats($baseVentes);

        $parSemaine = $this->campagneRapportService->agregerVentesParPeriode(clone $baseVentes, 'semaine');

        $topCommerciauxChart = $classementComplet
            ->filter(fn (array $c) => ($c['total_ventes'] ?? 0) > 0)
            ->take(5)
            ->map(fn (array $c) => [
                'label' => $c['user_name'],
                'ventes' => (int) $c['total_ventes'],
            ])
            ->values();

        $ventesParAgenceChart = $this->ventesParAgencePourChart($baseVentes);
        $classementAgences = $this->classementAgencesPourPerformances($baseVentes);
        $classementTypesCartes = $this->classementTypesCartesPourPerformances($baseVentes);

        $compareEnabled = $ctx['compareEnabled'];
        $statsPrev = null;
        $compareDelta = null;
        if ($compareEnabled) {
            [$prevDebut, $prevFin, $nbJoursCalendaires] = $this->previousPeriodWindow($ctx['dateDebut'], $ctx['dateFin']);
            $basePrev = $this->ventesQueryPerformance($prevDebut, $prevFin, $ctx['agenceId'], $campagneRef);
            $statsPrev = $this->aggregatePerformanceStats($basePrev);
            $compareDelta = [
                'ventes_pct' => $this->pctVariation($stats['total_ventes'], $statsPrev['total_ventes']),
                'libelle' => 'Période de comparaison : '.$prevDebut->format('d/m/Y').' → '.$prevFin->format('d/m/Y').' ('.$nbJoursCalendaires.' j. inclus)',
            ];
        }

        $typesCartes = TypeCarte::orderBy('code')->get();

        $vueChef = false;

        $campagnesSelect = $this->campagnesPourPerformancesSelect($ctx['agenceId'], $user);

        $classement = $classementComplet;
        $classementLigneTop1 = null;
        $ligneCommercialConnecte = null;

        if ($vueCommerciale) {
            // Même périmètre que le classement campagne : toutes les ventes du user sur la campagne (sans filtre agence sur ventes).
            $mesVentesPeriode = (int) Vente::query()
                ->where('user_id', $user->id)
                ->whereBetween('created_at', [$ctx['dateDebut'], $ctx['dateFin']])
                ->when($campagneRef !== null, fn ($q) => $q->where('campagne_id', $campagneRef->id))
                ->when($campagneRef === null && $ctx['agenceId'] !== null, fn ($q) => $q->where('agence_id', $ctx['agenceId']))
                ->count();

            $sync = $this->leaderEtMaLigneCommercialPerformances($classementComplet, $user, $mesVentesPeriode);
            $classementLigneTop1 = $sync['classementLigneTop1'];
            $ligneCommercialConnecte = $sync['ligneCommercialConnecte'];
            $stats['mes_ventes'] = $mesVentesPeriode;
            $stats['mon_rang'] = $ligneCommercialConnecte['rang'] ?? null;
        }

        return view('performance.index', [
            'classement' => $classement,
            'classementLigneTop1' => $classementLigneTop1,
            'ligneCommercialConnecte' => $ligneCommercialConnecte,
            'stats' => $stats,
            'statsPrev' => $statsPrev,
            'compareDelta' => $compareDelta,
            'compareEnabled' => $compareEnabled,
            'parSemaine' => $parSemaine,
            'topCommerciauxChart' => $topCommerciauxChart,
            'ventesParAgenceChart' => $ventesParAgenceChart,
            'classementAgences' => $classementAgences,
            'classementTypesCartes' => $classementTypesCartes,
            'agenceId' => $ctx['agenceId'],
            'typesCartes' => $typesCartes,
            'vueCommerciale' => $vueCommerciale,
            'vueChef' => $vueChef,
            'user' => $user,
            'libellePeriode' => $ctx['libellePeriode'],
            'campagnePerformances' => $ctx['campagnePerformances'],
            'campagneRef' => $campagneRef,
            'campagneIdSelected' => $ctx['campagneIdSelected'],
            'campagnesSelect' => $campagnesSelect,
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

        $campagneRef = $ctx['campagneRef'];

        $ventesBase = Vente::query()
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$ctx['dateDebut'], $ctx['dateFin']])
            ->when($campagneRef !== null, fn ($q) => $q->where('campagne_id', $campagneRef->id));

        $ventes = (clone $ventesBase)
            ->with(['client', 'typeCarte', 'agence'])
            ->orderByDesc('created_at')
            ->get();

        $parType = (clone $ventesBase)
            ->selectRaw('type_carte_id, COUNT(*) as total')
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
            'campagne_id' => $ctx['campagneIdSelected'],
            'compare' => $ctx['compareEnabled'] ? '1' : null,
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
            'campagneRef' => $campagneRef,
        ]);
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        Campagne::syncStatuts();
        $ctx = $this->performanceContext($request);
        $campagneRef = $ctx['campagneRef'];

        $viewer = $request->user();
        $filtreVentesAgencePourClassement = ($viewer?->isAdmin() || $viewer?->isDirection())
            ? $ctx['agenceId']
            : null;
        $classement = $campagneRef !== null
            ? $this->primeService->getClassementPourCampagne($campagneRef, $ctx['dateDebut'], $ctx['dateFin'], false, $filtreVentesAgencePourClassement)
            : $this->primeService->getClassementBetween($ctx['dateDebut'], $ctx['dateFin'], $ctx['agenceId'], false, $filtreVentesAgencePourClassement);

        $baseVentes = $this->ventesQueryPerformance($ctx['dateDebut'], $ctx['dateFin'], $ctx['agenceId'], $campagneRef);
        $stats = $this->aggregatePerformanceStats($baseVentes);
        $parSemaine = $this->campagneRapportService->agregerVentesParPeriode(clone $baseVentes, 'semaine');

        $resumeBody = [
            ['Période affichée', $ctx['libellePeriode']],
            ['Date début (filtre)', $ctx['dateDebut']->format('d/m/Y')],
            ['Date fin (filtre)', $ctx['dateFin']->format('d/m/Y')],
            ['Campagne (filtre ventes)', $campagneRef?->nom ?? '—'],
            ['Total ventes', $stats['total_ventes']],
        ];

        $totalVentesExport = (int) ($stats['total_ventes'] ?? 0);
        $classementRows = $classement->map(function (array $c) use ($totalVentesExport) {
            $pct = $totalVentesExport > 0 ? round($c['total_ventes'] / $totalVentesExport * 100, 1) : 0.0;

            return [$c['rang'], $c['user_name'], $c['total_ventes'], $pct];
        })->all();

        $semRows = $parSemaine->map(fn ($l) => [$l['libelle'], $l['total_ventes']])->all();

        $classementAgences = $this->classementAgencesPourPerformances($baseVentes);
        $classementTypesCartes = $this->classementTypesCartesPourPerformances($baseVentes);

        $agencesRows = $classementAgences->map(fn (array $r) => [
            $r['rang'], $r['agence_nom'], $r['total_ventes'], $r['pct_volume'],
        ])->all();

        $typesRows = $classementTypesCartes->map(fn (array $r) => [
            $r['rang'], $r['code'], $r['total_ventes'], $r['pct_volume'],
        ])->all();

        $ventesDetail = (clone $baseVentes)
            ->with(['client', 'user', 'agence', 'typeCarte', 'campagne'])
            ->orderByDesc('created_at')
            ->get();

        $ventesDetailRows = $ventesDetail->map(fn (Vente $v) => [
            $v->created_at->format('d/m/Y H:i'),
            $v->campagne?->nom ?? '—',
            $v->client ? trim($v->client->prenom.' '.$v->client->nom) : '—',
            $v->client?->telephone ?? '',
            $v->typeCarte?->code ?? '—',
            $v->user ? ($v->user->prenom ? trim($v->user->prenom.' '.$v->user->name) : $v->user->name) : '',
            $v->agence->nom ?? '—',
            $v->statut_activation ?? '',
        ])->all();

        $definitions = [
            [
                'title' => 'Résumé',
                'headers' => ['Indicateur', 'Valeur'],
                'rows' => $resumeBody,
            ],
            [
                'title' => 'Classement commerciaux',
                'headers' => ['Rang', 'Commercial', 'Nombre de ventes', 'Part % volume'],
                'rows' => $classementRows,
            ],
            [
                'title' => 'Classement agences',
                'headers' => ['Rang', 'Agence', 'Ventes', 'Part % volume'],
                'rows' => $agencesRows,
            ],
            [
                'title' => 'Types cartes',
                'headers' => ['Rang', 'Type carte', 'Ventes', 'Part % volume'],
                'rows' => $typesRows,
            ],
            [
                'title' => 'Par semaine',
                'headers' => ['Période', 'Ventes'],
                'rows' => $semRows,
            ],
            [
                'title' => 'Ventes détail',
                'headers' => ['Date', 'Campagne', 'Client', 'Téléphone', 'Type carte', 'Commercial', 'Agence', 'Statut'],
                'rows' => $ventesDetailRows,
            ],
        ];

        $spreadsheet = $this->spreadsheetExportService->createMultiSheetSpreadsheet($definitions);
        $fn = 'performances_'.now()->format('Y-m-d_His').'.xlsx';

        return $this->spreadsheetExportService->download($spreadsheet, $fn);
    }

    public function exportCommercialExcel(Request $request, User $user): StreamedResponse
    {
        Campagne::syncStatuts();
        $viewer = $request->user();
        if (! $viewer) {
            throw new AccessDeniedHttpException;
        }

        $ctx = $this->performanceContext($request);
        if (! $this->canViewCommercialDetail($viewer, $user, $ctx['agenceId'])) {
            throw new AccessDeniedHttpException('Vous ne pouvez pas exporter ce détail.');
        }

        if (! in_array($user->role, ['commercial', 'commercial_telephonique'], true)) {
            throw new NotFoundHttpException('Utilisateur non trouvé.');
        }

        $user->load('agence');
        $campagneRef = $ctx['campagneRef'];

        $ventes = Vente::query()
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$ctx['dateDebut'], $ctx['dateFin']])
            ->when($campagneRef !== null, fn ($q) => $q->where('campagne_id', $campagneRef->id))
            ->with(['client', 'typeCarte', 'agence', 'campagne'])
            ->orderByDesc('created_at')
            ->get();

        $displayName = $user->prenom ? trim($user->prenom.' '.$user->name) : $user->name;
        $resumeRows = [
            ['Commercial', $displayName],
            ['Agence', $user->agence?->nom ?? '—'],
            ['Période', $ctx['libellePeriode']],
            ['Nombre de ventes exportées', $ventes->count()],
        ];

        $headers = ['Date', 'Campagne', 'Client', 'Type carte', 'Agence', 'Statut activation'];
        $rows = $ventes->map(fn (Vente $v) => [
            $v->created_at->format('d/m/Y H:i'),
            $v->campagne?->nom ?? '—',
            $v->client ? trim($v->client->prenom.' '.$v->client->nom) : '—',
            $v->typeCarte?->code ?? '—',
            $v->agence->nom ?? '—',
            $v->statut_activation ?? '',
        ])->all();

        $definitions = [
            ['title' => 'Résumé', 'headers' => ['Indicateur', 'Valeur'], 'rows' => $resumeRows],
            ['title' => 'Ventes', 'headers' => $headers, 'rows' => $rows],
        ];

        $spreadsheet = $this->spreadsheetExportService->createMultiSheetSpreadsheet($definitions);
        $safe = preg_replace('/[^\pL\pN_-]+/u', '_', $displayName) ?? 'commercial';
        $fn = 'performance_'.$safe.'_'.$ctx['dateDebut']->format('Y-m-d').'.xlsx';

        return $this->spreadsheetExportService->download($spreadsheet, $fn);
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
     * Total des ventes servant de dénominateur pour « Part % volume » (vue commercial).
     * Campagne choisie : toutes les ventes de cette campagne sur la période. Sinon : ventes de la période, filtre agence si défini.
     */
    private function totalVentesDenombrePourPartVolume(Carbon $dateDebut, Carbon $dateFin, ?Campagne $campagne, ?int $agenceId): int
    {
        return (int) Vente::query()
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->when($campagne !== null, fn ($q) => $q->where('campagne_id', (int) $campagne->id))
            ->when($campagne === null && $agenceId !== null, fn ($q) => $q->where('agence_id', $agenceId))
            ->count();
    }

    private function ventesQueryPerformance(Carbon $dateDebut, Carbon $dateFin, ?int $agenceId, ?Campagne $campagneRef): Builder
    {
        return Vente::query()
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->when($campagneRef !== null, fn ($q) => $q->where('campagne_id', $campagneRef->id))
            ->when($agenceId !== null, fn ($q) => $q->where('agence_id', $agenceId));
    }

    /**
     * @return array{total_ventes: int, par_type: Collection}
     */
    private function aggregatePerformanceStats(Builder $statsQuery): array
    {
        $totalVentes = (clone $statsQuery)->count();

        return [
            'total_ventes' => $totalVentes,
            'par_type' => (clone $statsQuery)
                ->selectRaw('type_carte_id, COUNT(*) as total')
                ->groupBy('type_carte_id')
                ->pluck('total', 'type_carte_id'),
        ];
    }

    /**
     * Fenêtre de même durée (jours calendaires inclus) terminant la veille de dateDebut.
     *
     * @return array{0: Carbon, 1: Carbon, 2: int}
     */
    private function previousPeriodWindow(Carbon $dateDebut, Carbon $dateFin): array
    {
        $days = $dateDebut->copy()->startOfDay()->diffInDays($dateFin->copy()->startOfDay()) + 1;
        $prevFin = $dateDebut->copy()->subDay()->endOfDay();
        $prevDebut = $dateDebut->copy()->subDays($days)->startOfDay();

        return [$prevDebut, $prevFin, $days];
    }

    private function pctVariation(int $current, int $previous): ?float
    {
        if ($previous === 0) {
            return $current > 0 ? null : 0.0;
        }

        return round(($current - $previous) / $previous * 100, 1);
    }

    /**
     * Pour la vue commercial : 1re place et position du connecté sur le même classement que les ventes réelles.
     * Le total du connecté est forcé sur le décompte `ventesQueryPerformance` (évite tout décalage avec le JOIN SQL).
     *
     * @return array{
     *     classementLigneTop1: array{rang: int, user_id: int, user_name: string, total_ventes: int}|null,
     *     ligneCommercialConnecte: array{rang: int, user_id: int, user_name: string, total_ventes: int}|null
     * }
     */
    private function leaderEtMaLigneCommercialPerformances(Collection $classementCampagne, User $user, int $mesVentesReelles): array
    {
        $uid = (int) $user->id;
        $displayName = $user->prenom ? trim($user->prenom.' '.$user->name) : $user->name;

        $byUser = [];
        foreach ($classementCampagne as $c) {
            $id = (int) $c['user_id'];
            $byUser[$id] = [
                'user_id' => $id,
                'user_name' => $c['user_name'],
                'total_ventes' => (int) $c['total_ventes'],
            ];
        }

        $byUser[$uid] = [
            'user_id' => $uid,
            'user_name' => $displayName,
            'total_ventes' => $mesVentesReelles,
        ];

        $list = array_values($byUser);
        usort($list, function (array $a, array $b) {
            if ($a['total_ventes'] !== $b['total_ventes']) {
                return $b['total_ventes'] <=> $a['total_ventes'];
            }

            return $a['user_id'] <=> $b['user_id'];
        });

        $withRang = [];
        $rang = 1;
        foreach ($list as $index => $row) {
            if ($index > 0 && $row['total_ventes'] < $list[$index - 1]['total_ventes']) {
                $rang = $index + 1;
            }
            $withRang[] = array_merge($row, ['rang' => $rang]);
        }

        $leader = $withRang[0] ?? null;
        $maLigne = null;
        foreach ($withRang as $wr) {
            if ($wr['user_id'] === $uid) {
                $maLigne = $wr;
                break;
            }
        }

        return [
            'classementLigneTop1' => $leader,
            'ligneCommercialConnecte' => $maLigne,
        ];
    }

    /**
     * @return Collection<int, array{label: string, ventes: int}>
     */
    private function ventesParAgencePourChart(Builder $baseVentes): Collection
    {
        $rows = (clone $baseVentes)
            ->selectRaw('agence_id, COUNT(*) as total_ventes')
            ->groupBy('agence_id')
            ->orderByDesc('total_ventes')
            ->get();

        if ($rows->isEmpty()) {
            return collect();
        }

        $ids = $rows->pluck('agence_id')->filter(static fn ($id) => $id !== null && $id !== '')->map(static fn ($id) => (int) $id)->unique()->values();
        $noms = $ids->isNotEmpty()
            ? Agence::query()->whereIn('id', $ids)->pluck('nom', 'id')
            : collect();

        return $rows->map(function ($r) use ($noms) {
            $id = $r->agence_id;
            if ($id === null || $id === '') {
                $label = 'Sans agence';
            } else {
                $label = (string) ($noms[(int) $id] ?? ('Agence #'.$id));
            }

            return [
                'label' => $label,
                'ventes' => (int) $r->total_ventes,
            ];
        })->values();
    }

    /**
     * @return Collection<int, array{rang: int, agence_nom: string, total_ventes: int, pct_volume: float}>
     */
    private function classementAgencesPourPerformances(Builder $baseVentes): Collection
    {
        $rows = (clone $baseVentes)
            ->selectRaw('agence_id, COUNT(*) as total_ventes')
            ->groupBy('agence_id')
            ->orderByDesc('total_ventes')
            ->orderBy('agence_id')
            ->get();

        if ($rows->isEmpty()) {
            return collect();
        }

        $totalVentes = (int) $rows->sum('total_ventes');
        $ids = $rows->pluck('agence_id')->filter(static fn ($id) => $id !== null && $id !== '')->map(static fn ($id) => (int) $id)->unique()->values();
        $noms = $ids->isNotEmpty()
            ? Agence::query()->whereIn('id', $ids)->pluck('nom', 'id')
            : collect();

        $ordered = $rows->map(function ($r) use ($noms, $totalVentes) {
            $id = $r->agence_id;
            $nom = ($id === null || $id === '')
                ? 'Sans agence'
                : (string) ($noms[(int) $id] ?? ('Agence #'.$id));
            $tv = (int) $r->total_ventes;
            $pct = $totalVentes > 0 ? round($tv / $totalVentes * 100, 1) : 0.0;

            return ['agence_nom' => $nom, 'total_ventes' => $tv, 'pct_volume' => $pct];
        })->values();

        $lignes = collect();
        $rangCompetition = 1;
        foreach ($ordered as $index => $item) {
            if ($index > 0 && $item['total_ventes'] < $ordered[$index - 1]['total_ventes']) {
                $rangCompetition = $index + 1;
            }
            $lignes->push(array_merge(['rang' => $rangCompetition], $item));
        }

        return $lignes;
    }

    /**
     * @return Collection<int, array{rang: int, code: string, total_ventes: int, pct_volume: float}>
     */
    private function classementTypesCartesPourPerformances(Builder $baseVentes): Collection
    {
        $rows = (clone $baseVentes)
            ->selectRaw('type_carte_id, COUNT(*) as total_ventes')
            ->groupBy('type_carte_id')
            ->orderByDesc('total_ventes')
            ->orderBy('type_carte_id')
            ->get();

        if ($rows->isEmpty()) {
            return collect();
        }

        $totalVentes = (int) $rows->sum('total_ventes');
        $ids = $rows->pluck('type_carte_id')->filter(static fn ($id) => $id !== null && $id !== '')->map(static fn ($id) => (int) $id)->unique()->values();
        $codes = $ids->isNotEmpty()
            ? TypeCarte::query()->whereIn('id', $ids)->pluck('code', 'id')
            : collect();

        $ordered = $rows->map(function ($r) use ($codes, $totalVentes) {
            $id = $r->type_carte_id;
            $code = ($id === null || $id === '')
                ? '—'
                : (string) ($codes[(int) $id] ?? ('#'.$id));
            $tv = (int) $r->total_ventes;
            $pct = $totalVentes > 0 ? round($tv / $totalVentes * 100, 1) : 0.0;

            return ['code' => $code, 'total_ventes' => $tv, 'pct_volume' => $pct];
        })->values();

        $lignes = collect();
        $rangCompetition = 1;
        foreach ($ordered as $index => $item) {
            if ($index > 0 && $item['total_ventes'] < $ordered[$index - 1]['total_ventes']) {
                $rangCompetition = $index + 1;
            }
            $lignes->push(array_merge(['rang' => $rangCompetition], $item));
        }

        return $lignes;
    }

    private function resolveCampagneFiltre(Request $request, ?int $agenceId, ?User $user): ?Campagne
    {
        if (! $request->filled('campagne_id') || ! $user) {
            return null;
        }
        $c = Campagne::query()->find((int) $request->campagne_id);
        if (! $c) {
            return null;
        }
        if ($user->isAdmin() || $user->isDirection()) {
            return $c;
        }
        if (($user->isCommercial() || $user->isCommercialTelephonique()) && $user->agence_id && $c->concerneAgence((int) $user->agence_id)) {
            return $c;
        }

        return null;
    }

    private function campagnesPourPerformancesSelect(?int $agenceId, ?User $user): Collection
    {
        if (! $user) {
            return collect();
        }
        $q = Campagne::query()->whereNotIn('statut', [Campagne::STATUT_ANNULEE])->orderByDesc('date_debut');
        if ($user->isAdmin() || $user->isDirection()) {
            return $q->get(['id', 'nom', 'date_debut', 'date_fin']);
        }
        if ($agenceId) {
            return $q->where(function ($w) use ($agenceId) {
                $w->where('toutes_agences', true)
                    ->orWhereHas('agences', fn ($a) => $a->where('agences.id', $agenceId));
            })->get(['id', 'nom', 'date_debut', 'date_fin']);
        }

        return collect();
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
     *     campagnePerformances: Campagne|null,
     *     campagneRef: Campagne|null,
     *     campagneIdSelected: int|null,
     *     compareEnabled: bool
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
        $campagneFiltre = $this->resolveCampagneFiltre($request, $agenceId, $user);
        $campagneRef = $campagneFiltre ?? $campagnePerformances;

        $campagneIdSelected = $campagneFiltre?->id;
        $compareEnabled = $request->boolean('compare');

        if ($filtreIntervalle) {
            $dateDebut = Carbon::parse($du)->startOfDay();
            $dateFin = Carbon::parse($au)->endOfDay();
            if ($dateDebut->gt($dateFin)) {
                [$dateDebut, $dateFin] = [$dateFin->copy()->startOfDay(), $dateDebut->copy()->endOfDay()];
            }
            $libellePeriode = 'Du '.$dateDebut->format('d/m/Y').' au '.$dateFin->format('d/m/Y');
            if ($campagneFiltre) {
                $libellePeriode .= ' — campagne « '.$campagneFiltre->nom.' »';
            }
        } elseif ($request->filled('periode')) {
            $periodeMois = $request->query('periode');
            $dateDebut = Carbon::parse($periodeMois.'-01')->startOfMonth();
            $dateFin = $dateDebut->copy()->endOfMonth();
            $libellePeriode = $dateDebut->locale('fr')->translatedFormat('F Y');
        } elseif ($campagneFiltre) {
            $dateDebut = $campagneFiltre->date_debut->copy()->startOfDay();
            $dateFin = $campagneFiltre->date_fin->copy()->endOfDay();
            $libellePeriode = 'Campagne « '.$campagneFiltre->nom.' » ('.$dateDebut->format('d/m/Y').' – '.$dateFin->format('d/m/Y').')';
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
            'campagneRef' => $campagneRef,
            'campagneIdSelected' => $campagneIdSelected,
            'compareEnabled' => $compareEnabled,
        ];
    }
}
