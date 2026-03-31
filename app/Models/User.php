<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'prenom',
        'email',
        'password',
        'telephone',
        'role',
        'agence_id',
        'actif',
        'adresse_contrat',
        'piece_identite_ref',
    ];

    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function reclamations(): HasMany
    {
        return $this->hasMany(Reclamation::class);
    }

    public function primes(): HasMany
    {
        return $this->hasMany(Prime::class);
    }

    public function campagnesSignataireContrat(): BelongsToMany
    {
        return $this->belongsToMany(Campagne::class, 'campagne_commercial_contrat')->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCommercial(): bool
    {
        return $this->role === 'commercial';
    }

    /** @deprecated Ancien rôle chef d’agence (retiré ; migré en commercial en base). */
    public function isChefAgence(): bool
    {
        return false;
    }

    public function isDirection(): bool
    {
        return $this->role === 'direction';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }
}
