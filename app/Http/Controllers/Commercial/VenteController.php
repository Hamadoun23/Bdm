<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\Client;
use App\Models\TypeCarte;
use App\Models\Vente;
use App\Services\CampagneStatsScope;
use App\Services\SpreadsheetExportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VenteController extends Controller
{
    public function __construct(
        private SpreadsheetExportService $spreadsheetExportService
    ) {}

    public function index(Request $request): Response
    {
        $query = Vente::with(['client', 'agence', 'user', 'typeCarte', 'campagne']);

        $user = $request->user();
        $agenceId = null;
        if ($user) {
            if ($user->isCommercial()) {
                $query->where('user_id', $user->id);
                $agenceId = $user->agence_id ? (int) $user->agence_id : null;
            } elseif ($user->isDirection()) {
                // toutes les ventes (lecture), filtrées par campagne de référence
            } elseif ($user->isAdmin()) {
                // idem direction
            }
        }

        CampagneStatsScope::appliquerSurVentes($query, $agenceId);

        $ventes = $query->with('typeCarte')->latest()->paginate(15);
        $libelleStatsCampagne = CampagneStatsScope::libelle($agenceId, Campagne::TYPE_VENTE_CARTE);

        $canManage = (bool) $user?->isCommercial();
        $canSeeCommercial = (bool) ($user?->isAdmin() || $user?->isDirection());

        return Inertia::render('Ventes/Index', [
            'libelleStatsCampagne' => $libelleStatsCampagne,
            'canManage' => $canManage,
            'canSeeCommercial' => $canSeeCommercial,
            'ventes' => [
                'data' => $ventes->getCollection()->map(fn (Vente $v) => [
                    'id' => $v->id,
                    'date' => $v->created_at->format('d/m/Y H:i'),
                    'client_nom' => trim($v->client->prenom.' '.$v->client->nom),
                    'type_carte' => $v->typeCarte?->code ?? '?',
                    'commercial' => $v->user->name ?? '-',
                    'agence' => $v->agence->nom ?? '-',
                    'peut_modifier_client' => $v->client?->peutEtreModifieOuSupprimeParCommercial() ?? false,
                    'peut_supprimer' => $v->peutEtreSupprimeeParCommercial(),
                    'client_id' => $v->client_id,
                ])->values(),
                'links' => $ventes->linkCollection(),
                'from' => $ventes->firstItem(),
                'to' => $ventes->lastItem(),
                'total' => $ventes->total(),
            ],
        ]);
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $query = Vente::with(['client', 'agence', 'user', 'typeCarte', 'campagne'])->latest();

        $user = $request->user();
        $agenceId = null;
        if ($user?->isCommercial()) {
            $query->where('user_id', $user->id);
            $agenceId = $user->agence_id ? (int) $user->agence_id : null;
        }

        CampagneStatsScope::appliquerSurVentes($query, $agenceId);

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

    public function create(Request $request): Response
    {
        Campagne::syncStatuts();
        $user = $request->user();
        $agenceId = $user->agence_id ? (int) $user->agence_id : null;
        $campagnesOuvertes = $agenceId
            ? Campagne::getActivesPourAgence($agenceId)
                ->where('type', Campagne::TYPE_VENTE_CARTE)
                ->filter(fn (Campagne $c) => $c->estEngageCommercial($user->id))
                ->values()
            : collect();
        $campagneActive = $campagnesOuvertes->first();
        $peutVendre = $agenceId && $campagnesOuvertes->isNotEmpty();
        $contratAccepte = $campagnesOuvertes->contains(fn (Campagne $c) => $c->commercialAAccepteContrat($user->id));

        $typesCartes = TypeCarte::actifs()->get();

        return Inertia::render('Ventes/Create', [
            'typesCartes' => $typesCartes->map(fn (TypeCarte $t) => ['id' => $t->id, 'code' => $t->code])->values(),
            'campagnesOuvertes' => $campagnesOuvertes->map(fn (Campagne $c) => [
                'id' => $c->id,
                'nom' => $c->nom,
                'date_fin' => $c->date_fin->format('d/m/Y'),
            ])->values(),
            'peutVendre' => $peutVendre,
            'contratAccepte' => $contratAccepte,
        ]);
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
