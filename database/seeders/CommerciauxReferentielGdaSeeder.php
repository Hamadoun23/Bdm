<?php

namespace Database\Seeders;

use App\Models\Agence;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

/**
 * Commerciaux et agences issus de Info.md (référentiel GDA Bamako + intérieur).
 *
 * Mot de passe : exactement 8 caractères, contient « @bdm » (4 car.) — impossible à deviner
 * pour un collègue sans connaître le numéro complet et l’algorithme :
 *   [1ère lettre prénom][2 chiffres tirés du téléphone à un offset][1ère lettre nom]@bdm
 * Les 2 chiffres sont lus en enchaînant deux copies du numéro (évite hors bornes),
 * offset = (somme des codes Unicode du nom + prénom) % longueur_du_numéro.
 *
 * Connexion : identifiant = numéro de téléphone (chiffres uniquement, sans indicatif).
 *
 * Exécutez après les admins (ex. SoloAdminSeeder) : ne pas tronquer users si vous voulez conserver les commerciaux.
 */
class CommerciauxReferentielGdaSeeder extends Seeder
{
    /** @return array<int, array{site: string, nom: string, prenom: string, telephone: string, quartier: string}> */
    private function lignesBamako(): array
    {
        return [
            ['site' => 'Niamana', 'nom' => 'THERA', 'prenom' => 'Mariam', 'telephone' => '74082712', 'quartier' => 'Niamana'],
            ['site' => 'PME/PMI', 'nom' => 'NIAMBLE', 'prenom' => 'Aissata N', 'telephone' => '66904040', 'quartier' => 'Magnambougou ACI'],
            ['site' => 'Centre d\'appel', 'nom' => 'KANSAYE', 'prenom' => 'Diahara', 'telephone' => '98119629', 'quartier' => 'Sotuba logements sociaux'],
            ['site' => 'Sotuba', 'nom' => 'DIAKITE', 'prenom' => 'Nagnouma TOURE', 'telephone' => '79053641', 'quartier' => 'Sangarebougou'],
            ['site' => 'Sogoniko', 'nom' => 'MAIGA', 'prenom' => 'Adiaratou A', 'telephone' => '90889198', 'quartier' => 'Missabougou'],
            ['site' => 'Korofina', 'nom' => 'DRAME', 'prenom' => 'Sadio', 'telephone' => '92096399', 'quartier' => 'Korofina'],
            ['site' => 'Baco Djicoroni', 'nom' => 'DIALLO', 'prenom' => 'Ami Colley', 'telephone' => '76040083', 'quartier' => 'Baco Djicoroni ACI'],
            ['site' => 'Dibida', 'nom' => 'SANGARE', 'prenom' => 'Fatimata', 'telephone' => '78754962', 'quartier' => 'Baco Djicoroni'],
            ['site' => 'AP 2', 'nom' => 'CAMARA', 'prenom' => 'Ali Badara', 'telephone' => '73907530', 'quartier' => 'Lafiabougou'],
            ['site' => 'N\'Golonina', 'nom' => 'TOURE', 'prenom' => 'Mary N', 'telephone' => '69098738', 'quartier' => 'Sebenikoro'],
            ['site' => 'Kalaban coura', 'nom' => 'SERITA', 'prenom' => 'Massitan', 'telephone' => '79018138', 'quartier' => 'Banankabougou'],
            ['site' => 'Maison du Hadj', 'nom' => 'FOFANA', 'prenom' => 'Kadiatou', 'telephone' => '76612042', 'quartier' => 'Badianlan I'],
            ['site' => 'Centre d\'appel', 'nom' => 'KANOUTE', 'prenom' => 'Nènè', 'telephone' => '74353690', 'quartier' => 'ACI BOCOUM'],
            ['site' => 'Yirimadio', 'nom' => 'COULIBALY', 'prenom' => 'Aminata', 'telephone' => '71766277', 'quartier' => 'Niamana Attbougou'],
            ['site' => 'Futura', 'nom' => 'SANGARE', 'prenom' => 'Binta', 'telephone' => '71616201', 'quartier' => 'Hamdallaye'],
            ['site' => 'Djicoroni para', 'nom' => 'TOGORA', 'prenom' => 'Lassina', 'telephone' => '83140127', 'quartier' => 'Yirimadio'],
            ['site' => 'Dramane DIAKITE', 'nom' => 'DABITAO', 'prenom' => 'Oumou', 'telephone' => '64924953', 'quartier' => 'Kati'],
            ['site' => 'Kabala', 'nom' => 'TRAORE', 'prenom' => 'Adama', 'telephone' => '70277320', 'quartier' => 'Baco Djicoroni'],
            ['site' => 'AP 1', 'nom' => 'TOURE', 'prenom' => 'Hawoye', 'telephone' => '76326633', 'quartier' => 'Sotuba'],
        ];
    }

    /** @return array<int, array{site: string, nom: string, prenom: string, telephone: string, quartier: string}> */
    private function lignesInterieur(): array
    {
        return [
            ['site' => 'Ségou 2', 'nom' => 'THIAM', 'prenom' => 'Mohamed Aly', 'telephone' => '70442854', 'quartier' => 'Ségou 2'],
            ['site' => 'Ségou 1', 'nom' => 'TOURE', 'prenom' => 'Harerata', 'telephone' => '89501249', 'quartier' => 'Ségou 1'],
            ['site' => 'San', 'nom' => 'OUMAROU', 'prenom' => 'Hawa', 'telephone' => '79771505', 'quartier' => 'San'],
            ['site' => 'Mopti', 'nom' => 'NIANGALE', 'prenom' => 'Fatoumata', 'telephone' => '93244009', 'quartier' => 'Mopti'],
            ['site' => 'Koulikoro', 'nom' => 'SANOGO', 'prenom' => 'Fatoumata', 'telephone' => '92330460', 'quartier' => 'Koulikoro'],
            ['site' => 'Dioila', 'nom' => 'SIDIBE', 'prenom' => 'Kadidiatou', 'telephone' => '92021391', 'quartier' => 'Dioila'],
            ['site' => 'Sikasso', 'nom' => 'DEMBELE', 'prenom' => 'Karidiata', 'telephone' => '60625221', 'quartier' => 'Sikasso'],
            ['site' => 'Tombouctou', 'nom' => 'TRAORE', 'prenom' => 'Mariam Bagna', 'telephone' => '94888495', 'quartier' => 'Tombouctou'],
            ['site' => 'Kita', 'nom' => 'HAIDARA', 'prenom' => 'Awa', 'telephone' => '76277641', 'quartier' => 'Kita'],
            ['site' => 'Kayes 1', 'nom' => 'SISSOKO', 'prenom' => 'Djeneba', 'telephone' => '69418521', 'quartier' => 'Kayes 1'],
        ];
    }

    private function buildPassword(string $prenom, string $nom, string $telephoneDigits): string
    {
        $phone = $telephoneDigits !== '' ? $telephoneDigits : '00000000';
        $prenom = trim($prenom);
        $nom = trim($nom);
        $cP = mb_strtoupper(mb_substr($prenom, 0, 1));
        $cN = mb_strtoupper(mb_substr($nom, 0, 1));
        if ($cP === '' || ! preg_match('/^[A-ZÀ-Ÿ]$/u', $cP)) {
            $cP = 'X';
        }
        if ($cN === '' || ! preg_match('/^[A-ZÀ-Ÿ]$/u', $cN)) {
            $cN = 'X';
        }

        $sum = 0;
        foreach (preg_split('//u', $prenom.$nom, -1, PREG_SPLIT_NO_EMPTY) ?: [] as $ch) {
            $sum += mb_ord($ch, 'UTF-8');
        }
        $len = strlen($phone);
        $off = $len > 0 ? $sum % $len : 0;
        $rolled = $phone.$phone;
        $pair = substr($rolled, $off, 2);

        $plain = $cP.$pair.$cN.'@bdm';
        if (strlen($plain) !== 8) {
            throw new \RuntimeException('Mot de passe interne : longueur attendue 8, obtenue '.strlen($plain));
        }

        return $plain;
    }

    public function run(): void
    {
        $lignes = array_merge($this->lignesBamako(), $this->lignesInterieur());
        $seenPhones = [];
        $seenPasswords = [];
        /** @var list<array{prenom: string, nom: string, telephone: string, site: string, password: string}> */
        $credentials = [];

        foreach ($lignes as $row) {
            $phone = preg_replace('/\D/', '', $row['telephone']);
            if ($phone === '') {
                $this->command->warn('Téléphone vide — ignoré : '.$row['prenom'].' '.$row['nom']);

                continue;
            }
            if (isset($seenPhones[$phone])) {
                throw new \RuntimeException('Doublon de téléphone : '.$phone);
            }
            $seenPhones[$phone] = true;

            $plain = $this->buildPassword($row['prenom'], $row['nom'], $phone);
            if (isset($seenPasswords[$plain])) {
                throw new \RuntimeException('Collision de mot de passe (ajustez l’algorithme) : '.$plain);
            }
            $seenPasswords[$plain] = true;

            $agence = Agence::firstOrCreate(
                ['nom' => $row['site']],
                ['adresse' => $row['quartier']],
            );

            User::updateOrCreate(
                ['telephone' => $phone],
                [
                    'name' => $row['nom'],
                    'prenom' => $row['prenom'],
                    'email' => null,
                    'password' => Hash::make($plain),
                    'role' => 'commercial',
                    'agence_id' => $agence->id,
                    'actif' => true,
                ]
            );

            $credentials[] = [
                'prenom' => $row['prenom'],
                'nom' => $row['nom'],
                'telephone' => $phone,
                'site' => $row['site'],
                'password' => $plain,
            ];
        }

        File::put(
            storage_path('app/commerciaux_gda_credentials.json'),
            json_encode($credentials, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        $this->command->info('Fichier confidentiel (mots de passe en clair) : storage/app/commerciaux_gda_credentials.json');

        $this->command->info('Commerciaux référentiel GDA : '.count($lignes).' comptes (connexion : identifiant = n° téléphone, 8 caractères dont la séquence @bdm).');
    }
}
