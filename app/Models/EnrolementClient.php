<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrolementClient extends Model
{
    protected $fillable = ['campagne_id', 'user_id', 'agence_id', 'nom', 'prenom', 'telephone', 'adresse'];

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(Campagne::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }

    /** Délai depuis la création de l'enrôlement pour autoriser la suppression par le commercial (48 h). */
    public const DELAI_SUPPRESSION_COMMERCIAL_HEURES = 48;

    public function peutEtreModifieOuSupprimeParCommercial(): bool
    {
        return $this->created_at instanceof Carbon
            && $this->created_at->gt(Carbon::now()->subHours(self::DELAI_SUPPRESSION_COMMERCIAL_HEURES));
    }
}
