<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$c = App\Models\Campagne::where('nom', 'like', '%Juin%')->first();
if (! $c) {
    echo "No Juin campaign\n";
    exit(1);
}
echo "Campagne: {$c->id} {$c->nom} {$c->date_debut} -> {$c->date_fin}\n\n";
foreach ($c->signatairesContrat()->with('agence')->orderBy('name')->get() as $u) {
    echo "{$u->telephone}|{$u->name} {$u->prenom}|".($u->agence?->nom ?? 'NULL')."\n";
}
