<?php

namespace App\Services;

use App\Models\Campagne;
use Illuminate\Database\Eloquent\Builder;

class CampagneStatsScope
{
    /** @return list<int> */
    public static function idsPour(?int $agenceId = null): array
    {
        return Campagne::idsCampagnesPourStats($agenceId);
    }

    public static function appliquerSurVentes(Builder $query, ?int $agenceId = null): Builder
    {
        $ids = self::idsPour($agenceId);
        if ($ids === []) {
            return $query->whereRaw('0 = 1');
        }

        return $query->whereIn('campagne_id', $ids);
    }

    public static function appliquerSurTelephonique(Builder $query, ?int $agenceId = null): Builder
    {
        $ids = self::idsPour($agenceId);
        if ($ids === []) {
            return $query->whereRaw('0 = 1');
        }

        return $query->whereIn('campagne_id', $ids);
    }

    /** @return array{debut: \Carbon\Carbon, fin: \Carbon\Carbon}|null */
    public static function fenetreDates(?int $agenceId = null): ?array
    {
        return Campagne::fenetreDatesPourStats($agenceId);
    }

    public static function libelle(?int $agenceId = null): string
    {
        return Campagne::libelleCampagnesPourStats($agenceId);
    }
}
