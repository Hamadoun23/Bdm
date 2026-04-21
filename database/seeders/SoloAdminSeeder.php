<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Vide la base applicative et recrée uniquement une équipe d’administrateurs.
 * Connexion admin : saisir le nom (ex. Sylla) comme identifiant — pas d’e-mail ni de téléphone.
 */
class SoloAdminSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Vidage complet — recréation des comptes administrateurs (login par nom).');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        if (Schema::hasTable('reclamations')) {
            DB::table('reclamations')->truncate();
        }
        if (Schema::hasTable('ventes')) {
            DB::table('ventes')->truncate();
        }
        if (Schema::hasTable('clients')) {
            DB::table('clients')->truncate();
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

        /** @var list<array{name: string, password: string}> */
        $admins = [
            ['name' => 'Sylla', 'password' => 'Sylla@bdm99'],
            ['name' => 'Dante', 'password' => 'Ami26@bmd'],
            ['name' => 'Koita', 'password' => 'Koita27@bmd'],
            ['name' => 'Sacko', 'password' => 'Bdm47@youba'],
            ['name' => 'Cisse', 'password' => '23m@bdm'],
            ['name' => 'Yaya', 'password' => 'bdm@26yaya'],
        ];

        foreach ($admins as $row) {
            User::create([
                'name' => $row['name'],
                'prenom' => null,
                'email' => null,
                'password' => Hash::make($row['password']),
                'telephone' => null,
                'role' => 'admin',
                'agence_id' => null,
                'actif' => true,
            ]);
        }

        $this->command->info('Terminé : '.count($admins).' administrateurs.');
        foreach ($admins as $row) {
            $this->command->line('  • '.$row['name'].' — connexion avec le nom : '.$row['name']);
        }
    }
}
