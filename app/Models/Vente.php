<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Vente extends Model
{
    /** Délai après enregistrement pendant lequel le commercial peut supprimer la vente. */
    public const DELAI_SUPPRESSION_COMMERCIAL_HEURES = 48;

    protected $fillable = [
        'client_id', 'user_id', 'agence_id', 'campagne_id',
        'type_carte_id', 'montant', 'statut_activation',
    ];

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(Campagne::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }

    public function typeCarte(): BelongsTo
    {
        return $this->belongsTo(TypeCarte::class);
    }

    public function mouvementStock(): HasOne
    {
        return $this->hasOne(MouvementStock::class);
    }

    public function peutEtreSupprimeeParCommercial(): bool
    {
        return $this->created_at instanceof Carbon
            && $this->created_at->gt(now()->subHours(self::DELAI_SUPPRESSION_COMMERCIAL_HEURES));
    }
}
