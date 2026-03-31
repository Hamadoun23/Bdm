<?php

namespace Database\Seeders;

use App\Models\Agence;
use App\Models\Campagne;
use App\Models\Stock;
use App\Models\TypeCarte;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Vide les données applicatives et recrée : 2 agences, 1 admin, 3 commerciaux par agence (pas de chef d'agence).
 */
class FreshMinimalSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Vidage de la base (données métier + utilisateurs + agences)...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        if (Schema::hasTable('reclamations')) {
            DB::table('reclamations')->truncate();
        }
        if (Schema::hasTable('mouvements_stock')) {
            DB::table('mouvements_stock')->truncate();
        }
        if (Schema::hasTable('ventes')) {
            DB::table('ventes')->truncate();
        }
        if (Schema::hasTable('clients')) {
            DB::table('clients')->truncate();
        }
        if (Schema::hasTable('stocks')) {
            DB::table('stocks')->truncate();
        }
        if (Schema::hasTable('primes')) {
            DB::table('primes')->truncate();
        }
        if (Schema::hasTable('campagne_actions')) {
            DB::table('campagne_actions')->truncate();
        }
        if (Schema::hasTable('campagne_agence')) {
            DB::table('campagne_agence')->truncate();
        }
        if (Schema::hasTable('campagne_aide_beneficiaire')) {
            DB::table('campagne_aide_beneficiaire')->truncate();
        }
        if (Schema::hasTable('campagne_remise_type_carte')) {
            DB::table('campagne_remise_type_carte')->truncate();
        }
        if (Schema::hasTable('campagnes')) {
            DB::table('campagnes')->truncate();
        }
        if (Schema::hasTable('types_cartes')) {
            DB::table('types_cartes')->truncate();
        }
        if (Schema::hasTable('users')) {
            DB::table('users')->truncate();
        }
        if (Schema::hasTable('agences')) {
            DB::table('agences')->truncate();
        }
        if (Schema::hasTable('sessions')) {
            DB::table('sessions')->truncate();
        }
        if (Schema::hasTable('password_reset_tokens')) {
            DB::table('password_reset_tokens')->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        foreach ([
            ['code' => 'ADAN', 'prix' => 12000, 'actif' => true],
            ['code' => 'LAFIA', 'prix' => 18000, 'actif' => true],
            ['code' => 'ELITE', 'prix' => 35000, 'actif' => true],
        ] as $row) {
            TypeCarte::create($row);
        }

        $agenceDakar = Agence::create([
            'nom' => 'Agence Dakar',
            'adresse' => 'Dakar, Sénégal',
            'chef_id' => null,
        ]);
        $agenceThies = Agence::create([
            'nom' => 'Agence Thiès',
            'adresse' => 'Thiès, Sénégal',
            'chef_id' => null,
        ]);

        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@bdm.com',
            'password' => Hash::make('password'),
            'telephone' => '221770000001',
            'role' => 'admin',
            'agence_id' => null,
        ]);

        $commerciauxDakar = [
            ['name' => 'Sow', 'prenom' => 'Ibrahima', 'email' => 'dakar1@bdm.com'],
            ['name' => 'Ba', 'prenom' => 'Awa', 'email' => 'dakar2@bdm.com'],
            ['name' => 'Gueye', 'prenom' => 'Ousmane', 'email' => 'dakar3@bdm.com'],
        ];
        foreach ($commerciauxDakar as $c) {
            User::create([
                'name' => $c['name'],
                'prenom' => $c['prenom'],
                'email' => $c['email'],
                'password' => Hash::make('password'),
                'telephone' => '22177'.str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT),
                'role' => 'commercial',
                'agence_id' => $agenceDakar->id,
            ]);
        }

        $commerciauxThies = [
            ['name' => 'Faye', 'prenom' => 'Mariama', 'email' => 'thies1@bdm.com'],
            ['name' => 'Fall', 'prenom' => 'Amadou', 'email' => 'thies2@bdm.com'],
            ['name' => 'Ndiaye', 'prenom' => 'Fatou', 'email' => 'thies3@bdm.com'],
        ];
        foreach ($commerciauxThies as $c) {
            User::create([
                'name' => $c['name'],
                'prenom' => $c['prenom'],
                'email' => $c['email'],
                'password' => Hash::make('password'),
                'telephone' => '22177'.str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT),
                'role' => 'commercial',
                'agence_id' => $agenceThies->id,
            ]);
        }

        foreach ([$agenceDakar, $agenceThies] as $agence) {
            foreach (TypeCarte::orderBy('code')->get() as $tc) {
                Stock::create([
                    'agence_id' => $agence->id,
                    'type_carte_id' => $tc->id,
                    'quantite' => 200,
                ]);
            }
        }

        Campagne::create([
            'nom' => 'Campagne '.now()->format('Y'),
            'date_debut' => now()->startOfMonth(),
            'date_fin' => now()->endOfMonth()->addMonths(3),
            'prime_meilleur_vendeur' => 25000,
            'actif' => true,
            'statut' => Campagne::STATUT_EN_COURS,
            'toutes_agences' => true,
            'remise_tous_types_cartes' => true,
        ]);

        $this->command->info('OK : 2 agences, 1 admin, 6 commerciaux, 3 types de cartes, stocks à 200/unité/agence, 1 campagne.');
        $this->command->info('Connexion admin : admin@bdm.com / password');
        $this->command->info('Commerciaux Dakar : dakar1@bdm.com … dakar3@bdm.com / password');
        $this->command->info('Commerciaux Thiès : thies1@bdm.com … thies3@bdm.com / password');
        $this->command->info('Sans chef d\'agence : gérez les stocks via l\'admin (écran Stocks) ou ajoutez un rôle plus tard.');
    }
}
