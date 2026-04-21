<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\Client;
use App\Models\TypeCarte;
use App\Models\Vente;
use App\Services\SpreadsheetExportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VenteController extends Controller
{
    public function __construct(
        private SpreadsheetExportService $spreadsheetExportService
    ) {}

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

    public function exportExcel(Request $request): StreamedResponse
    {
        $query = Vente::with(['client', 'agence', 'user', 'typeCarte', 'campagne'])->latest();

        $user = $request->user();
        if ($user?->isCommercial()) {
            $query->where('user_id', $user->id);
        }

        $ventes = $query->get();

        $includeCommercial = $user && ($user->isAdmin() || $user->isDirection());
        $headers = ['Date', 'Campagne', 'Client', 'Téléphone', 'Type carte'];
        if ($includeCommercial) {
            array_push($headers, 'Commercial', 'Agence');
        }
        array_push($headers, 'Statut activation');

        $rows = $ventes->map(function (Vente $v) use ($includeCommercial) {
            $base = [
                $v->created_at->format('d/m/Y H:i'),
                $v->campagne?->nom ?? '—',
                $v->client ? trim($v->client->prenom.' '.$v->client->nom) : '—',
                $v->client->telephone ?? '',
                $v->typeCarte?->code ?? '—',
            ];
            if ($includeCommercial) {
                $base[] = $v->user ? ($v->user->prenom ? trim($v->user->prenom.' '.$v->user->name) : $v->user->name) : '';
                $base[] = $v->agence->nom ?? '';
            }
            $base[] = $v->statut_activation ?? '';

            return $base;
        })->all();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($this->spreadsheetExportService->sanitizeSheetTitle('Historique ventes'));
        $this->spreadsheetExportService->fillSheet($sheet, $headers, $rows);
        $fn = 'historique_ventes_'.now()->format('Y-m-d_His').'.xlsx';

        return $this->spreadsheetExportService->download($spreadsheet, $fn);
    }

    public function create(Request $request): View
    {
        Campagne::syncStatuts();
        $user = $request->user();
        $agenceId = $user->agence_id ? (int) $user->agence_id : null;
        $campagnesOuvertes = $agenceId ? Campagne::getActivesPourAgence($agenceId) : collect();
        $campagneActive = $campagnesOuvertes->first();
        $peutVendre = $agenceId && $campagnesOuvertes->isNotEmpty();

        $typesCartes = TypeCarte::actifs()->get();

        return view('commercial.ventes.create', compact('typesCartes', 'campagneActive', 'campagnesOuvertes', 'peutVendre'));
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
