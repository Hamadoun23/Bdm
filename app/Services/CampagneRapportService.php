<?php

namespace App\Services;

use App\Models\Agence;
use App\Models\Campagne;
use App\Models\TelephoniqueRapport;
use App\Models\TypeCarte;
use App\Models\User;
use App\Models\Vente;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Synthèses et exports pour le reporting « toute la campagne » (ventes filtrées par campagne_id + plage).
 */
class CampagneRapportService
{
    /**
     * @return array{
     *     date_debut: Carbon,
     *     date_fin: Carbon,
     *     resume: array{
     *         total_ventes: int,
     *         nb_commerciaux_perimetre: int,
     *         nb_avec_ventes: int,
     *         nb_zero_vente: int,
     *         nb_agences_avec_ventes: int
     *     },
     *     commerciaux: Collection<int, array{user_id: int, user_name: string, agence_id: int|null, agence_nom: string|null, total_ventes: int, rang: int}>,
     *     agences: Collection<int, array{agence_id: int|null, agence_nom: string, total_ventes: int, pct_volume: float, nb_commerciaux: int}>,
     *     par_type_carte: Collection<int, array{type_carte_id: int|null, code: string, total_ventes: int, pct_volume: float}>,
     *     par_semaine: Collection<int, array{cle: string, libelle: string, total_ventes: int}>,
     *     par_mois: Collection<int, array{cle: string, libelle: string, total_ventes: int}>
     * }
     */
    public function synthese(
        Campagne $campagne,
        Carbon $dateDebut,
        Carbon $dateFin,
        ?int $filtreAgenceId = null,
        ?int $filtreUserId = null
    ): array {
        $campagne->loadMissing('agences');
        $dateDebut = $dateDebut->copy()->startOfDay();
        $dateFin = $dateFin->copy()->endOfDay();

        $ventesBase = $this->ventesFiltreesQuery($campagne->id, $dateDebut, $dateFin, $filtreAgenceId, $filtreUserId);

        $totalVentes = (clone $ventesBase)->count();

        $usersQuery = $this->usersPerimetreQuery($campagne);
        if ($filtreAgenceId !== null) {
            $usersQuery->where('users.agence_id', $filtreAgenceId);
        }
        if ($filtreUserId !== null) {
            $usersQuery->where('users.id', $filtreUserId);
        }

        $nbCommerciauxPerimetre = (clone $usersQuery)->count();

        $agregaVent = Vente::query()
            ->selectRaw('ventes.user_id, COUNT(ventes.id) as cnt')
            ->where('ventes.campagne_id', $campagne->id)
            ->whereBetween('ventes.created_at', [$dateDebut, $dateFin])
            ->when($filtreAgenceId !== null, fn ($q) => $q->where('ventes.agence_id', $filtreAgenceId))
            ->when($filtreUserId !== null, fn ($q) => $q->where('ventes.user_id', $filtreUserId))
            ->groupBy('ventes.user_id');

        $commerciauxRows = (clone $usersQuery)
            ->leftJoinSub($agregaVent, 'v', 'users.id', '=', 'v.user_id')
            ->selectRaw('users.id as user_id, users.name, users.prenom, users.agence_id, COALESCE(v.cnt, 0) as total_ventes')
            ->orderByDesc('total_ventes')
            ->orderBy('users.id')
            ->get();

        $agenceIds = $commerciauxRows->pluck('agence_id')->filter()->unique()->values()->all();
        $agencesById = Agence::query()->whereIn('id', $agenceIds)->get()->keyBy('id');

        $rangCompetition = 1;
        $commerciaux = collect();
        foreach ($commerciauxRows as $index => $row) {
            if ($index > 0 && (int) $row->total_ventes < (int) $commerciauxRows[$index - 1]->total_ventes) {
                $rangCompetition = $index + 1;
            }
            $agence = $row->agence_id ? $agencesById->get($row->agence_id) : null;
            $commerciaux->push([
                'user_id' => (int) $row->user_id,
                'user_name' => $row->prenom ? trim($row->prenom.' '.$row->name) : $row->name,
                'agence_id' => $row->agence_id ? (int) $row->agence_id : null,
                'agence_nom' => $agence?->nom,
                'total_ventes' => (int) $row->total_ventes,
                'rang' => $rangCompetition,
            ]);
        }

        $nbAvecVentes = $commerciaux->where('total_ventes', '>', 0)->count();
        $nbZeroVente = $commerciaux->where('total_ventes', 0)->count();

        $agencesData = (clone $ventesBase)
            ->selectRaw('ventes.agence_id, COUNT(ventes.id) as cnt')
            ->groupBy('ventes.agence_id')
            ->get();

        $nbAgencesAvecVentes = $agencesData->filter(fn ($r) => $r->agence_id !== null)->count();

        $agences = collect();
        foreach ($agencesData as $row) {
            if ($row->agence_id === null) {
                continue;
            }
            $nom = Agence::query()->find($row->agence_id)?->nom ?? 'Agence #'.$row->agence_id;
            $nbCommerciauxAgence = User::query()
                ->whereIn('role', ['commercial', 'commercial_telephonique'])
                ->where('agence_id', $row->agence_id)
                ->count();
            $pct = $totalVentes > 0 ? round((int) $row->cnt / $totalVentes * 100, 2) : 0.0;
            $agences->push([
                'agence_id' => (int) $row->agence_id,
                'agence_nom' => $nom,
                'total_ventes' => (int) $row->cnt,
                'pct_volume' => $pct,
                'nb_commerciaux' => $nbCommerciauxAgence,
            ]);
        }
        $agences = $agences->sortByDesc('total_ventes')->values();

        $typesData = (clone $ventesBase)
            ->selectRaw('ventes.type_carte_id, COUNT(ventes.id) as cnt')
            ->groupBy('ventes.type_carte_id')
            ->get();

        $typesCartes = TypeCarte::query()->whereIn('id', $typesData->pluck('type_carte_id')->filter())->get()->keyBy('id');

        $parTypeCarte = collect();
        foreach ($typesData as $row) {
            $tc = $row->type_carte_id ? $typesCartes->get($row->type_carte_id) : null;
            $pct = $totalVentes > 0 ? round((int) $row->cnt / $totalVentes * 100, 2) : 0.0;
            $parTypeCarte->push([
                'type_carte_id' => $row->type_carte_id ? (int) $row->type_carte_id : null,
                'code' => $tc?->code ?? '?',
                'total_ventes' => (int) $row->cnt,
                'pct_volume' => $pct,
            ]);
        }
        $parTypeCarte = $parTypeCarte->sortByDesc('total_ventes')->values();

        $parSemaine = $this->agregerParPeriode($ventesBase, 'semaine');
        $parMois = $this->agregerParPeriode($ventesBase, 'mois');

        return [
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'resume' => [
                'total_ventes' => $totalVentes,
                'nb_commerciaux_perimetre' => $nbCommerciauxPerimetre,
                'nb_avec_ventes' => $nbAvecVentes,
                'nb_zero_vente' => $nbZeroVente,
                'nb_agences_avec_ventes' => $nbAgencesAvecVentes,
            ],
            'commerciaux' => $commerciaux,
            'agences' => $agences,
            'par_type_carte' => $parTypeCarte,
            'par_semaine' => $parSemaine,
            'par_mois' => $parMois,
        ];
    }

    public function ventesFiltreesQuery(
        int $campagneId,
        Carbon $dateDebut,
        Carbon $dateFin,
        ?int $filtreAgenceId = null,
        ?int $filtreUserId = null,
        ?int $filtreTypeCarteId = null
    ): Builder {
        return Vente::query()
            ->where('campagne_id', $campagneId)
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->when($filtreAgenceId !== null, fn ($q) => $q->where('agence_id', $filtreAgenceId))
            ->when($filtreUserId !== null, fn ($q) => $q->where('user_id', $filtreUserId))
            ->when($filtreTypeCarteId !== null, fn ($q) => $q->where('type_carte_id', $filtreTypeCarteId));
    }

    /**
     * Agrégation temporelle sur une requête ventes déjà filtrée (performances, exports croisés).
     *
     * @param  'semaine'|'mois'  $mode
     * @return Collection<int, array{cle: string, libelle: string, total_ventes: int}>
     */
    public function agregerVentesParPeriode(Builder $ventesBase, string $mode): Collection
    {
        return $this->agregerParPeriode($ventesBase, $mode);
    }

    /** Requête utilisateurs du même périmètre que {@see PrimeService::getClassementPourCampagne}. */
    public function usersPerimetreQuery(Campagne $campagne): Builder
    {
        $campagne->loadMissing('agences');

        return User::query()
            ->whereIn('users.role', ['commercial', 'commercial_telephonique'])
            ->when(! $campagne->toutes_agences, function ($q) use ($campagne) {
                $ids = $campagne->agences->pluck('id');
                if ($ids->isEmpty()) {
                    $q->whereRaw('0 = 1');
                } else {
                    $q->whereIn('users.agence_id', $ids->all());
                }
            });
    }

    /**
     * @return Collection<int, array{cle: string, libelle: string, total_ventes: int}>
     */
    private function agregerParPeriode(Builder $ventesBase, string $mode): Collection
    {
        $driver = DB::getDriverName();
        $q = clone $ventesBase;

        if ($mode === 'semaine') {
            if ($driver === 'mysql') {
                $rows = $q->selectRaw('YEARWEEK(ventes.created_at, 3) as periode_cle, COUNT(ventes.id) as cnt')
                    ->groupBy('periode_cle')
                    ->orderBy('periode_cle')
                    ->get();
            } else {
                $rows = $q->selectRaw("strftime('%Y-W%W', ventes.created_at) as periode_cle, COUNT(ventes.id) as cnt")
                    ->groupBy('periode_cle')
                    ->orderBy('periode_cle')
                    ->get();
            }
        } else {
            if ($driver === 'mysql') {
                $rows = $q->selectRaw("DATE_FORMAT(ventes.created_at, '%Y-%m') as periode_cle, COUNT(ventes.id) as cnt")
                    ->groupBy('periode_cle')
                    ->orderBy('periode_cle')
                    ->get();
            } else {
                $rows = $q->selectRaw("strftime('%Y-%m', ventes.created_at) as periode_cle, COUNT(ventes.id) as cnt")
                    ->groupBy('periode_cle')
                    ->orderBy('periode_cle')
                    ->get();
            }
        }

        return $rows->map(function ($row) use ($mode) {
            $cle = (string) $row->periode_cle;
            if ($mode === 'semaine') {
                $libelle = $this->libelleSemaineIso($cle);
            } elseif ($mode === 'mois' && strlen($cle) === 7) {
                $libelle = Carbon::createFromFormat('Y-m', $cle)->locale('fr')->translatedFormat('F Y');
            } else {
                $libelle = $cle;
            }

            return [
                'cle' => $cle,
                'libelle' => $libelle,
                'total_ventes' => (int) $row->cnt,
            ];
        })->values();
    }

    /**
     * Libellé lisible pour une clé de semaine :
     * — MySQL YEARWEEK(d, 3) : entier AAAASS (ex. 202614 → 2026, semaine ISO 14) ;
     * — SQLite strftime('%Y-W%W') : ex. 2026-W14.
     */
    private function libelleSemaineIso(string $cle): string
    {
        $cle = trim($cle);
        $year = null;
        $week = null;

        if (preg_match('/^(\d{4})(\d{2})$/', $cle, $m)) {
            $year = (int) $m[1];
            $week = (int) $m[2];
        } elseif (preg_match('/^(\d{4})-W(\d{1,2})$/i', $cle, $m)) {
            $year = (int) $m[1];
            $week = (int) $m[2];
        }

        if ($year !== null && $week !== null && $week >= 1 && $week <= 53) {
            try {
                $lun = (new Carbon)->setISODate($year, $week, 1)->locale('fr')->startOfDay();
                $dim = (new Carbon)->setISODate($year, $week, 7)->locale('fr')->startOfDay();

                return $this->libellePlageSemaineFr($lun, $dim);
            } catch (\Throwable) {
                return $cle;
            }
        }

        if (str_contains($cle, '-')) {
            return 'Semaine '.$cle;
        }

        return $cle;
    }

    /** Ex. « 30 mars – 5 avril 2026 » (mois du second jour avec majuscule). */
    private function libellePlageSemaineFr(Carbon $lun, Carbon $dim): string
    {
        $debut = $lun->isoFormat('D MMMM');
        if ($lun->year !== $dim->year) {
            $debut .= ' '.$lun->isoFormat('YYYY');
        }
        $jourFin = $dim->isoFormat('D');
        $moisFin = $dim->isoFormat('MMMM');
        if ($moisFin !== '') {
            $moisFin = mb_strtoupper(mb_substr($moisFin, 0, 1)).mb_substr($moisFin, 1);
        }
        $anFin = $dim->isoFormat('YYYY');

        return $debut.' – '.$jourFin.' '.$moisFin.' '.$anFin;
    }

    /**
     * Fiches téléphoniques liées à une campagne pour une fenêtre de dates :
     * — rattachées explicitement (campagne_id = cette campagne) ;
     * — ou anciennes fiches sans campagne_id, date dans la fenêtre, téléopératrice en rôle commercial_telephonique
     *   et agence dans le périmètre de la campagne (même idée que les ventes sans campagne_id).
     *
     * @return Builder<TelephoniqueRapport>
     */
    public function telephoniqueRapportsPourCampagneQuery(
        Campagne $campagne,
        Carbon $dateDebut,
        Carbon $dateFin,
        ?int $filtreAgenceId = null,
        ?int $filtreUserId = null
    ): Builder {
        $campagne->loadMissing('agences');
        $db = $dateDebut->copy()->startOfDay()->format('Y-m-d');
        $df = $dateFin->copy()->endOfDay()->format('Y-m-d');

        return TelephoniqueRapport::query()
            ->whereBetween('date_rapport', [$db, $df])
            ->where(function ($outer) use ($campagne, $filtreAgenceId, $filtreUserId) {
                $outer->where(function ($brancheLiee) use ($campagne, $filtreAgenceId, $filtreUserId) {
                    $brancheLiee->where('campagne_id', $campagne->id);
                    if ($filtreUserId !== null) {
                        $brancheLiee->where('user_id', $filtreUserId);
                    }
                    if ($filtreAgenceId !== null) {
                        $brancheLiee->whereHas('user', fn ($u) => $u->where('agence_id', $filtreAgenceId));
                    }
                });
                $outer->orWhere(function ($brancheOrpheline) use ($campagne, $filtreAgenceId, $filtreUserId) {
                    $brancheOrpheline->whereNull('campagne_id')
                        ->whereHas('user', function ($uq) use ($campagne, $filtreAgenceId, $filtreUserId) {
                            $uq->where('role', 'commercial_telephonique');
                            if (! $campagne->toutes_agences) {
                                $ids = $campagne->agences->pluck('id');
                                if ($ids->isEmpty()) {
                                    $uq->whereRaw('0 = 1');
                                } else {
                                    $uq->whereIn('agence_id', $ids->all());
                                }
                            }
                            if ($filtreAgenceId !== null) {
                                $uq->where('agence_id', $filtreAgenceId);
                            }
                            if ($filtreUserId !== null) {
                                $uq->where('id', $filtreUserId);
                            }
                        });
                });
            });
    }

    /**
     * Totaux cumulés sur une requête fiches (liste filtrée), hors pagination.
     *
     * @param  Builder<TelephoniqueRapport>  $query
     * @return array{nb_fiches: int, appels_emis: int, appels_joignables: int, appels_non_joignables: int, clients_interesses: int, clients_deja_servis: int}
     */
    public function totauxTelephoniqueListe(Builder $query): array
    {
        return [
            'nb_fiches' => (clone $query)->count(),
            'appels_emis' => (int) (clone $query)->sum('appels_emis'),
            'appels_joignables' => (int) (clone $query)->sum('appels_joignables'),
            'appels_non_joignables' => (int) (clone $query)->sum('appels_non_joignables'),
            'clients_interesses' => (int) (clone $query)->sum('clients_interesses_nombre'),
            'clients_deja_servis' => (int) (clone $query)->sum('clients_deja_servis_nombre'),
        ];
    }

    /**
     * @return array{nb_fiches: int, appels_emis: int, appels_joignables: int, appels_non_joignables: int, clients_interesses: int, clients_deja_servis: int}
     */
    public function agregatsTelephonique(
        Campagne $campagne,
        Carbon $dateDebut,
        Carbon $dateFin,
        ?int $filtreAgenceId = null,
        ?int $filtreUserId = null
    ): array {
        $base = $this->telephoniqueRapportsPourCampagneQuery($campagne, $dateDebut, $dateFin, $filtreAgenceId, $filtreUserId);

        return [
            'nb_fiches' => (clone $base)->count(),
            'appels_emis' => (int) (clone $base)->sum('appels_emis'),
            'appels_joignables' => (int) (clone $base)->sum('appels_joignables'),
            'appels_non_joignables' => (int) (clone $base)->sum('appels_non_joignables'),
            'clients_interesses' => (int) (clone $base)->sum('clients_interesses_nombre'),
            'clients_deja_servis' => (int) (clone $base)->sum('clients_deja_servis_nombre'),
        ];
    }
}
