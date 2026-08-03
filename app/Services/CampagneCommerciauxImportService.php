<?php

namespace App\Services;

use App\Models\Agence;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Import en masse de commerciaux (+ agences) pour une campagne, à partir d'un texte
 * collé depuis Excel (colonnes séparées par tabulation). Réutilise les commerciaux/agences
 * déjà en base par correspondance de nom ; ne crée que ce qui manque.
 */
class CampagneCommerciauxImportService
{
    /**
     * @return array<int, array{ligne_no: int, nom: string, prenom: string, agence_nom: string, telephone: string, erreurs: string[]}>
     */
    public function parseTexteColle(string $texte): array
    {
        $lignesBrutes = preg_split('/\r\n|\r|\n/', $texte) ?: [];
        $resultat = [];
        $telephonesVus = []; // telephone (digits) => ligne_no de la première occurrence

        foreach ($lignesBrutes as $i => $ligneBrute) {
            $ligneNo = $i + 1;
            if (trim($ligneBrute) === '') {
                continue;
            }

            // Ne pas trim() la ligne entière avant découpage : ça supprimerait les tabulations
            // finales (cellules vides en fin de ligne), faussant le nombre de colonnes détecté.
            $ligne = rtrim($ligneBrute, "\r\n");
            $colonnes = explode("\t", $ligne);
            if (count($colonnes) === 1) {
                $colonnes = preg_split('/\s{2,}/', trim($ligne)) ?: [trim($ligne)];
            }
            $colonnes = array_map('trim', $colonnes);

            $mapped = $this->mapperColonnes($colonnes);
            if ($mapped === null) {
                $resultat[] = [
                    'ligne_no' => $ligneNo,
                    'nom' => '', 'prenom' => '', 'agence_nom' => '', 'telephone' => '',
                    'erreurs' => ['Format non reconnu ('.count($colonnes).' colonne(s)) — attendu : Nom, Prénom, [Quartier], Agence, Téléphone.'],
                ];
                continue;
            }

            [$nom, $prenom, $agenceNom, $telephoneBrut] = $mapped;

            if ($this->estLigneEnTete($nom, $prenom)) {
                continue;
            }

            $erreurs = [];
            if ($nom === '') {
                $erreurs[] = 'Nom manquant.';
            }
            if ($prenom === '') {
                $erreurs[] = 'Prénom manquant.';
            }
            if ($agenceNom === '') {
                $erreurs[] = 'Agence manquante.';
            }

            $telephoneDigits = preg_replace('/\D/', '', $telephoneBrut) ?? '';
            if (strlen($telephoneDigits) < 2) {
                $erreurs[] = 'Numéro de téléphone invalide.';
            } elseif (isset($telephonesVus[$telephoneDigits])) {
                $erreurs[] = 'Doublon dans la liste collée (même téléphone que la ligne '.$telephonesVus[$telephoneDigits].').';
            } else {
                $telephonesVus[$telephoneDigits] = $ligneNo;
            }

            $resultat[] = [
                'ligne_no' => $ligneNo,
                'nom' => $nom,
                'prenom' => $prenom,
                'agence_nom' => $agenceNom,
                'telephone' => $telephoneDigits,
                'erreurs' => $erreurs,
            ];
        }

        return $resultat;
    }

    /** @return array{0: string, 1: string, 2: string, 3: string}|null [nom, prenom, agence_nom, telephone] */
    private function mapperColonnes(array $colonnes): ?array
    {
        return match (count($colonnes)) {
            // N°, Nom, Prénom, Quartier, Agence, Téléphone
            6 => [$colonnes[1], $colonnes[2], $colonnes[4], $colonnes[5]],
            // Nom, Prénom, Quartier, Agence, Téléphone
            5 => [$colonnes[0], $colonnes[1], $colonnes[3], $colonnes[4]],
            // Nom, Prénom, Agence, Téléphone
            4 => [$colonnes[0], $colonnes[1], $colonnes[2], $colonnes[3]],
            default => null,
        };
    }

    private function estLigneEnTete(string $nom, string $prenom): bool
    {
        $n = mb_strtolower(trim($nom));
        $p = mb_strtolower(trim($prenom));

        return in_array($n, ['nom', 'n°', 'no'], true) || $p === 'prénom' || $p === 'prenom';
    }

    public static function genererMotDePasseInitial(string $prenom, string $nom, string $telephoneDigits): string
    {
        $pInit = mb_strtoupper(mb_substr(trim($prenom), 0, 1));
        $nInit = mb_strtoupper(mb_substr(trim($nom), 0, 1));
        $last2 = mb_substr($telephoneDigits, -2);

        return $pInit.$last2.$nInit.'@bdm';
    }

    /** @return array{agence: ?Agence, a_creer: bool} */
    private function resoudreAgence(string $nomBrut, bool $persist): array
    {
        $normalise = mb_strtolower(trim(preg_replace('/\s+/', ' ', $nomBrut) ?? $nomBrut));
        $agence = Agence::query()->whereRaw('LOWER(TRIM(nom)) = ?', [$normalise])->first();

        if ($agence) {
            return ['agence' => $agence, 'a_creer' => false];
        }

        if (! $persist) {
            return ['agence' => null, 'a_creer' => true];
        }

        $agence = Agence::create([
            'nom' => trim($nomBrut),
            'ordre' => (int) (Agence::query()->max('ordre') ?? 0) + 1,
        ]);

        return ['agence' => $agence, 'a_creer' => true];
    }

    /** @return array{user: ?User, a_creer: bool, conflit_agence: bool} */
    private function resoudreCommercial(string $nom, string $prenom, string $telephoneDigits, ?int $agenceId, bool $persist): array
    {
        $user = User::query()->where('telephone', $telephoneDigits)->first();

        if ($user) {
            $conflit = $user->agence_id !== null && $agenceId !== null && (int) $user->agence_id !== $agenceId;

            return ['user' => $user, 'a_creer' => false, 'conflit_agence' => $conflit];
        }

        if (! $persist) {
            return ['user' => null, 'a_creer' => true, 'conflit_agence' => false];
        }

        $user = User::create([
            'name' => $nom,
            'prenom' => $prenom,
            'email' => null,
            'telephone' => $telephoneDigits,
            'password' => Hash::make(self::genererMotDePasseInitial($prenom, $nom, $telephoneDigits)),
            'role' => 'commercial',
            'agence_id' => $agenceId,
            'actif' => true,
        ]);

        return ['user' => $user, 'a_creer' => true, 'conflit_agence' => false];
    }

    /** Lecture seule : n'écrit rien en base. */
    public function previsualiser(string $texte): array
    {
        $lignesParsees = $this->parseTexteColle($texte);
        $agencesEnCoursCreation = []; // nom normalisé => vrai, pour dédupliquer l'affichage sur des lignes successives

        $lignes = [];
        $lignesValides = 0;
        $agencesACreer = 0;
        $commerciauxACreer = 0;
        $commerciauxExistants = 0;
        $erreurs = 0;

        foreach ($lignesParsees as $l) {
            if ($l['erreurs'] !== []) {
                $erreurs++;
                $lignes[] = array_merge($l, [
                    'agence_statut' => null,
                    'commercial_statut' => null,
                    'conflit_agence' => false,
                    'mot_de_passe_apercu' => null,
                ]);
                continue;
            }

            $lignesValides++;
            $normalise = mb_strtolower(trim(preg_replace('/\s+/', ' ', $l['agence_nom']) ?? $l['agence_nom']));
            $resAgence = $this->resoudreAgence($l['agence_nom'], false);

            $agenceExistePourCetteLigne = ! $resAgence['a_creer'];
            if (! $agenceExistePourCetteLigne) {
                if (! isset($agencesEnCoursCreation[$normalise])) {
                    $agencesEnCoursCreation[$normalise] = true;
                    $agencesACreer++;
                }
            }

            $resCommercial = $this->resoudreCommercial(
                $l['nom'], $l['prenom'], $l['telephone'],
                $resAgence['agence']?->id,
                false
            );

            if ($resCommercial['a_creer']) {
                $commerciauxACreer++;
            } else {
                $commerciauxExistants++;
            }

            $lignes[] = array_merge($l, [
                'agence_statut' => $agenceExistePourCetteLigne ? 'existe' : 'a_creer',
                'commercial_statut' => $resCommercial['a_creer'] ? 'a_creer' : 'existe',
                'conflit_agence' => $resCommercial['conflit_agence'],
                'mot_de_passe_apercu' => $resCommercial['a_creer']
                    ? self::genererMotDePasseInitial($l['prenom'], $l['nom'], $l['telephone'])
                    : null,
            ]);
        }

        return [
            'lignes' => $lignes,
            'resume' => [
                'lignes_valides' => $lignesValides,
                'agences_a_creer' => $agencesACreer,
                'commerciaux_a_creer' => $commerciauxACreer,
                'commerciaux_existants' => $commerciauxExistants,
                'erreurs' => $erreurs,
            ],
        ];
    }

    /**
     * Import réel (création des agences/commerciaux manquants), dans une transaction.
     *
     * @return array{user_ids: list<int>, agences_creees: int, commerciaux_crees: int, commerciaux_reutilises: int, lignes_en_erreur: int}
     */
    public function importer(string $texte): array
    {
        $lignesParsees = $this->parseTexteColle($texte);
        $valides = array_values(array_filter($lignesParsees, fn ($l) => $l['erreurs'] === []));

        return DB::transaction(function () use ($valides, $lignesParsees) {
            $userIds = [];
            $agencesCreees = 0;
            $commerciauxCrees = 0;
            $commerciauxReutilises = 0;

            foreach ($valides as $l) {
                $resAgence = $this->resoudreAgence($l['agence_nom'], true);
                if ($resAgence['a_creer']) {
                    $agencesCreees++;
                }

                $resCommercial = $this->resoudreCommercial(
                    $l['nom'], $l['prenom'], $l['telephone'],
                    $resAgence['agence']->id,
                    true
                );

                if ($resCommercial['a_creer']) {
                    $commerciauxCrees++;
                } else {
                    $commerciauxReutilises++;
                }

                $userIds[] = $resCommercial['user']->id;
            }

            return [
                'user_ids' => array_values(array_unique($userIds)),
                'agences_creees' => $agencesCreees,
                'commerciaux_crees' => $commerciauxCrees,
                'commerciaux_reutilises' => $commerciauxReutilises,
                'lignes_en_erreur' => count($lignesParsees) - count($valides),
            ];
        });
    }
}
