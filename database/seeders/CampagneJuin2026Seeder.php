<?php

namespace Database\Seeders;

use App\Models\Agence;
use App\Models\Campagne;
use App\Models\CampagneContratArticle;
use App\Models\ContratPrestationReponse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Campagne « Juin 2026 » : 15 → 17 juin 2026,
 * périmètre restreint aux agences et commerciaux listés.
 *
 * Mot de passe par commercial : M{2 derniers chiffres du n°}T@bdm
 *
 * php artisan db:seed --class=CampagneJuin2026Seeder
 */
class CampagneJuin2026Seeder extends Seeder
{
    private const NOM_CAMPAGNE = 'Juin 2026';

    /**
     * Liste campagne Juin 2026 — agences mises à jour (juillet 2026).
     *
     * @var list<array{noms: string, prenom: string, agence: string, tel: string, tel_ancien?: string}>
     */
    private const COMMERCIAUX = [
        ['noms' => 'SIDIBE', 'prenom' => 'Djelika KEITA', 'agence' => 'LAFIABOUGOU', 'tel' => '72715555'],
        ['noms' => 'DIARRE', 'prenom' => 'Assetou Yalcoye', 'agence' => 'BS', 'tel' => '66986621'],
        ['noms' => 'KANOUTE', 'prenom' => 'Nènè', 'agence' => 'API', 'tel' => '74353690'],
        ['noms' => 'COULIBALY', 'prenom' => 'Awa', 'agence' => 'PME / PMI', 'tel' => '79790604'],
        ['noms' => 'KAMATE', 'prenom' => 'Sitan', 'agence' => 'SAN', 'tel' => '90464123'],
        ['noms' => 'COULIBALY', 'prenom' => 'Aminata', 'agence' => 'NIAMANA', 'tel' => '71766277'],
        ['noms' => 'SISSOKO', 'prenom' => 'Djeneba', 'agence' => 'KAYES 1', 'tel' => '69418521'],
        ['noms' => 'CAMARA', 'prenom' => 'Ali Badara', 'agence' => 'AP2', 'tel' => '73907530'],
        ['noms' => 'KONATE', 'prenom' => 'Maimouna', 'agence' => 'SEBENIKORO', 'tel' => '70179839'],
        ['noms' => 'FOFANA', 'prenom' => 'Kadiatou', 'agence' => 'HAMDALLAYE', 'tel' => '76612042'],
        ['noms' => 'TOGORA', 'prenom' => 'Lassina', 'agence' => 'BAGADADJI', 'tel' => '83140127'],
        ['noms' => 'COULIBALY', 'prenom' => 'Mamadou', 'agence' => 'MAGNAMBOUGOU', 'tel' => '76411856'],
        ['noms' => 'KANSAYE', 'prenom' => 'Diahara', 'agence' => 'MISSIRA', 'tel' => '78522819'],
        ['noms' => 'TANGARA', 'prenom' => 'AMINATA', 'agence' => 'DJICORONI-PARA', 'tel' => '71700505'],
        ['noms' => 'THIAM', 'prenom' => 'Fatoumata', 'agence' => 'BACO DJICORONI', 'tel' => '92274352'],
        ['noms' => 'TOURE', 'prenom' => 'Mary N', 'agence' => 'DIBIDANI', 'tel' => '69098738'],
        ['noms' => 'THIAM', 'prenom' => 'Mohamed Aly', 'agence' => 'SEGOU 2', 'tel' => '70442854'],
        ['noms' => 'NIAMBLE', 'prenom' => 'Aissata N', 'agence' => 'FUTURA', 'tel' => '66904040'],
        ['noms' => 'GAKOU', 'prenom' => 'Oumar', 'agence' => 'BOULKASSOUMBOUGOU', 'tel' => '79787541'],
        ['noms' => 'BATHILY', 'prenom' => 'Maimounata', 'agence' => 'KATI', 'tel' => '65893863'],
        ['noms' => 'DEMBELE', 'prenom' => 'Salimata', 'agence' => 'MORIBABOUGOU', 'tel' => '72189105', 'tel_ancien' => '72789105'],
        ['noms' => 'THERA', 'prenom' => 'Mariam', 'agence' => 'SOGONIKO', 'tel' => '74082712'],
        ['noms' => 'SANOGO', 'prenom' => 'FATOUMATA', 'agence' => "N'GOLONINA", 'tel' => '71010050'],
        ['noms' => 'DEMBELE', 'prenom' => 'Karidiata', 'agence' => 'SIKASSO 1', 'tel' => '60625221'],
        ['noms' => 'SANGARE', 'prenom' => 'Fatimata', 'agence' => 'SEMA GESCO', 'tel' => '78754962'],
        ['noms' => 'MAIGA', 'prenom' => 'Adiaratou A', 'agence' => 'YIRIMADIO', 'tel' => '90889198'],
        ['noms' => 'TRAORE', 'prenom' => 'Adama', 'agence' => 'KALABAN-COURA', 'tel' => '70277320'],
        ['noms' => 'MAIGA', 'prenom' => 'Fatoumata', 'agence' => 'AZAR CENTER', 'tel' => '76636578'],
        ['noms' => 'DIAKITE', 'prenom' => 'Nagnouma TOURE', 'agence' => 'KOROFINA', 'tel' => '79053641'],
        ['noms' => 'SANOGO', 'prenom' => 'Fatoumata', 'agence' => 'KOULIKORO', 'tel' => '92330460'],
    ];

    /** Synonymes agence → nom canonique déjà en base. */
    private const AGENCE_ALIASES = [
        'api' => 'AP 1',
        'ap 1' => 'AP 1',
        'ap1' => 'AP 1',
        'pme / pmi' => 'PME/ PMI',
        'pme/pmi' => 'PME/ PMI',
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
    ];

    public function run(): void
    {
        $dateDebut = Carbon::parse('2026-06-15')->startOfDay();
        $dateFin = Carbon::parse('2026-06-17')->endOfDay();

        $agenceIds = [];
        $userIds = [];

        foreach (self::COMMERCIAUX as $row) {
            $agence = $this->findOrCreateAgence($row['agence']);
            $agenceIds[$agence->id] = $agence->id;

            $user = $this->findOrCreateCommercial($row, $agence->id);
            $userIds[$user->id] = $user->id;
        }

        $agenceIds = array_values($agenceIds);
        $userIds = array_values($userIds);

        $campagne = Campagne::updateOrCreate(
            ['nom' => self::NOM_CAMPAGNE],
            [
                'date_debut' => $dateDebut->toDateString(),
                'date_fin' => $dateFin->toDateString(),
                'prime_meilleur_vendeur' => 25000,
                'actif' => false,
                'statut' => Campagne::STATUT_PROGRAMMEE,
                'toutes_agences' => false,
                'remise_pourcentage' => null,
                'remise_tous_types_cartes' => true,
                'aide_hebdo_active' => false,
                'aide_hebdo_montant' => 5000,
                'aide_hebdo_carburant' => 3000,
                'aide_hebdo_credit_tel' => 2000,
                'aide_hebdo_tous_commerciaux' => false,
                'contrat_tous_commerciaux' => false,
                'contrat_emolument_forfait' => 50000,
                'contrat_forfait_communication' => 2000,
                'contrat_forfait_deplacement' => 3000,
                'contrat_representant_nom' => 'Yaya H DIALLO',
                'contrat_lieu_signature' => 'Bamako',
                'contrat_clause_libre' => null,
            ]
        );

        $campagne->agences()->sync($agenceIds);
        $campagne->typesCartesRemise()->detach();
        $campagne->beneficiairesAide()->detach();
        $campagne->signatairesContrat()->sync($userIds);

        CampagneContratArticle::seedDefaultsIfEmpty($campagne->id);

        foreach ($userIds as $uid) {
            ContratPrestationReponse::firstOrCreate(
                ['campagne_id' => $campagne->id, 'user_id' => $uid],
                ['statut' => ContratPrestationReponse::STATUT_EN_ATTENTE]
            );
        }
        ContratPrestationReponse::where('campagne_id', $campagne->id)
            ->whereNotIn('user_id', $userIds)
            ->delete();

        if (! $campagne->contrat_publie_at) {
            $campagne->update(['contrat_publie_at' => now()]);
        }

        Campagne::syncStatuts();

        $this->command->info('Campagne « '.self::NOM_CAMPAGNE.' » (ID '.$campagne->id.') : '.$dateDebut->format('d/m/Y').' → '.$dateFin->format('d/m/Y').'.');
        $this->command->info('Agences liées : '.count($agenceIds).' ; signataires : '.count($userIds).'.');
        $this->command->info('Détail connexion (identifiant = n° téléphone) :');
        foreach (self::COMMERCIAUX as $row) {
            $tel = $this->normalizePhone($row['tel']);
            $this->command->line('  '.mb_strtoupper(trim($row['noms'])).' '.$row['prenom'].' — '.$tel.' — MDP : '.$this->motDePassePourTelephone($tel));
        }
    }

    private function findOrCreateAgence(string $nom): Agence
    {
        $n = trim($nom);
        $key = $this->normalizeAgenceKey($n);
        $canonique = self::AGENCE_ALIASES[$key] ?? $n;

        $existing = Agence::query()
            ->whereRaw('LOWER(TRIM(REPLACE(REPLACE(nom, "-", " "), "/", " "))) = ?', [
                $this->normalizeAgenceKey($canonique),
            ])
            ->orWhereRaw('LOWER(TRIM(nom)) = ?', [mb_strtolower($canonique)])
            ->first();

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

    private function motDePassePourTelephone(string $telephoneNumerique): string
    {
        $tel = $this->normalizePhone($telephoneNumerique);
        $suffix = substr($tel, -2);
        if (strlen($suffix) < 2) {
            $suffix = str_pad($suffix, 2, '0', STR_PAD_LEFT);
        }

        return 'M'.$suffix.'T@bdm';
    }

    private function emailTechniquePourTelephone(string $telephone): string
    {
        return 'juin2026.'.$telephone.'@import.gda';
    }

    private function findOrCreateCommercial(array $row, int $agenceId): User
    {
        $telephone = $this->normalizePhone($row['tel']);
        $noms = mb_strtoupper(trim($row['noms']));
        $prenom = trim($row['prenom']);
        $email = $this->emailTechniquePourTelephone($telephone);
        $hash = Hash::make($this->motDePassePourTelephone($telephone));

        $user = User::query()
            ->where(function ($q) use ($telephone, $email, $row) {
                $q->where('telephone', $telephone)
                    ->orWhere('email', $email);
                if (! empty($row['tel_ancien'])) {
                    $ancien = $this->normalizePhone($row['tel_ancien']);
                    $q->orWhere('telephone', $ancien)
                        ->orWhere('email', $this->emailTechniquePourTelephone($ancien));
                }
            })
            ->first();

        if ($user) {
            $role = in_array($user->role, ['commercial', 'commercial_telephonique'], true)
                ? $user->role
                : 'commercial';

            $user->update([
                'name' => $noms,
                'prenom' => $prenom,
                'agence_id' => $agenceId,
                'telephone' => $telephone,
                'email' => $email,
                'role' => $role,
                'actif' => true,
                'password' => $hash,
            ]);

            return $user;
        }

        return User::create([
            'name' => $noms,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $hash,
            'telephone' => $telephone,
            'role' => 'commercial',
            'agence_id' => $agenceId,
            'actif' => true,
        ]);
    }
}
