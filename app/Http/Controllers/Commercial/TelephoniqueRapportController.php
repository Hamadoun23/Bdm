<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\TelephoniqueRapport;
use App\Models\TypeCarte;
use App\Services\CampagneRapportService;
use App\Services\SpreadsheetExportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
        $base = TelephoniqueRapport::query()->where('user_id', $request->user()->id);
        $totauxListe = $this->campagneRapportService->totauxTelephoniqueListe($base);

        $rapports = $base->clone()
            ->orderByDesc('date_rapport')
            ->paginate(20);

        return view('commercial.telephonique.index', compact('rapports', 'totauxListe'));
    }

    public function create(Request $request): View
    {
        Campagne::syncStatuts();
        $user = $request->user();
        $date = $request->date('date')?->format('Y-m-d') ?? now()->format('Y-m-d');
        $rapport = TelephoniqueRapport::where('user_id', $user->id)
            ->whereDate('date_rapport', $date)
            ->first();

        $campagneActive = $user->agence_id ? Campagne::getActiveForAgence((int) $user->agence_id) : null;
        $campagneActive?->loadMissing('typesCartesRemise');
        $typesCampagne = $campagneActive
            ? $campagneActive->typesCartesPourReportingTelephonique()
            : collect();

        return view('commercial.telephonique.form', [
            'rapport' => $rapport,
            'dateRapport' => $date,
            'campagneActive' => $campagneActive,
            'typesCampagne' => $typesCampagne,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Campagne::syncStatuts();
        $user = $request->user();
        $campagneActive = $user->agence_id ? Campagne::getActiveForAgence((int) $user->agence_id) : null;
        $typesCampagne = $campagneActive
            ? $campagneActive->typesCartesPourReportingTelephonique()
            : collect();

        $data = $this->validated($request, $typesCampagne);
        $data['user_id'] = $user->id;

        $existing = TelephoniqueRapport::query()
            ->where('user_id', $user->id)
            ->whereDate('date_rapport', $data['date_rapport'])
            ->first();
        if ($existing && ! $existing->peutEtreModifieOuSupprime()) {
            throw ValidationException::withMessages([
                'date_rapport' => 'Cette fiche ne peut plus être modifiée : délai de 48 h dépassé depuis l’enregistrement.',
            ]);
        }

        TelephoniqueRapport::updateOrCreate(
            [
                'user_id' => $user->id,
                'date_rapport' => $data['date_rapport'],
            ],
            $data
        );

        return redirect()->route('commercial.telephonique.index')->with('success', 'Fiche enregistrée.');
    }

    public function destroy(Request $request, TelephoniqueRapport $telephoniqueRapport): RedirectResponse
    {
        if ($telephoniqueRapport->user_id !== $request->user()->id) {
            abort(403);
        }

        if (! $telephoniqueRapport->peutEtreModifieOuSupprime()) {
            return redirect()
                ->route('commercial.telephonique.index')
                ->with('error', 'Suppression impossible : délai de 48 h dépassé depuis l’enregistrement de cette fiche.');
        }

        $telephoniqueRapport->delete();

        return redirect()->route('commercial.telephonique.index')->with('success', 'Fiche supprimée.');
    }

    /**
     * @param  Collection<int, TypeCarte>  $typesCampagne
     * @return array<string, mixed>
     */
    private function validated(Request $request, $typesCampagne): array
    {
        $typeRules = [];
        foreach ($typesCampagne as $tc) {
            $typeRules['propose.'.$tc->id] = ['required', 'integer', 'min:0'];
        }

        $autresNb = (int) $request->input('nj_autres_nombre');
        $precisionRules = [
            Rule::requiredIf($autresNb > 0),
            'nullable',
            'string',
            'max:500',
        ];

        $request->validate(array_merge([
            'date_rapport' => 'required|date',
            'appels_emis' => 'required|integer|min:0',
            'appels_joignables' => 'required|integer|min:0',
            'clients_interesses_nombre' => 'required|integer|min:0',
            'clients_deja_servis_nombre' => 'required|integer|min:0',
            'nj_repondeur' => 'required|integer|min:0',
            'nj_numero_errone' => 'required|integer|min:0',
            'nj_hors_reseau' => 'required|integer|min:0',
            'nj_autres_nombre' => 'required|integer|min:0',
            'nj_autres_precision' => $precisionRules,
        ], $typeRules));

        if ($autresNb > 0 && trim((string) $request->input('nj_autres_precision', '')) === '') {
            throw ValidationException::withMessages([
                'nj_autres_precision' => 'Précisez le motif « autres » lorsque le nombre est supérieur à 0.',
            ]);
        }

        $emis = (int) $request->input('appels_emis');
        $joignables = (int) $request->input('appels_joignables');
        if ($joignables > $emis) {
            throw ValidationException::withMessages([
                'appels_joignables' => 'Le nombre de joignables ne peut pas dépasser les appels émis.',
            ]);
        }

        $nonJ = max(0, $emis - $joignables);
        $taux = $emis > 0 ? round($joignables / $emis * 100, 2) : null;

        $njAnalyseSomme = (int) $request->input('nj_repondeur')
            + (int) $request->input('nj_numero_errone')
            + (int) $request->input('nj_hors_reseau')
            + (int) $request->input('nj_autres_nombre');
        if ($njAnalyseSomme > $nonJ) {
            throw ValidationException::withMessages([
                'nj_analyse' => 'La somme (répondeur + n° erroné + hors réseau + autres) ne peut pas dépasser le nombre de non joignables (appels émis − joignables), soit '.$nonJ.' pour cette fiche.',
            ]);
        }

        $allowedIds = $typesCampagne->pluck('id')->map(fn ($id) => (int) $id)->all();
        $cartesProposees = [];
        foreach ($allowedIds as $tid) {
            $cartesProposees[(string) $tid] = (int) $request->input('propose.'.$tid, 0);
        }

        $agenceId = $request->user()->agence_id ? (int) $request->user()->agence_id : null;
        $campagneFiche = Campagne::pourFicheTelephonique($agenceId, $request->date('date_rapport'));

        return [
            'campagne_id' => $campagneFiche?->id,
            'date_rapport' => $request->date('date_rapport')->format('Y-m-d'),
            'appels_emis' => $emis,
            'appels_joignables' => $joignables,
            'appels_non_joignables' => $nonJ,
            'taux_joignabilite' => $taux,
            'clients_interesses_nombre' => (int) $request->input('clients_interesses_nombre'),
            'clients_interesses_pct' => null,
            'clients_deja_servis_nombre' => (int) $request->input('clients_deja_servis_nombre'),
            'clients_deja_servis_pct' => null,
            'cartes_proposees' => $cartesProposees,
            'propose_visa' => 0,
            'propose_gim' => 0,
            'propose_cauris' => 0,
            'propose_prepayee' => 0,
            'nj_repondeur' => (int) $request->input('nj_repondeur'),
            'nj_numero_errone' => (int) $request->input('nj_numero_errone'),
            'nj_hors_reseau' => (int) $request->input('nj_hors_reseau'),
            'nj_autres_nombre' => (int) $request->input('nj_autres_nombre'),
            'nj_autres_precision' => $autresNb > 0 ? trim((string) $request->input('nj_autres_precision')) : null,
        ];
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $request->user()->loadMissing('agence');
        $rapports = TelephoniqueRapport::query()
            ->with(['user.agence', 'campagne'])
            ->where('user_id', $request->user()->id)
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

        $user = $request->user();
        $nomCollaborateur = $user->prenom ? trim($user->prenom.' '.$user->name) : $user->name;
        $meta = [
            'Généré le '.now()->locale('fr')->translatedFormat('d F Y').' à '.now()->format('H:i'),
            'Collaborateur : '.$nomCollaborateur.($user->agence?->nom ? ' — '.$user->agence->nom : ''),
        ];

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

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $this->spreadsheetExportService->fillStructuredTable(
            $sheet,
            'Mes fiches — reporting téléphonique',
            $meta,
            $hdr,
            $rows,
            $totaux
        );
        $sheet->setTitle($this->spreadsheetExportService->sanitizeSheetTitle('Mes fiches'));
        $fn = 'mes_fiches_telephonique_'.now()->format('Y-m-d_His').'.xlsx';

        return $this->spreadsheetExportService->download($spreadsheet, $fn);
    }
}
