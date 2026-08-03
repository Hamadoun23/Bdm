<?php

namespace App\Http\Controllers\Direction;

use App\Http\Controllers\Controller;
use App\Models\TypeCarte;
use Inertia\Inertia;
use Inertia\Response;

class ReferentielController extends Controller
{
    public function typesCartes(): Response
    {
        $typesCartes = TypeCarte::orderBy('code')->get();

        return Inertia::render('Direction/Referentiel/TypesCartes', [
            'typesCartes' => $typesCartes->pluck('code')->values(),
        ]);
    }
}
