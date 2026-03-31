<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContratPrestationReponse extends Model
{
    public const STATUT_EN_ATTENTE = 'en_attente';

    public const STATUT_ACCEPTE = 'accepte';

    public const STATUT_REJETE = 'rejete';

    protected $table = 'contrat_prestation_reponses';

    protected $fillable = [
        'campagne_id', 'user_id', 'statut', 'repondu_at',
    ];

    protected function casts(): array
    {
        return [
            'repondu_at' => 'datetime',
        ];
    }

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(Campagne::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
