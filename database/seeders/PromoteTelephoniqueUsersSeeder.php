<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Passe deux comptes commerciaux en commercial_telephonique et fixe leurs mots de passe.
 *
 * php artisan db:seed --class=PromoteTelephoniqueUsersSeeder
 */
class PromoteTelephoniqueUsersSeeder extends Seeder
{
    public function run(): void
    {
        $updates = [
            ['telephone' => '74353690', 'password' => 'N53K@bdm'], // Nènè KANOUTE
            ['telephone' => '78522819', 'password' => 'D29K@bdm'], // Diahara KANSAYE
        ];

        foreach ($updates as $row) {
            $user = User::query()->where('telephone', $row['telephone'])->first();
            if (! $user) {
                $this->command?->warn("Aucun utilisateur avec le téléphone {$row['telephone']}.");

                continue;
            }
            $user->role = 'commercial_telephonique';
            $user->password = Hash::make($row['password']);
            $user->save();
            $this->command?->info("Mis à jour : #{$user->id} {$user->prenom} {$user->name} ({$user->telephone}) → commercial_telephonique");
        }
    }
}
