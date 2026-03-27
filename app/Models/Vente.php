<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vente extends Model
{
    protected $fillable = [
        'client_id', 'user_id', 'agence_id', 'campagne_id',
        'type_carte_id', 'montant', 'statut_activation'
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
}
