<?php

namespace Database\Seeders;

use App\Models\Campagne;
use App\Models\Client;
use App\Models\Prime;
use App\Models\TypeCarte;
use App\Models\User;
use App\Models\Vente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Vide les données métier (ventes, campagnes, types de cartes…) et recharge un jeu neuf.
 * Les utilisateurs et les agences sont conservés.
 */
class ResetBusinessDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Réinitialisation des données métier (utilisateurs et agences conservés)...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        if (Schema::hasTable('reclamations')) {
            DB::table('reclamations')->truncate();
        }
        Vente::query()->delete();
        Client::query()->delete();
        Prime::query()->delete();
        DB::table('campagne_actions')->truncate();
        DB::table('campagne_agence')->truncate();
        Campagne::query()->delete();
        TypeCarte::query()->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        foreach ([
            ['code' => 'ADAN', 'actif' => true],
            ['code' => 'LAFIA', 'actif' => true],
            ['code' => 'ELITE', 'actif' => true],
        ] as $row) {
            TypeCarte::create($row);
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

        $commercialUsers = User::where('role', 'commercial')->get();
        $typeIds = TypeCarte::pluck('id')->all();

        $prenoms = ['Abdoul', 'Mamadou', 'Fatou', 'Awa', 'Ousmane', 'Mariama', 'Ibrahima', 'Aminata'];
        $noms = ['Sow', 'Diallo', 'Ndiaye', 'Ba', 'Gueye', 'Faye', 'Fall', 'Mbaye'];
        $villes = ['Dakar', 'Pikine', 'Thiès', 'Saint-Louis', 'Mbour'];
        $quartiers = ['Almadies', 'Plateau', 'Medina', 'Parcelles', 'Hann'];

        foreach (range(1, 60) as $i) {
            $commercial = $commercialUsers->random();
            $agenceId = $commercial->agence_id;
            if (! $agenceId) {
                continue;
            }
            $typeCarteId = collect($typeIds)->random();

            $statut = $i <= 4 ? 'en_erreur' : ($i <= 40 ? 'activée' : 'vendue');

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
                'created_at' => now()->subDays(rand(0, 45)),
            ]);
        }

        $periode = now()->format('Y-m');
        $tops = User::where('role', 'commercial')
            ->withCount('ventes')
            ->orderByDesc('ventes_count')
            ->take(2)
            ->get();
        $montants = [25000, 15000];
        foreach ($tops as $idx => $u) {
            Prime::firstOrCreate(
                ['user_id' => $u->id, 'periode' => $periode],
                ['montant' => $montants[$idx] ?? 15000, 'rang' => $idx + 1]
            );
        }

        $this->command->info('Terminé : 3 types de cartes, 1 campagne, ~60 ventes, primes du mois.');
    }
}
