<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Agence;
use App\Models\Campagne;
use App\Models\ContratPrestationReponse;
use App\Models\User;
use App\Models\Vente;

$campagne = Campagne::where('nom', 'like', '%Juin%')->first();
if (! $campagne) {
    echo "Campagne Juin introuvable\n";
    exit(1);
}

echo "Campagne: {$campagne->id} {$campagne->nom} {$campagne->date_debut} -> {$campagne->date_fin} statut={$campagne->statut} actif=".($campagne->actif ? '1' : '0')."\n\n";

$targets = [
    ['tel' => '66986621', 'agence' => 'BS'],
    ['tel' => '70277320', 'agence' => 'Kalaban coura'],
];

foreach ($targets as $t) {
    $u = User::with('agence')->where('telephone', $t['tel'])->first();
    $a = Agence::whereRaw('LOWER(TRIM(nom)) = ?', [mb_strtolower($t['agence'])])
        ->orWhereRaw('LOWER(TRIM(REPLACE(nom, "-", " "))) = ?', [mb_strtolower(str_replace('-', ' ', $t['agence']))])
        ->first();
    echo "User {$t['tel']}: ".($u ? $u->name.' actif='.($u->actif?'1':'0').' agence='.($u->agence?->nom ?? '?') : 'NOT FOUND')."\n";
    echo "Agence {$t['agence']}: ".($a ? "id={$a->id} {$a->nom}" : 'NOT FOUND')."\n";
    if ($u && $campagne->signatairesContrat()->where('users.id', $u->id)->exists()) {
        echo "  -> signataire Juin: OUI\n";
    } else {
        echo "  -> signataire Juin: NON\n";
    }
    if ($a && $campagne->agences()->where('agences.id', $a->id)->exists()) {
        echo "  -> agence dans campagne: OUI\n";
    } else {
        echo "  -> agence dans campagne: NON\n";
    }
    echo "\n";
}
