<?php

namespace Database\Seeders;

use App\Models\Agence;
use App\Models\Client;
use App\Models\Prime;
use App\Models\TypeCarte;
use App\Models\User;
use App\Models\Vente;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Alimentation des données fictives...');

        // Agences supplémentaires
        $agence2 = Agence::create([
            'nom' => 'Agence Pikine',
            'adresse' => 'Pikine, Dakar',
        ]);
        $agence3 = Agence::create([
            'nom' => 'Agence Thies',
            'adresse' => 'Thiès, Sénégal',
        ]);

        $commerciaux = [
            ['name' => 'Ibrahima Sow', 'email' => 'comm1@bdm.com', 'agence_id' => 1],
            ['name' => 'Awa Ba', 'email' => 'comm2@bdm.com', 'agence_id' => 1],
            ['name' => 'Ousmane Gueye', 'email' => 'comm3@bdm.com', 'agence_id' => $agence2->id],
            ['name' => 'Mariama Faye', 'email' => 'comm4@bdm.com', 'agence_id' => $agence2->id],
            ['name' => 'Amadou Fall', 'email' => 'comm5@bdm.com', 'agence_id' => $agence3->id],
        ];
        foreach ($commerciaux as $c) {
            User::create([
                'name' => $c['name'],
                'email' => $c['email'],
                'password' => bcrypt('password'),
                'telephone' => '22177'.rand(100000, 999999),
                'role' => 'commercial',
                'agence_id' => $c['agence_id'],
            ]);
        }
        $commercialUsers = User::where('role', 'commercial')->get();

        $prenoms = ['Abdoul', 'Mamadou', 'Fatou', 'Awa', 'Ousmane', 'Mariama', 'Ibrahima', 'Aminata', 'Seydou', 'Kadiatou'];
        $noms = ['Sow', 'Diallo', 'Ndiaye', 'Ba', 'Gueye', 'Faye', 'Fall', 'Mbaye', 'Thiam', 'Cisse'];
        $villes = ['Dakar', 'Pikine', 'Thiès', 'Saint-Louis', 'Ziguinchor', 'Mbour', 'Rufisque'];
        $quartiers = ['Almadies', 'Plateau', 'Medina', 'Grand Dakar', 'Parcelles', 'Hann', 'Ouakam'];

        $typeIds = TypeCarte::pluck('id')->all();

        // Créer 80 clients et ventes
        foreach (range(1, 80) as $i) {
            $commercial = $commercialUsers->random();
            $agenceId = $commercial->agence_id;
            $typeCarteId = collect($typeIds)->random();

            $statut = $i <= 5 ? 'en_erreur' : ($i <= 55 ? 'activée' : 'vendue');

            $client = Client::create([
                'prenom' => collect($prenoms)->random(),
                'nom' => collect($noms)->random(),
                'telephone' => '22177'.rand(1000000, 9999999),
                'ville' => collect($villes)->random(),
                'quartier' => collect($quartiers)->random(),
                'type_carte_id' => $typeCarteId,
                'statut_carte' => $statut,
                'user_id' => $commercial->id,
            ]);

            Vente::create([
                'client_id' => $client->id,
                'user_id' => $commercial->id,
                'agence_id' => $agenceId,
                'type_carte_id' => $typeCarteId,
                'statut_activation' => $statut,
                'created_at' => now()->subDays(rand(0, 60)),
            ]);
        }

        // Prime du meilleur commercial du mois (données fictives)
        $periode = now()->format('Y-m');
        $top = User::where('role', 'commercial')
            ->withCount('ventes')
            ->orderByDesc('ventes_count')
            ->first();
        if ($top) {
            Prime::firstOrCreate(
                ['user_id' => $top->id, 'periode' => $periode],
                ['montant' => 25000, 'rang' => 1]
            );
        }

        $this->command->info('Données fictives créées avec succès.');
    }
}
