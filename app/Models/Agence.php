<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agence extends Model
{
    protected $fillable = ['ordre', 'nom', 'adresse', 'chef_id'];

    protected function casts(): array
    {
        return [
            'ordre' => 'integer',
        ];
    }

    public function campagnes(): BelongsToMany
    {
        return $this->belongsToMany(Campagne::class, 'campagne_agence');
    }

    public function chef(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chef_id');
    }

    public function commerciaux(): HasMany
    {
        return $this->hasMany(User::class, 'agence_id')->where('role', 'commercial');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'agence_id');
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function mouvementsStock(): HasMany
    {
        return $this->hasMany(MouvementStock::class);
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }
}
