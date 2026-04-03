<?php

namespace App\Http\Controllers;

use App\Models\Campagne;
use App\Models\TypeCarte;
use App\Models\Vente;
use App\Services\PrimeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerformanceController extends Controller
{
    public function __construct(
        private PrimeService $primeService
    ) {}

    public function index(Request $request): View
    {
        Campagne::syncStatuts();
        $user = $request->user();

        if ($user?->isAdmin() || $user?->isDirection()) {
            $agenceId = $request->query('agence') ? (int) $request->query('agence') : null;
        } elseif ($user?->isCommercial() || $user?->isCommercialTelephonique()) {
            $agenceId = $user->agence_id;
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

        $classementComplet = $this->primeService->getClassementBetween($dateDebut, $dateFin, $agenceId);

        $statsQuery = Vente::query()
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->when($agenceId, fn ($q) => $q->where('agence_id', $agenceId));

        $stats = [
            'total_ventes' => (clone $statsQuery)->count(),
            'par_type' => (clone $statsQuery)
                ->selectRaw('type_carte_id, COUNT(*) as total')
                ->groupBy('type_carte_id')
                ->pluck('total', 'type_carte_id'),
        ];

        $typesCartes = TypeCarte::orderBy('code')->get();

        $vueCommerciale = $user && ($user->isCommercial() || $user->isCommercialTelephonique());
        $vueChef = false;

        $classement = $classementComplet;
        $classementTop3 = collect();
        $maLigne = null;

        if ($vueCommerciale) {
            $classementTop3 = $classementComplet->take(3);
            $idx = $classementComplet->search(fn ($c) => $c['user_id'] == $user->id);
            if ($idx !== false && $idx >= 3) {
                $maLigne = $classementComplet->values()[$idx];
            }
            $mesVentesPeriode = (int) Vente::query()
                ->where('user_id', $user->id)
                ->whereBetween('created_at', [$dateDebut, $dateFin])
                ->count();
            $monRang = $idx !== false ? $idx + 1 : null;
            $stats['mes_ventes'] = $mesVentesPeriode;
            $stats['mon_rang'] = $monRang;
        }

        return view('performance.index', compact(
            'classement',
            'classementTop3',
            'maLigne',
            'stats',
            'agenceId',
            'typesCartes',
            'vueCommerciale',
            'vueChef',
            'user',
            'libellePeriode',
            'campagnePerformances',
            'dateDebut',
            'dateFin',
            'filtreIntervalle',
            'du',
            'au'
        ));
    }
}
