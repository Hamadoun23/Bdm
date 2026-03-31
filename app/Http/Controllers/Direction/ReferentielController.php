<?php

namespace App\Http\Controllers\Direction;

use App\Http\Controllers\Controller;
use App\Models\TypeCarte;
use Illuminate\View\View;

class ReferentielController extends Controller
{
    public function typesCartes(): View
    {
        $typesCartes = TypeCarte::orderBy('code')->get();

        return view('direction.referentiel.types-cartes', compact('typesCartes'));
    }
}
