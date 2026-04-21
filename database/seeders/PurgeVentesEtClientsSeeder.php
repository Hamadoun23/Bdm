<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Prime;
use App\Models\Reclamation;
use App\Models\Vente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Supprime les données de test : ventes, clients, réclamations, primes.
 * Conserve : utilisateurs, agences, types de cartes, campagnes.
 *
 * php artisan db:seed --class=PurgeVentesEtClientsSeeder
 */
class PurgeVentesEtClientsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Suppression des ventes, clients, réclamations, primes…');

        DB::transaction(function () {
            Vente::query()->delete();
            Reclamation::query()->delete();
            Client::query()->delete();
            Prime::query()->delete();
        });

        $this->command->info('Terminé.');
    }
}
