<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$c = App\Models\Campagne::where('nom', 'Juin 2026')->first();
if (! $c) {
    exit("Pas de campagne Juin\n");
}

$tels = ['66986621','70179839','74082712','79053641','70277320','78522819'];
echo "Campagne Juin — profil vs ventes historiques\n";
echo str_repeat('-', 80)."\n";

foreach ($tels as $tel) {
    $u = App\Models\User::with('agence')->where('telephone', $tel)->first();
    if (! $u) {
        continue;
    }
    echo "\n{$u->name} {$u->prenom} ({$tel})\n";
    echo "  Profil actuel : ".($u->agence?->nom ?? '?')."\n";
    $ventes = App\Models\Vente::with('agence')
        ->where('user_id', $u->id)
        ->where('campagne_id', $c->id)
        ->get();
    if ($ventes->isEmpty()) {
        echo "  Aucune vente Juin\n";
        continue;
    }
    foreach ($ventes->groupBy('agence_id') as $aid => $group) {
        $nom = $group->first()->agence?->nom ?? '?';
        echo "  Ventes figées : {$group->count()} x agence « {$nom} »\n";
    }
}
