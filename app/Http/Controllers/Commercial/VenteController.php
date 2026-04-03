<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\Client;
use App\Models\MouvementStock;
use App\Models\Stock;
use App\Models\TypeCarte;
use App\Models\Vente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            } elseif ($user->isDirection()) {
                // toutes les ventes (lecture)
            }
        }

        $ventes = $query->with('typeCarte')->latest()->paginate(15);

        return view('commercial.ventes.index', compact('ventes'));
    }

    public function create(Request $request): View
    {
        Campagne::syncStatuts();
        $user = $request->user();
        $agenceId = $user->agence_id ? (int) $user->agence_id : null;
        $campagneActive = $agenceId ? Campagne::getActiveForAgence($agenceId) : null;
        $peutVendre = $agenceId && $campagneActive && $campagneActive->estOuverteAuxVentes($agenceId);

        $typesCartes = TypeCarte::actifs()->get();

        return view('commercial.ventes.create', compact('typesCartes', 'campagneActive', 'peutVendre'));
    }

    public function destroy(Request $request, Vente $vente): RedirectResponse
    {
        $user = $request->user();
        if (! $user?->isCommercial() || (int) $vente->user_id !== (int) $user->id) {
            abort(403);
        }

        if (! $vente->peutEtreSupprimeeParCommercial()) {
            return redirect()
                ->route('ventes.index')
                ->with('error', 'Suppression impossible : plus de '.Vente::DELAI_SUPPRESSION_COMMERCIAL_HEURES.' h se sont écoulées depuis l’enregistrement de cette vente.');
        }

        DB::transaction(function () use ($vente) {
            $mouvement = MouvementStock::query()->where('vente_id', $vente->id)->first();
            if ($mouvement) {
                $stock = Stock::query()
                    ->where('agence_id', $mouvement->agence_id)
                    ->where('type_carte_id', $mouvement->type_carte_id)
                    ->first();
                if ($stock) {
                    $stock->increment('quantite', abs((int) $mouvement->quantite));
                }
                $mouvement->delete();
            }

            $client = Client::query()->find($vente->client_id);
            $vente->delete();

            if ($client) {
                if ($client->carte_identite) {
                    Storage::disk('public')->delete($client->carte_identite);
                }
                $client->delete();
            }
        });

        return redirect()->route('ventes.index')->with('success', 'Vente supprimée.');
    }
}
