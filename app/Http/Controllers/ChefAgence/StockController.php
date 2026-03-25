<?php

namespace App\Http\Controllers\ChefAgence;

use App\Http\Controllers\Controller;
use App\Models\MouvementStock;
use App\Models\Stock;
use App\Services\StockAlertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function __construct(
        private StockAlertService $stockAlertService
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $agenceId = $user?->agence_id;

        if (!$agenceId) {
            return view('chef_agence.stocks.index', [
                'stocks' => collect(),
                'alertes' => collect(),
                'agence' => null,
                'typesDisponibles' => collect(),
            ]);
        }

        $stocks = Stock::with('typeCarte')->where('agence_id', $agenceId)->get();
        $typesDisponibles = \App\Models\TypeCarte::actifs()->get();
        $alertes = $this->stockAlertService->getAlertes()
            ->where('agence_id', $agenceId);
        $agence = $user->agence;

        return view('chef_agence.stocks.index', compact('stocks', 'alertes', 'agence', 'typesDisponibles'));
    }

    public function approvisionner(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (!$user?->isChefAgence() || !$user->agence_id) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'type_carte_id' => 'required|exists:types_cartes,id',
            'quantite' => 'required|integer|min:1',
        ]);

        $stock = Stock::firstOrCreate(
            ['agence_id' => $user->agence_id, 'type_carte_id' => $request->type_carte_id],
            ['quantite' => 0]
        );
        $stock->increment('quantite', $request->quantite);

        MouvementStock::create([
            'agence_id' => $user->agence_id,
            'type_carte_id' => $request->type_carte_id,
            'quantite' => $request->quantite,
            'type_mouvement' => 'entree',
        ]);

        return back()->with('success', 'Stock mis à jour.');
    }

    public function ajuster(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (!$user?->isChefAgence() || !$user->agence_id) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantite' => 'required|integer',
        ]);

        $stock = Stock::where('id', $request->stock_id)
            ->where('agence_id', $user->agence_id)
            ->firstOrFail();

        $quantite = (int) $request->quantite;
        if ($quantite > 0) {
            $stock->increment('quantite', $quantite);
            MouvementStock::create([
                'agence_id' => $user->agence_id,
                'type_carte_id' => $stock->type_carte_id,
                'quantite' => $quantite,
                'type_mouvement' => 'entree',
            ]);
        } elseif ($quantite < 0 && $stock->quantite >= abs($quantite)) {
            $stock->decrement('quantite', abs($quantite));
            MouvementStock::create([
                'agence_id' => $user->agence_id,
                'type_carte_id' => $stock->type_carte_id,
                'quantite' => $quantite,
                'type_mouvement' => 'ajustement',
            ]);
        } else {
            return back()->with('error', 'Quantité insuffisante pour cette sortie.');
        }

        return back()->with('success', 'Stock mis à jour.');
    }
}
