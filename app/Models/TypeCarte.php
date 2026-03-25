<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeCarte extends Model
{
    protected $table = 'types_cartes';

    protected $fillable = ['code', 'prix', 'actif'];

    protected function casts(): array
    {
        return [
            'prix' => 'integer',
            'actif' => 'boolean',
        ];
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }

    public function mouvementsStock(): HasMany
    {
        return $this->hasMany(MouvementStock::class, 'type_carte_id');
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true)->orderBy('code');
    }

    public function peutEtreSupprime(): bool
    {
        return $this->ventes()->count() === 0
            && $this->mouvementsStock()->count() === 0
            && $this->clients()->count() === 0;
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'type_carte_id');
    }
}
