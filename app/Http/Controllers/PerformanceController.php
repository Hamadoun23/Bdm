<?php

namespace App\Http\Controllers;

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
        $user = $request->user();
        $periode = $request->query('periode', now()->format('Y-m'));

        if ($user?->isAdmin() || $user?->isDirection()) {
            $agenceId = $request->query('agence') ? (int) $request->query('agence') : null;
        } elseif ($user?->isCommercial()) {
            $agenceId = $user->agence_id;
        } else {
            $agenceId = null;
        }

        $classementComplet = $this->primeService->getClassement($periode, $agenceId);

        $dateDebut = Carbon::parse($periode.'-01')->startOfMonth();
        $dateFin = $dateDebut->copy()->endOfMonth();

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

        $vueCommerciale = $user?->isCommercial() ?? false;
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
            $mesVentesMois = (int) ($classementComplet->firstWhere('user_id', $user->id)['total_ventes'] ?? 0);
            $monRang = $idx !== false ? $idx + 1 : null;
            $stats['mes_ventes'] = $mesVentesMois;
            $stats['mon_rang'] = $monRang;
        }

        return view('performance.index', compact(
            'classement',
            'classementTop3',
            'maLigne',
            'stats',
            'periode',
            'agenceId',
            'typesCartes',
            'vueCommerciale',
            'vueChef',
            'user'
        ));
    }
}
