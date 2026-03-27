<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campagne extends Model
{
    public const STATUT_PROGRAMMEE = 'programmee';
    public const STATUT_EN_COURS = 'en_cours';
    public const STATUT_ARRETEE = 'arretee';
    public const STATUT_ANNULEE = 'annulee';
    public const STATUT_TERMINEE = 'terminee';

    protected $fillable = [
        'nom', 'date_debut', 'date_fin',
        'prime_top1', 'prime_top2', 'actif',
        'statut', 'toutes_agences',
        'remise_pourcentage',
        'remise_tous_types_cartes',
        'aide_hebdo_active', 'aide_hebdo_montant',
        'aide_hebdo_carburant', 'aide_hebdo_credit_tel',
        'aide_hebdo_tous_commerciaux',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'actif' => 'boolean',
            'toutes_agences' => 'boolean',
            'remise_pourcentage' => 'decimal:2',
            'remise_tous_types_cartes' => 'boolean',
            'aide_hebdo_active' => 'boolean',
            'aide_hebdo_tous_commerciaux' => 'boolean',
        ];
    }

    public function agences(): BelongsToMany
    {
        return $this->belongsToMany(Agence::class, 'campagne_agence');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(CampagneAction::class)->orderByDesc('created_at');
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }

    /** Vente autorisée pour une agence : campagne marquée active, non arrêtée / annulée, dans la fenêtre de dates, et concernant l’agence. */
    public function estOuverteAuxVentes(int $agenceId): bool
    {
        if (! $this->actif) {
            return false;
        }
        if (in_array($this->statut, [self::STATUT_ARRETEE, self::STATUT_ANNULEE], true)) {
            return false;
        }
        if (! $this->concerneAgence($agenceId)) {
            return false;
        }
        $now = Carbon::now()->startOfDay();
        if ($this->date_debut->gt($now) || $this->date_fin->lt($now)) {
            return false;
        }

        return $this->statut_effectif === self::STATUT_EN_COURS;
    }

    /** Commerciaux explicitement choisis pour l'aide hebdomadaire (si « pas tous »). */
    public function beneficiairesAide(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'campagne_aide_beneficiaire')->withTimestamps();
    }

    /** Types de carte concernés par la remise (si remise_tous_types_cartes est faux). */
    public function typesCartesRemise(): BelongsToMany
    {
        return $this->belongsToMany(TypeCarte::class, 'campagne_remise_type_carte')->withTimestamps();
    }

    /** La remise % s’applique-t-elle à ce type de carte ? */
    public function remiseSappliqueAuType(int $typeCarteId): bool
    {
        $p = (float) ($this->remise_pourcentage ?? 0);
        if ($p <= 0) {
            return false;
        }
        if ($this->remise_tous_types_cartes) {
            return true;
        }

        return $this->typesCartesRemise()->where('types_cartes.id', $typeCarteId)->exists();
    }

    /** Montant catalogue après remise campagne (%). */
    public function montantApresRemise(int $prixCatalogue): int
    {
        $p = (float) ($this->remise_pourcentage ?? 0);
        if ($p <= 0) {
            return $prixCatalogue;
        }

        return (int) max(0, round($prixCatalogue * (1 - min($p, 100) / 100)));
    }

    /** Le commercial reçoit-il l'aide hebdomadaire configurée sur cette campagne ? */
    public function commercialRecoitAideHebdo(User $user): bool
    {
        if (!$this->aide_hebdo_active || !$user->isCommercial() || !$user->agence_id || !$user->actif) {
            return false;
        }
        if (!$this->concerneAgence((int) $user->agence_id)) {
            return false;
        }
        if ($this->aide_hebdo_tous_commerciaux) {
            return true;
        }

        return $this->beneficiairesAide()->where('users.id', $user->id)->exists();
    }

    /** Statut effectif (calculé si statut non manuel) */
    public function getStatutEffectifAttribute(): string
    {
        if (in_array($this->statut, [self::STATUT_ARRETEE, self::STATUT_ANNULEE])) {
            return $this->statut;
        }
        $now = Carbon::now()->startOfDay();
        if ($this->date_fin->lt($now)) {
            return self::STATUT_TERMINEE;
        }
        if ($this->date_debut->lte($now)) {
            return self::STATUT_EN_COURS;
        }
        return self::STATUT_PROGRAMMEE;
    }

    /** La campagne est-elle active pour les primes (période + pas arrêtée/annulée) ? */
    public function estActivePourPrimes(?int $agenceId = null): bool
    {
        if (!in_array($this->statut_effectif, [self::STATUT_EN_COURS, self::STATUT_PROGRAMMEE])) {
            return false;
        }
        $now = Carbon::now()->startOfDay();
        if ($this->date_debut->gt($now) || $this->date_fin->lt($now)) {
            return false;
        }
        if ($agenceId !== null && !$this->toutes_agences) {
            return $this->agences()->where('agence_id', $agenceId)->exists();
        }
        return true;
    }

    public function concerneAgence(int $agenceId): bool
    {
        if ($this->toutes_agences) {
            return true;
        }
        return $this->agences()->where('agence_id', $agenceId)->exists();
    }

    /** Synchronise les statuts (programmee->en_cours, en_cours->terminee) et active automatiquement */
    public static function syncStatuts(): void
    {
        $now = Carbon::now()->startOfDay();
        // Marquer terminées
        Campagne::whereIn('statut', [self::STATUT_PROGRAMMEE, self::STATUT_EN_COURS])
            ->where('date_fin', '<', $now)
            ->update(['statut' => self::STATUT_TERMINEE, 'actif' => false]);
        // Activer la campagne en cours (une seule : la plus récente par date_debut)
        $campagneActivable = Campagne::whereIn('statut', [self::STATUT_PROGRAMMEE, self::STATUT_EN_COURS])
            ->where('date_debut', '<=', $now)
            ->where('date_fin', '>=', $now)
            ->orderByDesc('date_debut')
            ->first();
        if ($campagneActivable) {
            Campagne::where('actif', true)->where('id', '!=', $campagneActivable->id)->update(['actif' => false]);
            $campagneActivable->update(['statut' => self::STATUT_EN_COURS, 'actif' => true]);
        }
    }

    /** Campagne active pour les primes, pour une agence donnée (null = toutes) */
    public static function getActiveForAgence(?int $agenceId = null): ?Campagne
    {
        self::syncStatuts();
        $campagnes = Campagne::where('actif', true)->get();
        foreach ($campagnes as $c) {
            if ($agenceId === null || $c->concerneAgence($agenceId)) {
                return $c;
            }
        }
        return null;
    }
}
