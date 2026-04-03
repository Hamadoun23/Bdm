<?php

namespace App\Services;

use App\Models\Campagne;
use App\Models\Prime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PrimeService
{
    public function getClassement(?string $periode = null, ?int $agenceId = null): Collection
    {
        $periode = $periode ?? now()->format('Y-m');
        $dateDebut = Carbon::parse($periode.'-01')->startOfMonth();
        $dateFin = $dateDebut->copy()->endOfMonth();

        return $this->getClassementBetween($dateDebut, $dateFin, $agenceId);
    }

    /** Classement des commerciaux sur une plage de dates (ventes agrégées). */
    public function getClassementBetween(Carbon $dateDebut, Carbon $dateFin, ?int $agenceId = null): Collection
    {
        $query = User::query()
            ->where('users.role', 'commercial')
            ->where('users.actif', true)
            ->when($agenceId, fn ($q) => $q->where('users.agence_id', $agenceId))
            ->leftJoin('ventes', function ($join) use ($dateDebut, $dateFin, $agenceId) {
                $join->on('users.id', '=', 'ventes.user_id')
                    ->whereBetween('ventes.created_at', [$dateDebut, $dateFin]);
                if ($agenceId) {
                    $join->where('ventes.agence_id', '=', $agenceId);
                }
            })
            ->selectRaw('users.id as user_id, users.name, users.prenom, COALESCE(COUNT(ventes.id), 0) as total')
            ->groupBy('users.id', 'users.name', 'users.prenom')
            ->orderByDesc('total');

        return $query->get()->map(function ($row, $index) {
            $displayName = $row->prenom ? trim($row->prenom.' '.$row->name) : $row->name;

            return [
                'rang' => $index + 1,
                'user_id' => $row->user_id,
                'user_name' => $displayName,
                'total_ventes' => (int) $row->total,
            ];
        });
    }

    public function calculerPrimes(string $periode, ?int $agenceId = null): array
    {
        $classement = $this->getClassement($periode, $agenceId);
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
