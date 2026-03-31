<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Remet toutes les quantités de stock à 0 et assure un compte Direction (vue globale multi-agences).
 * Exécution : php artisan db:seed --class=ResetStocksDirectionSeeder
 */
class ResetStocksDirectionSeeder extends Seeder
{
    public function run(): void
    {
        Stock::query()->update(['quantite' => 0]);

        User::updateOrCreate(
            ['telephone' => '22300000999'],
            [
                'name' => 'Direction',
                'prenom' => 'Générale',
                'email' => 'direction@bdm.local',
                'password' => 'Direction@bdm8',
                'role' => 'direction',
                'agence_id' => null,
                'actif' => true,
            ]
        );

        $this->command->info('Stocks : quantités mises à 0 pour toutes les lignes.');
        $this->command->info('Direction : téléphone 22300000999 ou identifiant « Direction » — mot de passe Direction@bdm8');
    }
}
