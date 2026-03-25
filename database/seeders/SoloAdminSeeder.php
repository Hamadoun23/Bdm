<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Vide la base applicative et ne conserve qu’un compte administrateur.
 */
class SoloAdminSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Vidage complet — un seul compte administrateur sera recréé (téléphone 83757033).');

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

        User::create([
            'name' => 'Administrateur',
            'prenom' => null,
            'email' => null,
            'password' => Hash::make('BDM@23m'),
            'telephone' => '83757033',
            'role' => 'admin',
            'agence_id' => null,
            'actif' => true,
        ]);

        $this->command->info('Terminé : 1 admin — identifiant : 83757033 (téléphone, champ « Email » sur la page de connexion) / mot de passe : BDM@23m');
    }
}
