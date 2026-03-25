<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $fillable = ['type_carte_id', 'quantite', 'agence_id'];

    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }

    public function typeCarte(): BelongsTo
    {
        return $this->belongsTo(TypeCarte::class);
    }
}
