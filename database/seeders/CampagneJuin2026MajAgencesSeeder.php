<?php

namespace Database\Seeders;

use App\Models\Agence;
use App\Models\Campagne;
use App\Models\CommercialAgenceTransfert;
use App\Models\User;
use App\Models\Vente;
use Illuminate\Database\Seeder;

/**
 * Campagne Juin 2026 — mise à jour agence profil uniquement.
 * Les ventes déjà enregistrées conservent leur ventes.agence_id (historique figé).
 *
 * php artisan db:seed --class=CampagneJuin2026MajAgencesSeeder
 */
class CampagneJuin2026MajAgencesSeeder extends Seeder
{
    private const NOM_CAMPAGNE = 'Juin 2026';

    /** @var list<array{tel: string, agence: string, tel_ancien?: string}> */
    private const NOUVELLES_AFFECTATIONS = [
        ['tel' => '66986621', 'agence' => 'BS'],
        ['tel' => '74353690', 'agence' => 'API'],
        ['tel' => '70179839', 'agence' => 'SEBENIKORO'],
        ['tel' => '76612042', 'agence' => 'HAMDALLAYE'],
        ['tel' => '83140127', 'agence' => 'BAGADADJI'],
        ['tel' => '76411856', 'agence' => 'MAGNAMBOUGOU'],
        ['tel' => '78522819', 'agence' => 'MISSIRA'],
        ['tel' => '71700505', 'agence' => 'DJICORONI-PARA'],
        ['tel' => '69098738', 'agence' => 'DIBIDANI'],
        ['tel' => '66904040', 'agence' => 'FUTURA'],
        ['tel' => '79787541', 'agence' => 'BOULKASSOUMBOUGOU'],
        ['tel' => '74082712', 'agence' => 'SOGONIKO'],
        ['tel' => '71010050', 'agence' => "N'GOLONINA"],
        ['tel' => '78754962', 'agence' => 'SEMA GESCO'],
        ['tel' => '90889198', 'agence' => 'YIRIMADIO'],
        ['tel' => '70277320', 'agence' => 'KALABAN-COURA'],
        ['tel' => '76636578', 'agence' => 'AZAR CENTER'],
        ['tel' => '79053641', 'agence' => 'KOROFINA'],
        ['tel' => '72189105', 'agence' => 'MORIBABOUGOU', 'tel_ancien' => '72789105'],
    ];

    private const AGENCE_ALIASES = [
        'api' => 'AP 1',
        'ap 1' => 'AP 1',
        'pme / pmi' => 'PME/ PMI',
        'pme pmi' => 'PME/ PMI',
        'niamana' => 'Niamana',
        'kayes 1' => 'Kayes 1',
        'sebenikoro' => 'SEBENIKORO',
        'sogoniko' => 'Sogoniko',
        'n golonina' => "N'Golonina",
        'ngolonina' => "N'Golonina",
        'kalaban coura' => 'Kalaban coura',
        'kalaban-coura' => 'Kalaban coura',
        'yirimadio' => 'Yirimadio',
        'futura' => 'Futura',
        'hamdallaye' => 'HAMDALLAYE',
        'san' => 'San',
        'segou 2' => 'Ségou 2',
        'koulikoro' => 'Koulikoro',
        'korofina' => 'Korofina',
        'baco djicoroni' => 'Baco Djicoroni',
        'bs' => 'BS',
        'missira' => 'MISSIRA',
        'magnambougou' => 'MAGNAMBOUGOU',
        'djicoroni-para' => 'DJICORONI-PARA',
        'djicoroni para' => 'DJICORONI-PARA',
        'azar center' => 'AZAR CENTER',
        'boulkassoumbougou' => 'BOULKASSOUMBOUGOU',
        'moribabougou' => 'MORIBABOUGOU',
        'sema gesco' => 'SEMA GESCO',
    ];

    public function run(): void
    {
        $campagne = Campagne::query()->where('nom', self::NOM_CAMPAGNE)->first();
        if (! $campagne) {
            $this->command->error('Campagne « '.self::NOM_CAMPAGNE.' » introuvable.');

            return;
        }

        $admin = User::query()->where('role', 'admin')->orderBy('id')->first();
        if (! $admin) {
            $this->command->error('Aucun administrateur trouvé pour le journal de transfert.');

            return;
        }

        $maj = 0;
        $ignore = 0;

        foreach (self::NOUVELLES_AFFECTATIONS as $row) {
            $user = $this->findCommercial($row['tel'], $row['tel_ancien'] ?? null);
            if (! $user) {
                $this->command->warn('Commercial introuvable : '.$row['tel']);

                continue;
            }

            $nouvelleAgence = $this->findOrCreateAgence($row['agence']);
            $ancienneAgenceId = $user->agence_id ? (int) $user->agence_id : null;

            if ($ancienneAgenceId === (int) $nouvelleAgence->id) {
                $ignore++;
                $this->command->line('  = '.$user->telephone.' '.$user->name.' — déjà sur '.$nouvelleAgence->nom);

                continue;
            }

            $ancienNom = $ancienneAgenceId
                ? (Agence::find($ancienneAgenceId)?->nom ?? '?')
                : '—';

            $user->update(['agence_id' => $nouvelleAgence->id]);

            if (! empty($row['tel_ancien']) && $this->normalizePhone($row['tel']) !== $user->telephone) {
                $user->update([
                    'telephone' => $this->normalizePhone($row['tel']),
                    'email' => 'juin2026.'.$this->normalizePhone($row['tel']).'@import.gda',
                ]);
            }

            CommercialAgenceTransfert::query()->create([
                'commercial_user_id' => $user->id,
                'admin_user_id' => $admin->id,
                'nouvelle_agence_id' => $nouvelleAgence->id,
                'snapshots' => [],
                'profil_agence_avant' => $ancienneAgenceId,
                'profil_agence_apres' => $nouvelleAgence->id,
                'note' => 'Campagne Juin 2026 — profil uniquement, ventes historiques inchangées.',
            ]);

            $ventesHistoriques = Vente::query()
                ->where('user_id', $user->id)
                ->where('campagne_id', $campagne->id)
                ->count();

            $maj++;
            $this->command->info(sprintf(
                '  ✓ %s %s — %s → %s (%d vente(s) Juin conservée(s) sur agence d’origine)',
                $user->telephone,
                trim($user->name.' '.$user->prenom),
                $ancienNom,
                $nouvelleAgence->nom,
                $ventesHistoriques
            ));
        }

        $agenceIds = array_unique(array_merge(
            $campagne->agences()->pluck('agences.id')->all(),
            $campagne->signatairesContrat()->pluck('agence_id')->filter()->all(),
            Vente::query()->where('campagne_id', $campagne->id)->distinct()->pluck('agence_id')->all(),
        ));
        $campagne->agences()->sync(array_values(array_filter($agenceIds)));

        $this->command->info('');
        $this->command->info("Terminé : {$maj} profil(s) mis à jour, {$ignore} déjà à jour.");
        $this->command->info('Campagne « '.self::NOM_CAMPAGNE.' » : '.count($agenceIds).' agence(s) liées (anciennes + nouvelles).');
        $this->command->info('Les nouvelles ventes utiliseront l’agence du profil ; l’historique reste sur ventes.agence_id.');
    }

    private function findCommercial(string $tel, ?string $telAncien): ?User
    {
        $telephone = $this->normalizePhone($tel);

        $user = User::query()->where('telephone', $telephone)->first();
        if ($user) {
            return $user;
        }

        if ($telAncien) {
            return User::query()->where('telephone', $this->normalizePhone($telAncien))->first();
        }

        return User::query()
            ->where('email', 'juin2026.'.$telephone.'@import.gda')
            ->first();
    }

    private function findOrCreateAgence(string $nom): Agence
    {
        $n = trim($nom);
        $key = $this->normalizeAgenceKey($n);
        $canonique = self::AGENCE_ALIASES[$key] ?? $n;

        $existing = Agence::query()
            ->whereRaw('LOWER(TRIM(nom)) = ?', [mb_strtolower($canonique)])
            ->first();

        if (! $existing) {
            $existing = Agence::query()
                ->whereRaw('LOWER(TRIM(REPLACE(REPLACE(nom, "-", " "), "/", " "))) = ?', [
                    $this->normalizeAgenceKey($canonique),
                ])
                ->first();
        }

        if ($existing) {
            return $existing;
        }

        $ordre = (int) (Agence::query()->max('ordre') ?? 0) + 1;

        return Agence::create(['nom' => $n, 'ordre' => $ordre]);
    }

    private function normalizeAgenceKey(string $nom): string
    {
        $s = mb_strtolower(trim(str_replace(['-', '/'], ' ', $nom)));
        $s = preg_replace('/\s+/', ' ', $s) ?? $s;

        return str_replace("'", '', $s);
    }

    private function normalizePhone(string $raw): string
    {
        return preg_replace('/\D+/', '', $raw) ?? '';
    }
}
