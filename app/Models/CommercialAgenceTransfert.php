<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommercialAgenceTransfert extends Model
{
    protected $fillable = [
        'commercial_user_id',
        'admin_user_id',
        'nouvelle_agence_id',
        'snapshots',
        'profil_agence_avant',
        'profil_agence_apres',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'snapshots' => 'array',
        ];
    }

    public function commercial(): BelongsTo
    {
        return $this->belongsTo(User::class, 'commercial_user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function nouvelleAgence(): BelongsTo
    {
        return $this->belongsTo(Agence::class, 'nouvelle_agence_id');
    }
}
