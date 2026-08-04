<?php

namespace App\Services;

use App\Models\Campagne;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CampagneStatsScope
{
    /**
     * Campagnes de référence pour les stats, éventuellement restreintes à un type.
     *
     * @return Collection<int, Campagne>
     */
    public static function campagnesPour(?int $agenceId = null, ?string $type = null): Collection
    {
        $campagnes = Campagne::getCampagnesPourStats($agenceId);

        return $type === null ? $campagnes : $campagnes->where('type', $type)->values();
    }

    /** @return list<int> */
    public static function idsPour(?int $agenceId = null, ?string $type = null): array
    {
        return self::campagnesPour($agenceId, $type)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
    }

    private static function limiterAuxCampagnes(Builder $query, array $ids): Builder
    {
        if ($ids === []) {
            return $query->whereRaw('0 = 1');
        }

        return $query->whereIn('campagne_id', $ids);
    }

    /**
     * Une requête sur `ventes` ne doit jamais être bornée à une campagne d'enrôlement : elle
     * renverrait un ensemble vide tout en affichant le nom de cette campagne comme périmètre.
     */
    public static function appliquerSurVentes(Builder $query, ?int $agenceId = null): Builder
    {
        return self::limiterAuxCampagnes($query, self::idsPour($agenceId, Campagne::TYPE_VENTE_CARTE));
    }

    /** Le reporting téléphonique est adossé aux campagnes de vente (pas d'équivalent enrôlement). */
    public static function appliquerSurTelephonique(Builder $query, ?int $agenceId = null): Builder
    {
        return self::limiterAuxCampagnes($query, self::idsPour($agenceId, Campagne::TYPE_VENTE_CARTE));
    }

    /** @return array{debut: Carbon, fin: Carbon}|null */
    public static function fenetreDates(?int $agenceId = null): ?array
    {
        return Campagne::fenetreDatesPourStats($agenceId);
    }

    public static function libelle(?int $agenceId = null, ?string $type = null): string
    {
        $campagnes = self::campagnesPour($agenceId, $type);
        if ($campagnes->isEmpty()) {
            return 'Aucune campagne';
        }

        return $campagnes->map(fn (Campagne $c) => '« '.$c->nom.' »')->join(', ', ' et ');
    }
}
