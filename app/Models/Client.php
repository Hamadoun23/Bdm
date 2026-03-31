<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'prenom', 'nom', 'telephone', 'ville', 'quartier',
        'type_carte_id', 'statut_carte', 'carte_identite', 'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function typeCarte(): BelongsTo
    {
        return $this->belongsTo(TypeCarte::class);
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }

    public function reclamations(): HasMany
    {
        return $this->hasMany(Reclamation::class);
    }

    /** Délais depuis la création du client pour autoriser la suppression par le commercial. */
    public const DELAI_SUPPRESSION_COMMERCIAL_HEURES = 48;

    public function peutEtreSupprimeParCommercial(): bool
    {
        return $this->created_at->gt(Carbon::now()->subHours(self::DELAI_SUPPRESSION_COMMERCIAL_HEURES));
    }
}
