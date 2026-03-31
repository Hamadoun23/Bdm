<?php

namespace Database\Seeders;

use App\Models\Agence;
use Illuminate\Database\Seeder;

/**
 * Crée les agences GDA (réf. Info.md) sans chef d’agence (chef_id = null), sans adresse.
 * Idempotent : firstOrCreate par nom.
 *
 * Exécution : php artisan db:seed --class=AgencesGdaSeeder
 */
class AgencesGdaSeeder extends Seeder
{
    public function run(): void
    {
        $nomsBamako = [
            'Niamana',
            'PME/PMI',
            "Centre d'appel — Sotuba (logements sociaux)",
            'Sotuba',
            'Sogoniko',
            'Korofina',
            'Baco Djicoroni',
            'Dibida',
            'AP 2',
            "N'Golonina",
            'Kalaban coura',
            'Maison du Hadj',
            "Centre d'appel — ACI BOCOUM",
            'Yirimadio',
            'Futura',
            'Djicoroni para',
            'Dramane DIAKITE',
            'Kabala',
            'Kati',
            'AP 1',
        ];

        $nomsInterieur = [
            'Ségou 2',
            'Ségou 1',
            'San',
            'Mopti',
            'Koulikoro',
            'Dioila',
            'Sikasso',
            'Tombouctou',
            'Kita',
            'Kayes 1',
        ];

        foreach (array_merge($nomsBamako, $nomsInterieur) as $nom) {
            Agence::firstOrCreate(
                ['nom' => $nom],
                ['adresse' => null, 'chef_id' => null]
            );
        }
    }
}
