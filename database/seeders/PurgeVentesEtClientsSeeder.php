<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\MouvementStock;
use App\Models\Prime;
use App\Models\Reclamation;
use App\Models\Stock;
use App\Models\Vente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Supprime les données de test : ventes, clients, mouvements de stock, réclamations, primes.
 * Conserve : utilisateurs, agences, types de cartes, campagnes, lignes de stock (quantités réinitialisées).
 *
 * php artisan db:seed --class=PurgeVentesEtClientsSeeder
 */
class PurgeVentesEtClientsSeeder extends Seeder
{
    /** Nombre de cartes par agence/type après purge (stocks à plat). */
    private int $quantiteStockApresPurge = 100;

    public function run(): void
    {
        $this->command->info('Suppression des ventes, clients, mouvements, réclamations, primes…');

        DB::transaction(function () {
            MouvementStock::query()->delete();
            Vente::query()->delete();
            Reclamation::query()->delete();
            Client::query()->delete();
            Prime::query()->delete();

            Stock::query()->update(['quantite' => $this->quantiteStockApresPurge]);
        });

        $this->command->info('Terminé. Stocks fixés à '.$this->quantiteStockApresPurge.' unités par ligne (agence × type de carte).');
    }
}
