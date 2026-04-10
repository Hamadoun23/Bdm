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
 * Campagne « Avril 2è vague » :9 avril → 8 mai (mois calendaire inclus),
 * périmètre restreint aux agences et commerciaux listés.
 *
 * Mot de passe par commercial : format M{XX}T@bdm (ex. M82T@bdm), XX =2 derniers chiffres du n° de téléphone en base.
 */
class CampagneAvril2eVagueSeeder extends Seeder
{
    private const NOM_CAMPAGNE = 'Avril 2è vague';

    /** @var list<array{noms: string, prenom: string, agence: string, tel: string}> */
    private const COMMERCIAUX = [
        ['noms' => 'KONE', 'prenom' => 'Modibo', 'agence' => 'SEMA GESCO', 'tel' => '83840345'],
        ['noms' => 'CISSE', 'prenom' => 'Kadidai CAMRA', 'agence' => 'MISSIRA', 'tel' => '72718370'],
        ['noms' => 'DIARRA', 'prenom' => 'Soumail', 'agence' => 'QUINZAMBOUGOU', 'tel' => '91105337'],
        ['noms' => 'TOUNKARA', 'prenom' => 'Mamadou', 'agence' => 'SEBENIKORO', 'tel' => '70122814'],
        ['noms' => 'KEITA', 'prenom' => 'Djelika', 'agence' => 'HAMDALLAYE', 'tel' => '72715555'],
        ['noms' => 'DIARRA', 'prenom' => 'Assetou YALCOYE', 'agence' => 'LAFIABOUGOU', 'tel' => '90983335'],
        ['noms' => 'COULIBALY', 'prenom' => 'Mamadou', 'agence' => 'TOROKOROBOUGOU', 'tel' => '76411856'],
        ['noms' => 'MACALOU', 'prenom' => 'Adama', 'agence' => 'MAGNAMBOUGOU', 'tel' => '71690729'],
        ['noms' => 'DIALLO', 'prenom' => 'FATI', 'agence' => 'AZAR', 'tel' => '71514623'],
        ['noms' => 'TURE', 'prenom' => 'Imran', 'agence' => 'BOULKASSOULBOUGOU', 'tel' => '92574790'],
        ['noms' => 'BATHILY', 'prenom' => 'Maimouna', 'agence' => 'KATI', 'tel' => '65893863'],
    ];

    public function run(): void
    {
        $dateDebut = Carbon::parse('2026-04-09')->startOfDay();
        $dateFin = $dateDebut->copy()->addMonth()->subDay()->endOfDay();

        $agenceIds = [];
        $userIds = [];

        foreach (self::COMMERCIAUX as $row) {
            $agence = $this->findOrCreateAgence($row['agence']);
            $agenceIds[$agence->id] = $agence->id;

            $tel = $this->normalizePhone($row['tel']);
            $user = $this->findOrCreateCommercial(
                noms: mb_strtoupper(trim($row['noms'])),
                prenom: trim($row['prenom']),
                agenceId: $agence->id,
                telephone: $tel,
            );
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
        $this->command->info('Mot de passe : M{2 derniers chiffres du tél}T@bdm (ex. …8345 → M45T@bdm). Détail :');
        foreach (self::COMMERCIAUX as $row) {
            $tel = $this->normalizePhone($row['tel']);
            $this->command->line('  '.mb_strtoupper(trim($row['noms'])).' ('.$tel.') : '.$this->motDePassePourTelephone($tel));
        }
    }

    private function findOrCreateAgence(string $nom): Agence
    {
        $n = trim($nom);
        $existing = Agence::query()
            ->whereRaw('LOWER(TRIM(nom)) = ?', [mb_strtolower($n)])
            ->first();
        if ($existing) {
            return $existing;
        }

        return Agence::create(['nom' => $n]);
    }

    private function normalizePhone(string $raw): string
    {
        return preg_replace('/\D+/', '', $raw) ?? '';
    }

    /** Format type M82T@bdm : M +2 derniers chiffres du téléphone + T@bdm (sans espace). */
    private function motDePassePourTelephone(string $telephoneNumerique): string
    {
        $tel = $this->normalizePhone($telephoneNumerique);
        $suffix = substr($tel, -2);
        if (strlen($suffix) < 2) {
            $suffix = str_pad($suffix, 2, '0', STR_PAD_LEFT);
        }

        return 'M'.$suffix.'T@bdm';
    }

    private function findOrCreateCommercial(string $noms, string $prenom, int $agenceId, string $telephone): User
    {
        $email = 'avril2.vague.'.$telephone.'@import.gda';

        $user = User::query()
            ->where(function ($q) use ($telephone, $email) {
                $q->where('telephone', $telephone)
                    ->orWhere('email', $email);
            })
            ->first();

        $hash = Hash::make($this->motDePassePourTelephone($telephone));

        if ($user) {
            $user->update([
                'name' => $noms,
                'prenom' => $prenom,
                'agence_id' => $agenceId,
                'telephone' => $telephone,
                'role' => 'commercial',
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
