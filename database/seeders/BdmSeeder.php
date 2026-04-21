<?php

namespace Database\Seeders;

use App\Models\Agence;
use App\Models\Campagne;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BdmSeeder extends Seeder
{
    public function run(): void
    {
        $agence = Agence::create([
            'nom' => 'Agence Principale',
            'adresse' => 'Dakar, Sénégal',
            'chef_id' => null,
        ]);

        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@bdm.com',
            'password' => Hash::make('password'),
            'telephone' => '221771234567',
            'role' => 'admin',
            'agence_id' => null,
        ]);

        User::create([
            'name' => 'Commercial Test',
            'email' => 'commercial@bdm.com',
            'password' => Hash::make('password'),
            'telephone' => '221771234569',
            'role' => 'commercial',
            'agence_id' => $agence->id,
        ]);

        Campagne::create([
            'nom' => 'Campagne 2025',
            'date_debut' => now()->startOfMonth(),
            'date_fin' => now()->endOfMonth()->addMonths(3),
            'prime_meilleur_vendeur' => 25000,
            'actif' => true,
            'statut' => Campagne::STATUT_EN_COURS,
            'toutes_agences' => true,
            'remise_tous_types_cartes' => true,
        ]);
    }
}
