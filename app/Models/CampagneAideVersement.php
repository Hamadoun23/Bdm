<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampagneAideVersement extends Model
{
    protected $table = 'campagne_aide_versements';

    protected $fillable = [
        'campagne_id', 'user_id', 'semaine_debut',
        'montant_carburant', 'montant_credit_tel',
        'enregistre_par', 'accuse_at', 'accuse_commentaire',
    ];

    protected function casts(): array
    {
        return [
            'semaine_debut' => 'date',
            'accuse_at' => 'datetime',
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

    public function enregistrePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }

    public function estAccuse(): bool
    {
        return $this->accuse_at !== null;
    }
}
