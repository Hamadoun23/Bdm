<?php

namespace Database\Seeders;

use App\Models\Agence;
use App\Models\Campagne;
use App\Models\Stock;
use App\Models\TypeCarte;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BdmSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'agence
        $agence = Agence::create([
            'nom' => 'Agence Principale',
            'adresse' => 'Dakar, Sénégal',
        ]);

        // Créer l'admin (sans agence)
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@bdm.com',
            'password' => Hash::make('password'),
            'telephone' => '221771234567',
            'role' => 'admin',
            'agence_id' => null,
        ]);

        // Créer le chef d'agence
        $chef = User::create([
            'name' => 'Chef Agence',
            'email' => 'chef@bdm.com',
            'password' => Hash::make('password'),
            'telephone' => '221771234568',
            'role' => 'chef_agence',
            'agence_id' => $agence->id,
        ]);

        // Lier le chef à l'agence
        $agence->update(['chef_id' => $chef->id]);

        // Créer un commercial
        User::create([
            'name' => 'Commercial Test',
            'email' => 'commercial@bdm.com',
            'password' => Hash::make('password'),
            'telephone' => '221771234569',
            'role' => 'commercial',
            'agence_id' => $agence->id,
        ]);

        // Créer les stocks initiaux pour l'agence
        foreach (TypeCarte::orderBy('code')->get() as $tc) {
            Stock::create([
                'type_carte_id' => $tc->id,
                'quantite' => 100,
                'agence_id' => $agence->id,
            ]);
        }

        // Créer une campagne (toutes agences, active auto à partir de date_debut)
        Campagne::create([
            'nom' => 'Campagne 2025',
            'date_debut' => now()->startOfMonth(),
            'date_fin' => now()->endOfMonth()->addMonths(3),
            'prime_top1' => 25000,
            'prime_top2' => 15000,
            'actif' => true,
            'statut' => Campagne::STATUT_EN_COURS,
            'toutes_agences' => true,
            'remise_tous_types_cartes' => true,
        ]);
    }
}
