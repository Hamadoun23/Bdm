<?php

/**
 * Active DIARRE (BS) et TRAORE (Kalaban coura) pour la campagne Juin :
 * ajoute leurs agences au périmètre campagne sans modifier l'historique des ventes.
 *
 * php scripts/activer_diare_traore_juin.php
 */

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Campagne;
use App\Models\User;
use App\Models\Vente;

$campagne = Campagne::query()
    ->where('nom', 'like', '%Juin%')
    ->orderByDesc('date_debut')
    ->first();

if (! $campagne) {
    echo "Campagne Juin introuvable.\n";
    exit(1);
}

$telephones = ['66986621', '70277320'];
$users = User::query()->whereIn('telephone', $telephones)->get();

if ($users->count() !== 2) {
    echo "Commerciaux introuvables (trouvés: {$users->count()}).\n";
    exit(1);
}

foreach ($users as $user) {
    if (! $user->agence_id) {
        echo "ERREUR: {$user->name} sans agence_id.\n";
        exit(1);
    }

    $signataire = $campagne->signatairesContrat()->where('users.id', $user->id)->exists();
    if (! $signataire) {
        $campagne->signatairesContrat()->syncWithoutDetaching([$user->id]);
        echo "Signataire ajouté: {$user->name} ({$user->telephone})\n";
    }

    $ids = $campagne->agences()->pluck('agences.id')->map(fn ($id) => (int) $id)->all();
    $idsVentes = Vente::query()
        ->where('user_id', $user->id)
        ->where('campagne_id', $campagne->id)
        ->distinct()
        ->pluck('agence_id')
        ->map(fn ($id) => (int) $id)
        ->all();

    $merged = array_values(array_unique(array_merge($ids, $idsVentes, [(int) $user->agence_id])));
    $campagne->agences()->sync($merged);

    $user->update(['actif' => true]);

    echo "OK {$user->name} | {$user->agence?->nom} | signataire=".($signataire ? 'déjà' : 'nouveau')." | agences campagne=".count($merged)."\n";
}

Campagne::apresModificationDatesOuPerimetre($campagne);

echo "\nVérification getActivesPourAgence:\n";
foreach ($users as $user) {
    $actives = Campagne::getActivesPourAgence($user->agence_id);
    $noms = $actives->pluck('nom')->implode(', ');
    echo "  {$user->name} (agence {$user->agence?->nom}): ".($actives->isNotEmpty() ? $noms : 'AUCUNE')."\n";
}

echo "\nTerminé.\n";
