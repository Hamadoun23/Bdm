<?php

namespace App\Services;

use App\Models\Agence;
use App\Models\User;
use App\Models\Vente;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TransfertVentesAgenceService
{
    /**
     * Réattribue des ventes d’un commercial vers une autre agence (champ `ventes.agence_id` uniquement).
     *
     * @param  list<int>  $venteIds
     * @return array{count: int, snapshots: list<array{vente_id: int, agence_avant: int, agence_apres: int}>}
     */
    public function reattribuerVentes(
        User $commercial,
        array $venteIds,
        int $nouvelleAgenceId,
        User $admin,
    ): array {
        if (! $commercial->isCommercial() && ! $commercial->isCommercialTelephonique()) {
            throw new InvalidArgumentException('Seuls les profils commercial / commercial téléphonique peuvent faire l’objet d’une réattribution de ventes.');
        }

        if (! $admin->isAdmin()) {
            throw new InvalidArgumentException('Seul un administrateur peut réattribuer des ventes.');
        }

        $venteIds = array_values(array_unique(array_map('intval', $venteIds)));
        if ($venteIds === []) {
            throw new InvalidArgumentException('Sélectionnez au moins une vente.');
        }

        if (! Agence::query()->whereKey($nouvelleAgenceId)->exists()) {
            throw new InvalidArgumentException('Agence cible invalide.');
        }

        return DB::transaction(function () use ($commercial, $venteIds, $nouvelleAgenceId): array {
            $ventes = Vente::query()
                ->whereIn('id', $venteIds)
                ->where('user_id', $commercial->id)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($ventes->count() !== count($venteIds)) {
                throw new InvalidArgumentException('Certaines ventes sont introuvables ou n’appartiennent pas à ce commercial.');
            }

            $snapshots = [];

            foreach ($ventes as $vente) {
                $ancienneAgenceId = (int) $vente->agence_id;

                if ($ancienneAgenceId === $nouvelleAgenceId) {
                    continue;
                }

                $vente->update(['agence_id' => $nouvelleAgenceId]);

                $snapshots[] = [
                    'vente_id' => (int) $vente->id,
                    'agence_avant' => $ancienneAgenceId,
                    'agence_apres' => $nouvelleAgenceId,
                ];
            }

            return [
                'count' => count($snapshots),
                'snapshots' => $snapshots,
            ];
        });
    }
}
