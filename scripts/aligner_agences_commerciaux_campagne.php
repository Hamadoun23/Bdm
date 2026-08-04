<?php

/**
 * Aligne l'agence des commerciaux de la campagne d'enrôlement en cours sur la
 * liste officielle « LA LISTE DES COMMERCIAUX CAMPAGNE BDM-PI »
 * (docs/LISE_Commerciaux_BDM-PI.xlsx).
 *
 * Plusieurs commerciaux ont été rattachés à la mauvaise agence lors de l'import.
 * Corriger `users.agence_id` ne suffit pas : `enrolement_clients.agence_id` fige
 * l'agence au moment de la saisie et c'est cette colonne qu'agrègent les rapports
 * « par agence ». Ce script corrige les deux, sur la campagne d'enrôlement
 * uniquement, et journalise chaque correction dans `commercial_agence_transferts`.
 *
 * Les ventes des campagnes passées (`ventes.agence_id`) ne sont PAS touchées :
 * l'historique de vente reste figé, comme le fait déjà le transfert d'agence admin.
 *
 * Usage :
 *   php scripts/aligner_agences_commerciaux_campagne.php            # simulation
 *   php scripts/aligner_agences_commerciaux_campagne.php --apply    # applique
 */

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Agence;
use App\Models\Campagne;
use App\Models\CommercialAgenceTransfert;
use App\Models\EnrolementClient;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/** Liste officielle : [téléphone, nom, prénom, agence] — recopiée du fichier Excel. */
const LISTE = [
    ['79790604', 'OULIBALY', 'Hawa C', 'PME/PMI'],
    ['78522819', 'KANSAYE', 'Diahara', 'MISSIRA'],
    ['72715555', 'KEITA', 'Djelika', 'HAMDALLAYE'],
    ['74353690', 'KANOUTE', 'Nènè', 'API'],
    ['72189105', 'DEMBELE', 'Salimatou', 'KOROFINA'],
    ['66986621', 'YACOULYE', 'Assetou', 'AP2'],
    ['90889198', 'MAIGA', 'Adiaratou A.', 'SOGONIKO'],
    ['72076576', 'COULIBALY', 'Madiè', 'BS'],
    ['70316801', 'SACKO', 'Fanta', 'DJICORONI-PARA'],
    ['73006222', 'TOURE', 'Nana A.', 'LAFIABOUGOU'],
    ['75705808', 'SALL', 'Mohamed', 'YIRIMADIO'],
    ['69098738', 'TOURE', 'Many Madani', 'DRAMANE DIAKITE'],
    ['73907530', 'CAMARA', 'Aly Badra', 'QUINZAMBOUGOU'],
    ['92666022', 'COULIBALY', 'Fatoumata', 'SEBENIKORO'],
    ['63072412', 'DEMBELE', 'Wassa', 'BACO-DJICORONI'],
];

$apply = in_array('--apply', $argv, true);

$campagne = Campagne::query()
    ->where('type', Campagne::TYPE_ENROLEMENT_APP)
    ->orderByDesc('date_debut')
    ->first();
if (! $campagne) {
    exit("Aucune campagne d'enrôlement trouvée.\n");
}

$agences = Agence::query()->get();

/**
 * Résout un libellé d'agence de la liste vers une agence en base.
 * La base contient des doublons de libellé (« AP2 »/« AP 2 », « PME/PMI »/« PME/ PMI ») :
 * on privilégie donc l'égalité stricte, puis l'insensible à la casse, et seulement
 * en dernier recours la comparaison normalisée — qui doit rester non ambiguë.
 */
function resoudreAgence(string $libelle, $agences): array
{
    $normaliser = function (string $v): string {
        $v = strtr($v, [
            'à' => 'a', 'â' => 'a', 'ä' => 'a', 'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'î' => 'i', 'ï' => 'i', 'ô' => 'o', 'ö' => 'o', 'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'ç' => 'c',
        ]);

        return preg_replace('/[^A-Z0-9]/', '', mb_strtoupper($v));
    };

    $exact = $agences->firstWhere('nom', $libelle);
    if ($exact) {
        return ['agence' => $exact, 'mode' => 'exact'];
    }

    $casse = $agences->filter(fn ($a) => mb_strtolower($a->nom) === mb_strtolower($libelle))->values();
    if ($casse->count() === 1) {
        return ['agence' => $casse->first(), 'mode' => 'casse'];
    }

    $norm = $agences->filter(fn ($a) => $normaliser($a->nom) === $normaliser($libelle))->values();
    if ($norm->count() === 1) {
        return ['agence' => $norm->first(), 'mode' => 'normalisé'];
    }

    return [
        'agence' => null,
        'mode' => $norm->isEmpty() ? 'introuvable' : 'ambigu',
        'candidats' => $norm->map(fn ($a) => $a->nom.' (#'.$a->id.')')->all(),
    ];
}

echo "Campagne : {$campagne->nom} (#{$campagne->id}, {$campagne->statut}, ";
echo ($campagne->toutes_agences ? 'toutes agences' : 'périmètre restreint').")\n";
echo 'Mode     : '.($apply ? 'APPLICATION' : 'simulation (aucune écriture)')."\n";
echo str_repeat('=', 100)."\n";

$plan = [];
$bloquants = [];

foreach (LISTE as [$tel, $nomListe, $prenomListe, $libelleAgence]) {
    $user = User::query()->where('telephone', $tel)->first();
    if (! $user) {
        $bloquants[] = "Commercial introuvable pour le téléphone {$tel} ({$nomListe} {$prenomListe}).";

        continue;
    }

    $res = resoudreAgence($libelleAgence, $agences);
    if (! $res['agence']) {
        $bloquants[] = "Agence « {$libelleAgence} » {$res['mode']} pour {$nomListe} {$prenomListe}"
            .(isset($res['candidats']) && $res['candidats'] ? ' — candidats : '.implode(', ', $res['candidats']) : '').'.';

        continue;
    }

    $cible = $res['agence'];

    $enrolementsHorsCible = EnrolementClient::query()
        ->where('user_id', $user->id)
        ->where('campagne_id', $campagne->id)
        ->where('agence_id', '!=', $cible->id)
        ->get();

    $plan[] = [
        'user' => $user,
        'cible' => $cible,
        'mode' => $res['mode'],
        'libelle' => $libelleAgence,
        'profilAvant' => $user->agence_id ? (int) $user->agence_id : null,
        'majProfil' => (int) $user->agence_id !== (int) $cible->id,
        'enrolements' => $enrolementsHorsCible,
        'nomListe' => $nomListe.' '.$prenomListe,
    ];
}

printf("%-11s %-26s %-18s %-18s %-9s %s\n", 'TÉLÉPHONE', 'COMMERCIAL (base)', 'AGENCE ACTUELLE', 'AGENCE LISTE', 'ENRÔL.', 'ACTION');
echo str_repeat('-', 100)."\n";

$nbProfils = 0;
$nbEnrolements = 0;

foreach ($plan as $p) {
    $user = $p['user'];
    $actuelle = $user->agence?->nom ?? '—';
    $nbEnr = $p['enrolements']->count();

    $actions = [];
    if ($p['majProfil']) {
        $actions[] = 'profil';
        $nbProfils++;
    }
    if ($nbEnr > 0) {
        $actions[] = $nbEnr.' enrôlement(s)';
        $nbEnrolements += $nbEnr;
    }

    printf(
        "%-11s %-26s %-18s %-18s %-9s %s\n",
        $user->telephone,
        mb_strimwidth(trim($user->name.' '.$user->prenom), 0, 26, ''),
        mb_strimwidth($actuelle, 0, 18, ''),
        mb_strimwidth($p['cible']->nom.' #'.$p['cible']->id, 0, 18, ''),
        $nbEnr > 0 ? (string) $nbEnr : '-',
        $actions ? implode(' + ', $actions) : 'conforme'
    );
}

echo str_repeat('-', 100)."\n";
echo "Profils à corriger    : {$nbProfils}\n";
echo "Enrôlements à migrer  : {$nbEnrolements}\n";

$ecartsNoms = array_filter($plan, function ($p) {
    $base = preg_replace('/\s+/', '', mb_strtolower($p['user']->name.$p['user']->prenom));
    $liste = preg_replace('/\s+/', '', mb_strtolower($p['nomListe']));

    return ! str_contains($base, mb_substr($liste, 0, 4));
});
if ($ecartsNoms) {
    echo "\nÉcarts de libellé nom/prénom (non modifiés par ce script, pour information) :\n";
    foreach ($ecartsNoms as $p) {
        echo "  - {$p['user']->telephone} : base « ".trim($p['user']->name.' '.$p['user']->prenom)." » / liste « {$p['nomListe']} »\n";
    }
}

if ($bloquants) {
    echo "\nBLOQUANT — aucune écriture effectuée :\n";
    foreach ($bloquants as $b) {
        echo "  - {$b}\n";
    }
    exit(1);
}

if ($nbProfils === 0 && $nbEnrolements === 0) {
    echo "\nTout est déjà conforme à la liste. Rien à faire.\n";
    exit(0);
}

if (! $apply) {
    echo "\nSimulation uniquement. Relancer avec --apply pour écrire en base.\n";
    exit(0);
}

DB::transaction(function () use ($plan, $campagne) {
    foreach ($plan as $p) {
        $ids = $p['enrolements']->pluck('id')->all();
        if ($ids === [] && ! $p['majProfil']) {
            continue;
        }

        if ($ids !== []) {
            EnrolementClient::query()->whereIn('id', $ids)->update([
                'agence_id' => $p['cible']->id,
                'updated_at' => now(),
            ]);
        }

        if ($p['majProfil']) {
            $p['user']->update(['agence_id' => $p['cible']->id]);
        }

        CommercialAgenceTransfert::query()->create([
            'commercial_user_id' => $p['user']->id,
            'admin_user_id' => $p['user']->id,
            'nouvelle_agence_id' => $p['cible']->id,
            'snapshots' => $p['enrolements']
                ->map(fn ($e) => ['enrolement_client_id' => (int) $e->id, 'agence_avant' => (int) $e->agence_id])
                ->all(),
            'profil_agence_avant' => $p['majProfil'] ? $p['profilAvant'] : null,
            'profil_agence_apres' => $p['majProfil'] ? (int) $p['cible']->id : null,
            'note' => 'Alignement sur la liste officielle des commerciaux — campagne « '.$campagne->nom
                .' ». Agence cible « '.$p['cible']->nom.' ». '.count($ids)
                .' enrôlement(s) réattribué(s). Ventes des campagnes passées inchangées.',
        ]);
    }

    // La campagne est normalement en « toutes agences » ; si son périmètre est
    // restreint, on y ajoute les agences cibles pour ne pas bloquer la saisie.
    if (! $campagne->toutes_agences) {
        $campagne->agences()->syncWithoutDetaching(
            array_values(array_unique(array_map(fn ($p) => (int) $p['cible']->id, $plan)))
        );
        Campagne::apresModificationDatesOuPerimetre($campagne);
    }
});

echo "\nAppliqué. Vérification :\n";
echo str_repeat('-', 100)."\n";

$restant = 0;
foreach ($plan as $p) {
    $user = $p['user']->fresh('agence');
    $horsCible = EnrolementClient::query()
        ->where('user_id', $user->id)
        ->where('campagne_id', $campagne->id)
        ->where('agence_id', '!=', $p['cible']->id)
        ->count();
    $surCible = EnrolementClient::query()
        ->where('user_id', $user->id)
        ->where('campagne_id', $campagne->id)
        ->where('agence_id', $p['cible']->id)
        ->count();
    $restant += $horsCible;

    printf(
        "%-11s profil « %s » | %d enrôlement(s) sur « %s » | hors cible : %d\n",
        $user->telephone,
        $user->agence?->nom ?? '—',
        $surCible,
        $p['cible']->nom,
        $horsCible
    );
}

echo str_repeat('-', 100)."\n";
echo $restant === 0
    ? "Tous les commerciaux de la liste sont alignés.\n"
    : "ATTENTION : {$restant} enrôlement(s) encore hors agence cible.\n";
