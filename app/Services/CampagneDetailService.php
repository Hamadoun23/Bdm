<?php

namespace App\Services;

use App\Models\Agence;
use App\Models\Campagne;
use App\Models\Prime;
use App\Models\TypeCarte;
use App\Models\User;
use App\Models\Vente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CampagneDetailService
{
    public function __construct(
        private CampagneRapportService $campagneRapportService
    ) {}

    /**
     * @return array{preset: string, periode_debut: Carbon, periode_fin: Carbon, campagne: Campagne, stats: array, classement: Collection, primes: Collection, typesCartes: Collection, telephoniqueCampagne: array{nb_fiches: int, appels_emis: int, appels_joignables: int, appels_non_joignables: int, clients_interesses: int, clients_deja_servis: int}}
     */
    public function buildShowData(Campagne $campagne, ?Request $request = null): array
    {
        [$preset, $dateDebut, $dateFin] = self::resolvePeriodeFromRequest($request, $campagne);

        $campagne->load([
            'agences', 'actions.user', 'beneficiairesAide.agence', 'typesCartesRemise',
            'signatairesContrat.agence', 'contratReponses.user', 'aideVersements.user',
            'contratArticles',
        ]);

        $campDebut = $campagne->date_debut->copy()->startOfDay();
        $campFin = $campagne->date_fin->copy()->endOfDay();

        $dateDebut = $dateDebut->copy()->max($campDebut);
        $dateFin = $dateFin->copy()->min($campFin);
        if ($dateDebut->gt($dateFin)) {
            $dateDebut = $campDebut->copy();
            $dateFin = $campFin->copy();
        }

        $agenceIdsCampagne = $campagne->toutes_agences ? null : $campagne->agences->pluck('id');
        $queryVentes = Vente::query()->where(function ($q) use ($campagne, $dateDebut, $dateFin, $agenceIdsCampagne) {
            $q->where(function ($q1) use ($campagne, $dateDebut, $dateFin) {
                $q1->where('campagne_id', $campagne->id)
                    ->whereBetween('created_at', [$dateDebut, $dateFin]);
            })->orWhere(function ($q2) use ($dateDebut, $dateFin, $agenceIdsCampagne) {
                $q2->whereNull('campagne_id')
                    ->whereBetween('created_at', [$dateDebut, $dateFin]);
                if ($agenceIdsCampagne !== null && $agenceIdsCampagne->isNotEmpty()) {
                    $q2->whereIn('agence_id', $agenceIdsCampagne);
                }
            });
        });

        $stats = [
            'total_ventes' => (clone $queryVentes)->count(),
            'montant_total' => (clone $queryVentes)->sum('montant'),
            'par_type' => (clone $queryVentes)->selectRaw('type_carte_id, COUNT(*) as nb, SUM(montant) as mt')
                ->groupBy('type_carte_id')->get()->keyBy('type_carte_id'),
            'par_agence' => (clone $queryVentes)->selectRaw('agence_id, COUNT(*) as nb, SUM(montant) as mt')
                ->groupBy('agence_id')->get(),
        ];
        $agenceIds = $stats['par_agence']->pluck('agence_id')->filter()->unique();
        $agencesMap = Agence::whereIn('id', $agenceIds)->get()->keyBy('id');
        $stats['par_agence'] = $stats['par_agence']->map(function ($row) use ($agencesMap) {
            $row->agence_nom = $agencesMap->get($row->agence_id)?->nom ?? 'N/A';

            return $row;
        });

        $classementRaw = (clone $queryVentes)
            ->selectRaw('user_id, COUNT(*) as total_ventes, SUM(montant) as montant_total')
            ->groupBy('user_id')
            ->orderByDesc('total_ventes')
            ->get();

        $userIds = $classementRaw->pluck('user_id')->filter()->unique();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');
        $classement = $classementRaw->map(function ($row, $i) use ($users) {
            $user = $users->get($row->user_id);
            $name = $user?->prenom ? trim($user->prenom.' '.$user->name) : ($user?->name ?? '-');

            return [
                'rang' => $i + 1,
                'user_name' => $name,
                'total_ventes' => $row->total_ventes,
                'montant_total' => $row->montant_total,
            ];
        })->values();

        $periodes = [];
        $current = $dateDebut->copy()->startOfMonth();
        $endMonth = $dateFin->copy()->startOfMonth();
        while ($current->lte($endMonth)) {
            $periodes[] = $current->format('Y-m');
            $current->addMonth();
        }
        $primes = Prime::whereIn('user_id', $userIds)
            ->whereIn('periode', array_unique($periodes))
            ->with('user')
            ->orderBy('periode')
            ->get();

        $typesCartes = TypeCarte::orderBy('code')->get();

        $periode_debut = $dateDebut;
        $periode_fin = $dateFin;

        $telephoniqueCampagne = $this->campagneRapportService->agregatsTelephonique(
            $campagne,
            $dateDebut,
            $dateFin,
            null,
            null
        );

        return compact(
            'campagne', 'stats', 'classement', 'primes', 'typesCartes',
            'preset', 'periode_debut', 'periode_fin', 'telephoniqueCampagne'
        );
    }

    /**
     * @return array{0: string, 1: Carbon, 2: Carbon}
     */
    public static function resolvePeriodeFromRequest(?Request $request, Campagne $campagne): array
    {
        $campDebut = $campagne->date_debut->copy()->startOfDay();
        $campFin = $campagne->date_fin->copy()->endOfDay();

        if ($request === null) {
            return ['campagne', $campDebut, $campFin];
        }

        $preset = $request->get('periode', 'campagne');
        $now = now();

        switch ($preset) {
            case 'semaine':
                return ['semaine', $now->copy()->startOfWeek()->startOfDay(), $now->copy()->endOfWeek()->endOfDay()];
            case 'mois':
                return ['mois', $now->copy()->startOfMonth()->startOfDay(), $now->copy()->endOfMonth()->endOfDay()];
            case 'perso':
                $from = $request->date('date_debut')?->copy()->startOfDay() ?? $campDebut;
                $to = $request->date('date_fin')?->copy()->endOfDay() ?? $campFin;

                return ['perso', $from, $to];
            default:
                return ['campagne', $campDebut, $campFin];
        }
    }
}
