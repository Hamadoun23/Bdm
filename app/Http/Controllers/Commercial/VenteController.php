<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\TypeCarte;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VenteController extends Controller
{
    public function index(Request $request): View
    {
        $query = Vente::with(['client', 'agence', 'user', 'typeCarte']);

        $user = $request->user();
        if ($user) {
            if ($user->isCommercial()) {
                $query->where('user_id', $user->id);
            } elseif ($user->isChefAgence() && $user->agence_id) {
                $query->where('agence_id', $user->agence_id);
            }
        }

        $ventes = $query->with('typeCarte')->latest()->paginate(15);

        return view('commercial.ventes.index', compact('ventes'));
    }

    public function create(): View
    {
        $typesCartes = TypeCarte::actifs()->get();
        return view('commercial.ventes.create', compact('typesCartes'));
    }
}
