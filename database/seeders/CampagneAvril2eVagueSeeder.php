<?php

namespace Database\Seeders;

use App\Models\Agence;
use App\Models\Campagne;
use App\Models\CampagneContratArticle;
use App\Models\ContratPrestationReponse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Campagne « Avril 2è vague » :9 avril → 8 mai (mois calendaire inclus),
 * périmètre restreint aux agences et commerciaux listés.
 *
 * Mot de passe par commercial : en général M{XX}T@bdm (2 derniers chiffres du n°).
 * Exception : identifiant téléphone seul + mot de passe sur 3 derniers chiffres (voir ligne TRAORE).
 */
class CampagneAvril2eVagueSeeder extends Seeder
{
    private const NOM_CAMPAGNE = 'Avril 2è vague';

    /**
     * @var list<array{noms: string, prenom: string, agence: string, tel: string, identifiant_tel_seul?: bool, mot_passe_last3?: bool}>
     */
    private const COMMERCIAUX = [
        ['noms' => 'KONE', 'prenom' => 'Modibo', 'agence' => 'SEMA GESCO', 'tel' => '83840345'],
        ['noms' => 'DIARRA', 'prenom' => 'Soumail', 'agence' => 'QUINZAMBOUGOU', 'tel' => '91105337'],
        ['noms' => 'TOUNKARA', 'prenom' => 'Mamadou', 'agence' => 'SEBENIKORO', 'tel' => '70122814'],
        ['noms' => 'KEITA', 'prenom' => 'Djelika', 'agence' => 'HAMDALLAYE', 'tel' => '72715555'],
        ['noms' => 'DIARRA', 'prenom' => 'Assetou YALCOYE', 'agence' => 'LAFIABOUGOU', 'tel' => '90983335'],
        ['noms' => 'COULIBALY', 'prenom' => 'Mamadou', 'agence' => 'TOROKOROBOUGOU', 'tel' => '76411856'],
        ['noms' => 'MACALOU', 'prenom' => 'Adama', 'agence' => 'MAGNAMBOUGOU', 'tel' => '71690729'],
        ['noms' => 'TURE', 'prenom' => 'Imran', 'agence' => 'BOULKASSOULBOUGOU', 'tel' => '92574790'],
        ['noms' => 'BATHILY', 'prenom' => 'Maimouna', 'agence' => 'KATI', 'tel' => '65893863'],
        ['noms' => 'TRAORE', 'prenom' => 'Youssouf', 'agence' => 'Kabala', 'tel' => '60032329', 'identifiant_tel_seul' => true, 'mot_passe_last3' => true],
    ];

    /** Téléphones retirés de cette campagne (comptes supprimés s’ils n’ont aucune vente). */
    private const TELEPHONES_RETIRES = ['72718370', '71514623'];

    public function run(): void
    {
        $dateDebut = Carbon::parse('2026-04-09')->startOfDay();
        $dateFin = $dateDebut->copy()->addMonth()->subDay()->endOfDay();

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

        $this->retirerCommerciauxDeLaCampagne($campagne, self::TELEPHONES_RETIRES);

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
        $this->command->info('Détail connexion (commercial : n° téléphone dans le champ Identifiant) :');
        foreach (self::COMMERCIAUX as $row) {
            $tel = $this->normalizePhone($row['tel']);
            $identifiant = ! empty($row['identifiant_tel_seul'])
                ? 'tél. '.$tel.' uniquement (e-mail technique '.$tel.'@identifiant.gda)'
                : 'tél. '.$tel.' ou e-mail avril2.vague.'.$tel.'@import.gda';
            $this->command->line('  '.mb_strtoupper(trim($row['noms'])).' — '.$identifiant.' — MDP : '.$this->motDePassePourLigne($row));
        }
    }

    /**
     * Détache de la campagne « Avril 2è vague » et supprime le compte s’il n’a aucune vente (sinon désactivation seulement).
     */
    private function retirerCommerciauxDeLaCampagne(Campagne $campagne, array $telephonesNumeriques): void
    {
        foreach ($telephonesNumeriques as $raw) {
            $tel = $this->normalizePhone($raw);
            if ($tel === '') {
                continue;
            }
            $email = 'avril2.vague.'.$tel.'@import.gda';
            $user = User::query()
                ->where(function ($q) use ($tel, $email) {
                    $q->where('telephone', $tel)->orWhere('email', $email);
                })
                ->first();
            if (! $user) {
                continue;
            }

            $campagne->signatairesContrat()->detach($user->id);
            ContratPrestationReponse::query()
                ->where('campagne_id', $campagne->id)
                ->where('user_id', $user->id)
                ->delete();

            if ($user->ventes()->exists() || $user->clients()->exists()) {
                $this->command->warn('Compte conservé (ventes ou clients liés) : '.$user->email.' — retiré des signataires de « '.self::NOM_CAMPAGNE.' ».');

                continue;
            }

            DB::table('campagne_commercial_contrat')->where('user_id', $user->id)->delete();
            DB::table('campagne_aide_beneficiaire')->where('user_id', $user->id)->delete();
            $user->delete();
            $this->command->info('Compte supprimé (aucune vente) : téléphone '.$tel);
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

    /** Évite collision avec le format 2 chiffres (ex. Youssouf) : M +3 derniers chiffres + T@bdm. */
    private function motDePassePourTelephoneDerniers3(string $telephoneNumerique): string
    {
        $tel = $this->normalizePhone($telephoneNumerique);
        $suffix = substr($tel, -3);
        if (strlen($suffix) < 3) {
            $suffix = str_pad($suffix, 3, '0', STR_PAD_LEFT);
        }

        return 'M'.$suffix.'T@bdm';
    }

    private function motDePassePourLigne(array $row): string
    {
        $tel = $this->normalizePhone($row['tel']);

        return ! empty($row['mot_passe_last3'])
            ? $this->motDePassePourTelephoneDerniers3($tel)
            : $this->motDePassePourTelephone($tel);
    }

    /** E-mail unique en base ; connexion possible au n° (voir LoginRequest). */
    private function emailTechniquePourLigne(array $row, string $telephone): string
    {
        if (! empty($row['identifiant_tel_seul'])) {
            return $telephone.'@identifiant.gda';
        }

        return 'avril2.vague.'.$telephone.'@import.gda';
    }

    private function findOrCreateCommercial(array $row, int $agenceId): User
    {
        $telephone = $this->normalizePhone($row['tel']);
        $noms = mb_strtoupper(trim($row['noms']));
        $prenom = trim($row['prenom']);
        $email = $this->emailTechniquePourLigne($row, $telephone);
        $emailLegacyAvril = 'avril2.vague.'.$telephone.'@import.gda';
        $hash = Hash::make($this->motDePassePourLigne($row));

        $user = User::query()
            ->where(function ($q) use ($telephone, $email, $emailLegacyAvril, $row) {
                $q->where('telephone', $telephone)
                    ->orWhere('email', $email);
                if (! empty($row['identifiant_tel_seul'])) {
                    $q->orWhere('email', $emailLegacyAvril);
                }
            })
            ->first();

        if ($user) {
            $user->update([
                'name' => $noms,
                'prenom' => $prenom,
                'agence_id' => $agenceId,
                'telephone' => $telephone,
                'email' => $email,
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
