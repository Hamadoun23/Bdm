<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\MouvementStock;
use App\Models\Stock;
use App\Models\TypeCarte;
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
        $agences = Agence::with(['stocks.typeCarte'])->get();
        $alertes = $this->stockAlertService->getAlertes();
        $typesCartes = TypeCarte::orderBy('code')->get();
        return view('admin.stocks.index', compact('agences', 'alertes', 'typesCartes'));
    }

    public function approvisionner(Request $request): RedirectResponse
    {
        $request->validate([
            'agence_id' => 'required|exists:agences,id',
            'type_carte_id' => 'required|exists:types_cartes,id',
            'quantite' => 'required|integer|min:1',
        ]);

        $stock = Stock::firstOrCreate(
            [
                'agence_id' => $request->agence_id,
                'type_carte_id' => $request->type_carte_id,
            ],
            ['quantite' => 0]
        );
        $stock->increment('quantite', $request->quantite);

        MouvementStock::create([
            'agence_id' => $request->agence_id,
            'type_carte_id' => $request->type_carte_id,
            'quantite' => $request->quantite,
            'type_mouvement' => 'entree',
        ]);

        return back()->with('success', 'Stock approvisionné.');
    }

    public function ajuster(Request $request): RedirectResponse
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantite' => 'required|integer',
        ]);

        $stock = Stock::with('typeCarte')->findOrFail($request->stock_id);
        $quantite = (int) $request->quantite;

        if ($quantite > 0) {
            $stock->increment('quantite', $quantite);
            MouvementStock::create([
                'agence_id' => $stock->agence_id,
                'type_carte_id' => $stock->type_carte_id,
                'quantite' => $quantite,
                'type_mouvement' => 'entree',
            ]);
        } elseif ($quantite < 0 && $stock->quantite >= abs($quantite)) {
            $stock->decrement('quantite', abs($quantite));
            MouvementStock::create([
                'agence_id' => $stock->agence_id,
                'type_carte_id' => $stock->type_carte_id,
                'quantite' => $quantite,
                'type_mouvement' => 'ajustement',
            ]);
        } else {
            return back()->with('error', 'Quantité insuffisante pour cette sortie.');
        }

        return back()->with('success', 'Stock mis à jour.');
    }

    public function mouvements(Request $request, ?int $agenceId = null): View
    {
        $query = MouvementStock::with(['agence', 'vente', 'typeCarte'])->latest();
        $agenceId = $agenceId ?? $request->query('agence');
        if ($agenceId) {
            $query->where('agence_id', $agenceId);
        }
        $mouvements = $query->paginate(20);
        $agences = Agence::all();
        return view('admin.stocks.mouvements', compact('mouvements', 'agences'));
    }
}
