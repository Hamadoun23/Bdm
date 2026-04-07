<?php

namespace App\Services;

use App\Models\Campagne;
use App\Models\Prime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PrimeService
{
    /**
     * @param  bool  $seulementSignatairesActifs  true = restreindre aux commerciaux « actifs » (signataires campagne vivante), ex. calcul des primes ; false = tous les commerciaux du périmètre (performances / classement comme pour l’admin).
     */
    public function getClassement(?string $periode = null, ?int $agenceId = null, bool $seulementSignatairesActifs = false): Collection
    {
        $periode = $periode ?? now()->format('Y-m');
        $dateDebut = Carbon::parse($periode.'-01')->startOfMonth();
        $dateFin = $dateDebut->copy()->endOfMonth();

        return $this->getClassementBetween($dateDebut, $dateFin, $agenceId, $seulementSignatairesActifs);
    }

    /**
     * Classement sur le périmètre d’une campagne : ventes avec ce `campagne_id` sur la période,
     * et commerciaux rattachés aux agences de la campagne (ou tous si « toutes agences »).
     *
     * @param  ?int  $ventesAgenceId  Si renseigné (ex. filtre « Agence » sur l’écran performances), seules les ventes avec ce `ventes.agence_id` sont comptées — aligné sur les totaux du tableau de bord.
     */
    public function getClassementPourCampagne(
        Campagne $campagne,
        Carbon $dateDebut,
        Carbon $dateFin,
        bool $seulementSignatairesActifs = false,
        ?int $ventesAgenceId = null
    ): Collection {
        $campagne->loadMissing('agences');
        $campagneId = (int) $campagne->id;

        $query = User::query()
            ->whereIn('users.role', ['commercial', 'commercial_telephonique'])
            ->when($seulementSignatairesActifs, fn ($q) => $q->where('users.actif', true))
            ->when(! $campagne->toutes_agences, function ($q) use ($campagne) {
                $ids = $campagne->agences->pluck('id');
                if ($ids->isEmpty()) {
                    $q->whereRaw('0 = 1');
                } else {
                    $q->whereIn('users.agence_id', $ids->all());
                }
            })
            ->leftJoin('ventes', function ($join) use ($campagneId, $dateDebut, $dateFin, $ventesAgenceId) {
                $join->on('users.id', '=', 'ventes.user_id')
                    ->where('ventes.campagne_id', '=', $campagneId)
                    ->whereBetween('ventes.created_at', [$dateDebut, $dateFin]);
                if ($ventesAgenceId !== null) {
                    $join->where('ventes.agence_id', '=', $ventesAgenceId);
                }
            })
            ->selectRaw('users.id as user_id, users.name, users.prenom, COALESCE(COUNT(ventes.id), 0) as total')
            ->groupBy('users.id', 'users.name', 'users.prenom')
            ->orderByDesc('total')
            ->orderBy('users.id');

        $rows = $query->get()->values();
        $lignes = collect();
        $rangCompetition = 1;

        foreach ($rows as $index => $row) {
            if ($index > 0 && (int) $row->total < (int) $rows[$index - 1]->total) {
                $rangCompetition = $index + 1;
            }
            $displayName = $row->prenom ? trim($row->prenom.' '.$row->name) : $row->name;
            $lignes->push([
                'rang' => $rangCompetition,
                'user_id' => (int) $row->user_id,
                'user_name' => $displayName,
                'total_ventes' => (int) $row->total,
            ]);
        }

        return $lignes;
    }

    /**
     * Classement des commerciaux sur une plage de dates (ventes agrégées).
     *
     * @param  bool  $seulementSignatairesActifs  Même logique que {@see getClassement()}.
     * @param  ?int  $ventesAgenceId  Si renseigné, restreint le comptage des ventes à cette agence (aligné performances / filtres tableau de bord).
     */
    public function getClassementBetween(Carbon $dateDebut, Carbon $dateFin, ?int $agenceId = null, bool $seulementSignatairesActifs = false, ?int $ventesAgenceId = null): Collection
    {
        $query = User::query()
            ->whereIn('users.role', ['commercial', 'commercial_telephonique'])
            ->when($seulementSignatairesActifs, fn ($q) => $q->where('users.actif', true))
            ->when($agenceId, fn ($q) => $q->where('users.agence_id', $agenceId))
            ->leftJoin('ventes', function ($join) use ($dateDebut, $dateFin, $ventesAgenceId) {
                $join->on('users.id', '=', 'ventes.user_id')
                    ->whereBetween('ventes.created_at', [$dateDebut, $dateFin]);
                if ($ventesAgenceId !== null) {
                    $join->where('ventes.agence_id', '=', $ventesAgenceId);
                }
            })
            ->selectRaw('users.id as user_id, users.name, users.prenom, COALESCE(COUNT(ventes.id), 0) as total')
            ->groupBy('users.id', 'users.name', 'users.prenom')
            ->orderByDesc('total')
            ->orderBy('users.id');

        $rows = $query->get()->values();
        $lignes = collect();
        $rangCompetition = 1;

        foreach ($rows as $index => $row) {
            if ($index > 0 && (int) $row->total < (int) $rows[$index - 1]->total) {
                $rangCompetition = $index + 1;
            }
            $displayName = $row->prenom ? trim($row->prenom.' '.$row->name) : $row->name;
            $lignes->push([
                'rang' => $rangCompetition,
                'user_id' => (int) $row->user_id,
                'user_name' => $displayName,
                'total_ventes' => (int) $row->total,
            ]);
        }

        return $lignes;
    }

    public function calculerPrimes(string $periode, ?int $agenceId = null): array
    {
        $classement = $this->getClassement($periode, $agenceId, true);
        $campagne = Campagne::getActiveForAgence($agenceId);

        if (! $campagne) {
            return [];
        }

        Prime::where('periode', $periode)->where('rang', 2)->delete();

        $primesCreees = [];
        $top1 = $classement->first();

        if ($top1) {
            $prime = Prime::updateOrCreate(
                ['user_id' => $top1['user_id'], 'periode' => $periode],
                ['montant' => $campagne->prime_meilleur_vendeur, 'rang' => 1]
            );
            $primesCreees[] = $prime;
        }

        return $primesCreees;
    }
}
