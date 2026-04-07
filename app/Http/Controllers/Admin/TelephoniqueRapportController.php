<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\TelephoniqueRapport;
use App\Models\TypeCarte;
use App\Models\User;
use App\Services\CampagneRapportService;
use App\Services\SpreadsheetExportService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TelephoniqueRapportController extends Controller
{
    public function __construct(
        private SpreadsheetExportService $spreadsheetExportService,
        private CampagneRapportService $campagneRapportService
    ) {}

    public function index(Request $request): View
    {
        $baseQuery = $this->telephoniqueRapportsQueryFiltree($request);
        $totauxListe = $this->campagneRapportService->totauxTelephoniqueListe($baseQuery);

        $rapports = $baseQuery->clone()
            ->with(['user.agence', 'campagne'])
            ->orderByDesc('date_rapport')
            ->paginate(30)
            ->withQueryString();

        $telephoniques = User::query()
            ->where('role', 'commercial_telephonique')
            ->orderBy('name')
            ->get();
        $campagnes = Campagne::query()->orderByDesc('date_debut')->get(['id', 'nom', 'date_debut', 'date_fin']);

        return view('admin.telephonique-rapports.index', compact('rapports', 'telephoniques', 'campagnes', 'totauxListe'));
    }

    public function show(TelephoniqueRapport $telephoniqueRapport): View
    {
        $telephoniqueRapport->load(['user.agence', 'campagne']);
        $typesCartes = TypeCarte::query()->orderBy('code')->get()->keyBy('id');

        return view('admin.telephonique-rapports.show', compact('telephoniqueRapport', 'typesCartes'));
    }

    public function export(Request $request): StreamedResponse
    {
        $rapports = $this->telephoniqueRapportsQueryFiltree($request)
            ->with(['user.agence', 'campagne'])
            ->orderByDesc('date_rapport')
            ->get();

        $hdr = [
            'Date', 'Campagne', 'Collaborateur', 'Agence', 'Appels émis', 'Joignables', 'Non joignables',
            'Taux joign. %', 'Intéressés (nb)', 'Intéressés %', 'Déjà servis (nb)', 'Déjà servis %',
            'NJ répondeur', 'NJ n° erroné', 'NJ hors réseau', 'NJ autres nb', 'NJ autres précision',
            'Cartes proposées (résumé)', 'Cohérence NJ',
        ];
        $rows = $rapports->map(fn ($r) => [
            $r->date_rapport->format('d/m/Y'),
            $r->campagne?->nom ?? '—',
            $r->user?->prenom ? trim($r->user->prenom.' '.$r->user->name) : ($r->user?->name ?? ''),
            $r->user?->agence?->nom ?? '',
            $r->appels_emis,
            $r->appels_joignables,
            $r->appels_non_joignables,
            $r->taux_joignabilite !== null ? round((float) $r->taux_joignabilite, 2) : '',
            $r->clients_interesses_nombre,
            $r->clients_interesses_pct !== null ? round((float) $r->clients_interesses_pct, 2) : ($r->pctInteressesCalcule() ?? ''),
            $r->clients_deja_servis_nombre,
            $r->clients_deja_servis_pct !== null ? round((float) $r->clients_deja_servis_pct, 2) : ($r->pctDejaServisCalcule() ?? ''),
            $r->nj_repondeur,
            $r->nj_numero_errone,
            $r->nj_hors_reseau,
            $r->nj_autres_nombre,
            $r->nj_autres_precision ?? '',
            $r->resumeCartesProposees(),
            $r->njAnalyseCoherente() ? 'OK' : 'Écart',
        ])->all();

        $totaux = [
            'TOTAUX', $rapports->count().' fiche(s)', '', '',
            $rapports->sum('appels_emis'),
            $rapports->sum('appels_joignables'),
            $rapports->sum('appels_non_joignables'),
            '', $rapports->sum('clients_interesses_nombre'), '',
            $rapports->sum('clients_deja_servis_nombre'), '',
            $rapports->sum('nj_repondeur'),
            $rapports->sum('nj_numero_errone'),
            $rapports->sum('nj_hors_reseau'),
            $rapports->sum('nj_autres_nombre'),
            '',
            '',
            '',
        ];

        $format = strtolower((string) $request->query('format', 'csv'));
        if ($format === 'xlsx') {
            $meta = $this->telephoniqueExportMetaLines($request);
            $spreadsheet = new Spreadsheet;
            $sheet = $spreadsheet->getActiveSheet();
            $this->spreadsheetExportService->fillStructuredTable(
                $sheet,
                'Reporting téléphonique — export structuré',
                $meta,
                $hdr,
                $rows,
                $totaux
            );
            $sheet->setTitle($this->spreadsheetExportService->sanitizeSheetTitle('Reporting téléphonique'));
            $fn = 'reporting_telephonique_'.now()->format('Y-m-d_His').'.xlsx';

            return $this->spreadsheetExportService->download($spreadsheet, $fn);
        }

        $filename = 'reporting_telephonique_'.now()->format('Y-m-d_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];
        $csvMeta = $this->telephoniqueExportMetaLines($request);

        return Response::stream(function () use ($rapports, $csvMeta, $hdr, $totaux) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Reporting téléphonique — BDM'], ';');
            foreach ($csvMeta as $line) {
                fputcsv($file, [$line], ';');
            }
            fputcsv($file, [], ';');
            fputcsv($file, $hdr, ';');
            foreach ($rapports as $r) {
                fputcsv($file, [
                    $r->date_rapport->format('d/m/Y'),
                    $r->campagne?->nom ?? '—',
                    $r->user?->prenom ? trim($r->user->prenom.' '.$r->user->name) : ($r->user?->name ?? ''),
                    $r->user?->agence?->nom ?? '',
                    $r->appels_emis,
                    $r->appels_joignables,
                    $r->appels_non_joignables,
                    $r->taux_joignabilite !== null ? number_format((float) $r->taux_joignabilite, 2, ',', '') : '',
                    $r->clients_interesses_nombre,
                    $r->clients_interesses_pct !== null ? number_format((float) $r->clients_interesses_pct, 2, ',', '') : ($r->pctInteressesCalcule() !== null ? number_format($r->pctInteressesCalcule(), 2, ',', '') : ''),
                    $r->clients_deja_servis_nombre,
                    $r->clients_deja_servis_pct !== null ? number_format((float) $r->clients_deja_servis_pct, 2, ',', '') : ($r->pctDejaServisCalcule() !== null ? number_format($r->pctDejaServisCalcule(), 2, ',', '') : ''),
                    $r->nj_repondeur,
                    $r->nj_numero_errone,
                    $r->nj_hors_reseau,
                    $r->nj_autres_nombre,
                    $r->nj_autres_precision ?? '',
                    $r->resumeCartesProposees(),
                    $r->njAnalyseCoherente() ? 'OK' : 'Écart',
                ], ';');
            }
            fputcsv($file, [], ';');
            fputcsv($file, $totaux, ';');
            fclose($file);
        }, 200, $headers);
    }

    /**
     * @return array<int, string>
     */
    private function telephoniqueExportMetaLines(Request $request): array
    {
        $lines = [
            'Généré le '.now()->locale('fr')->translatedFormat('d F Y').' à '.now()->format('H:i'),
        ];

        if ($request->filled('campagne_id')) {
            $campagne = Campagne::query()->find((int) $request->campagne_id);
            if ($campagne) {
                $lines[] = 'Campagne : '.$campagne->nom.' — '.$campagne->date_debut->format('d/m/Y').' → '.$campagne->date_fin->format('d/m/Y');
            }
        }

        if ($request->filled('date_debut') || $request->filled('date_fin')) {
            $du = $request->date('date_debut')?->format('d/m/Y') ?? '—';
            $au = $request->date('date_fin')?->format('d/m/Y') ?? '—';
            $lines[] = 'Filtre dates fiches : '.$du.' → '.$au;
        }

        if ($request->filled('user_id')) {
            $u = User::query()->find((int) $request->user_id);
            if ($u) {
                $lines[] = 'Téléopératrice : '.($u->prenom ? trim($u->prenom.' '.$u->name) : $u->name);
            }
        }

        return $lines;
    }

    /**
     * @return Builder<TelephoniqueRapport>
     */
    private function telephoniqueRapportsQueryFiltree(Request $request): Builder
    {
        if ($request->filled('campagne_id')) {
            $campagne = Campagne::find((int) $request->campagne_id);
            if (! $campagne) {
                return TelephoniqueRapport::query()->whereRaw('0 = 1');
            }
            $du = $request->date('date_debut')?->copy()->startOfDay() ?? $campagne->date_debut->copy()->startOfDay();
            $fin = $request->date('date_fin')?->copy()->endOfDay() ?? $campagne->date_fin->copy()->endOfDay();

            return $this->campagneRapportService->telephoniqueRapportsPourCampagneQuery(
                $campagne,
                $du,
                $fin,
                null,
                $request->filled('user_id') ? (int) $request->user_id : null
            );
        }

        $query = TelephoniqueRapport::query();
        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->user_id);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('date_rapport', '>=', $request->date('date_debut'));
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_rapport', '<=', $request->date('date_fin'));
        }

        return $query;
    }
}
