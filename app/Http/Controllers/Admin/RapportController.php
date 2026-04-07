<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Campagne;
use App\Models\Client;
use App\Models\TelephoniqueRapport;
use App\Models\TypeCarte;
use App\Models\User;
use App\Models\Vente;
use App\Services\CampagneRapportService;
use App\Services\SpreadsheetExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RapportController extends Controller
{
    public function __construct(
        private CampagneRapportService $campagneRapportService,
        private SpreadsheetExportService $spreadsheetExportService
    ) {}

    public function index(Request $request): View
    {
        Campagne::syncStatuts();
        /** @var User $user */
        $user = $request->user();

        $campagnes = Campagne::query()->orderByDesc('date_debut')->orderByDesc('id')->get();

        foreach ($campagnes as $campagne) {
            $campagne->setAttribute('nb_ventes_rapport', $campagne->ventes()->count());
        }

        return view('rapports.index', compact('campagnes', 'user'));
    }

    public function campagneVentes(Request $request, Campagne $campagne): View
    {
        $this->assertUserCanAccessCampagne($request->user(), $campagne);

        [$dateDebut, $dateFin, $filtreAgenceId, $filtreUserId] = $this->parseFiltresSyntheseCampagne($request, $campagne);
        $filtreTypeCarteId = $this->parseFiltreTypeCarteId($request);

        $base = $this->campagneRapportService->ventesFiltreesQuery(
            $campagne->id,
            $dateDebut,
            $dateFin,
            $filtreAgenceId,
            $filtreUserId,
            $filtreTypeCarteId
        );
        $resumeListe = [
            'count' => (clone $base)->count(),
            'montant' => (int) (clone $base)->sum('montant'),
        ];

        $ventes = (clone $base)
            ->with(['client', 'user', 'agence', 'typeCarte', 'campagne'])
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        $commerciauxChoix = $this->campagneRapportService->usersPerimetreQuery($campagne)
            ->orderBy('name')
            ->get();
        $agencesChoix = $campagne->toutes_agences
            ? Agence::query()->orderBy('nom')->get()
            : $campagne->agences()->orderBy('nom')->get();
        $typesChoix = TypeCarte::query()->orderBy('code')->get();

        return view('rapports.campagne-ventes', compact(
            'campagne',
            'ventes',
            'dateDebut',
            'dateFin',
            'filtreAgenceId',
            'filtreUserId',
            'filtreTypeCarteId',
            'resumeListe',
            'commerciauxChoix',
            'agencesChoix',
            'typesChoix'
        ));
    }

    public function campagneClients(Request $request, Campagne $campagne): View
    {
        $this->assertUserCanAccessCampagne($request->user(), $campagne);

        $clientIds = Vente::query()
            ->where('campagne_id', $campagne->id)
            ->distinct()
            ->pluck('client_id')
            ->filter()
            ->values();

        $clients = Client::query()
            ->with(['user.agence', 'typeCarte'])
            ->whereIn('id', $clientIds)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view('rapports.campagne-clients', compact('campagne', 'clients'));
    }

    public function campagneSynthese(Request $request, Campagne $campagne): View
    {
        $this->assertUserCanAccessCampagne($request->user(), $campagne);
        Campagne::syncStatuts();

        [$dateDebut, $dateFin, $filtreAgenceId, $filtreUserId] = $this->parseFiltresSyntheseCampagne($request, $campagne);

        $synthese = $this->campagneRapportService->synthese($campagne, $dateDebut, $dateFin, $filtreAgenceId, $filtreUserId);
        $telephonique = $this->campagneRapportService->agregatsTelephonique($campagne, $dateDebut, $dateFin, $filtreAgenceId, $filtreUserId);

        $commerciauxChoix = $this->campagneRapportService->usersPerimetreQuery($campagne)
            ->orderBy('name')
            ->get();

        $agencesChoix = $campagne->toutes_agences
            ? Agence::query()->orderBy('nom')->get()
            : $campagne->agences()->orderBy('nom')->get();

        return view('rapports.campagne-synthese', compact(
            'campagne',
            'synthese',
            'telephonique',
            'dateDebut',
            'dateFin',
            'filtreAgenceId',
            'filtreUserId',
            'commerciauxChoix',
            'agencesChoix'
        ));
    }

    /**
     * Liste paginée des fiches téléphonique rattachées à la campagne (admin & direction).
     */
    public function campagneReportingTelephonique(Request $request, Campagne $campagne): View
    {
        $this->assertUserCanAccessCampagne($request->user(), $campagne);
        Campagne::syncStatuts();

        [$dateDebut, $dateFin] = $this->parseDatesReportingTelCampagne($request, $campagne);

        $filtreAgenceId = $request->filled('agence_id') ? (int) $request->agence_id : null;
        $filtreUserId = $request->filled('user_id') ? (int) $request->user_id : null;

        $base = $this->campagneRapportService->telephoniqueRapportsPourCampagneQuery(
            $campagne,
            $dateDebut,
            $dateFin,
            $filtreAgenceId,
            $filtreUserId
        );

        $rapports = (clone $base)
            ->with(['user.agence', 'campagne'])
            ->orderByDesc('date_rapport')
            ->paginate(30)
            ->withQueryString();

        $agencesChoix = $campagne->toutes_agences
            ? Agence::query()->orderBy('nom')->get()
            : $campagne->agences()->orderBy('nom')->get();

        $campagne->loadMissing('agences');
        $telephoniques = User::query()
            ->where('role', 'commercial_telephonique')
            ->when(! $campagne->toutes_agences && $campagne->agences->isNotEmpty(), fn ($q) => $q->whereIn('agence_id', $campagne->agences->pluck('id')))
            ->orderBy('name')
            ->get();

        $agregats = $this->campagneRapportService->agregatsTelephonique(
            $campagne,
            $dateDebut,
            $dateFin,
            $filtreAgenceId,
            $filtreUserId
        );

        return view('rapports.campagne-reporting-telephonique', compact(
            'campagne',
            'rapports',
            'dateDebut',
            'dateFin',
            'agencesChoix',
            'telephoniques',
            'agregats',
            'filtreAgenceId',
            'filtreUserId'
        ));
    }

    public function campagneReportingTelephoniqueShow(Request $request, Campagne $campagne, TelephoniqueRapport $telephoniqueRapport): View
    {
        $this->assertUserCanAccessCampagne($request->user(), $campagne);
        $this->assertTelephoniqueRapportDansPerimetreCampagne($campagne, $telephoniqueRapport);

        $telephoniqueRapport->load(['user.agence', 'campagne']);
        $typesCartes = TypeCarte::query()->orderBy('code')->get()->keyBy('id');

        $retourListeCampagne = route('rapports.campagnes.reporting-telephonique', array_filter([
            'campagne' => $campagne->id,
            'date_debut' => $request->get('date_debut'),
            'date_fin' => $request->get('date_fin'),
            'user_id' => $request->get('user_id'),
            'agence_id' => $request->get('agence_id'),
        ], fn ($v) => $v !== null && $v !== ''));

        return view('admin.telephonique-rapports.show', compact('telephoniqueRapport', 'typesCartes', 'retourListeCampagne'));
    }

    public function exportCampagne(Request $request, Campagne $campagne): StreamedResponse
    {
        $this->assertUserCanAccessCampagne($request->user(), $campagne);

        $format = strtolower((string) $request->query('format', 'csv'));
        $section = $request->query('section', 'ventes');
        $allowedCsv = ['ventes', 'commerciaux', 'agences', 'types', 'semaines', 'mois'];
        $allowedXlsx = ['ventes', 'commerciaux', 'agences', 'types', 'semaines', 'mois', 'all'];
        if ($format === 'xlsx') {
            abort_unless(in_array($section, $allowedXlsx, true), 404);
        } else {
            abort_unless(in_array($section, $allowedCsv, true), 404);
        }

        [$dateDebut, $dateFin, $filtreAgenceId, $filtreUserId] = $this->parseFiltresSyntheseCampagne($request, $campagne);
        $filtreTypeCarteId = $this->parseFiltreTypeCarteId($request);

        if ($format === 'xlsx') {
            if ($section === 'all') {
                return $this->exportCampagneWorkbookXlsx(
                    $campagne,
                    $dateDebut,
                    $dateFin,
                    $filtreAgenceId,
                    $filtreUserId,
                    $filtreTypeCarteId
                );
            }

            return $this->exportCampagneSectionXlsx(
                $campagne,
                $section,
                $dateDebut,
                $dateFin,
                $filtreAgenceId,
                $filtreUserId,
                $filtreTypeCarteId
            );
        }

        $filename = 'rapport_campagne_'.$campagne->id.'_'.$section.'_'.$dateDebut->format('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        if ($section === 'ventes') {
            $ventes = $this->campagneRapportService
                ->ventesFiltreesQuery($campagne->id, $dateDebut, $dateFin, $filtreAgenceId, $filtreUserId, $filtreTypeCarteId)
                ->with(['client', 'user', 'agence', 'typeCarte', 'campagne'])
                ->orderBy('created_at')
                ->get();

            return Response::stream(function () use ($ventes) {
                $file = fopen('php://output', 'w');
                fwrite($file, "\xEF\xBB\xBF");
                fputcsv($file, ['Date', 'Campagne', 'Client', 'Téléphone', 'Type carte', 'Montant', 'Commercial', 'Agence', 'Statut'], ';');
                foreach ($ventes as $v) {
                    fputcsv($file, [
                        $v->created_at->format('d/m/Y H:i'),
                        $v->campagne?->nom ?? '-',
                        $v->client ? trim($v->client->prenom.' '.$v->client->nom) : '-',
                        $v->client->telephone ?? '',
                        $v->typeCarte?->code ?? '-',
                        $v->montant ?? '',
                        $v->user ? ($v->user->prenom ? trim($v->user->prenom.' '.$v->user->name) : $v->user->name) : '',
                        $v->agence->nom ?? '',
                        $v->statut_activation ?? '',
                    ], ';');
                }
                fclose($file);
            }, 200, $headers);
        }

        $synthese = $this->campagneRapportService->synthese($campagne, $dateDebut, $dateFin, $filtreAgenceId, $filtreUserId);

        return Response::stream(function () use ($section, $synthese) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            if ($section === 'commerciaux') {
                fputcsv($file, ['Rang', 'Commercial', 'Agence', 'Ventes', 'Montant FCFA'], ';');
                foreach ($synthese['commerciaux'] as $l) {
                    fputcsv($file, [$l['rang'], $l['user_name'], $l['agence_nom'] ?? '', $l['total_ventes'], $l['total_montant']], ';');
                }
            } elseif ($section === 'agences') {
                fputcsv($file, ['Agence', 'Ventes', 'Montant FCFA', 'Part % volume', 'Nb commericaux ratt.'], ';');
                foreach ($synthese['agences'] as $l) {
                    fputcsv($file, [$l['agence_nom'], $l['total_ventes'], $l['total_montant'], $l['pct_volume'], $l['nb_commerciaux']], ';');
                }
            } elseif ($section === 'types') {
                fputcsv($file, ['Type carte', 'Ventes', 'Montant FCFA', 'Part % volume'], ';');
                foreach ($synthese['par_type_carte'] as $l) {
                    fputcsv($file, [$l['code'], $l['total_ventes'], $l['total_montant'], $l['pct_volume']], ';');
                }
            } elseif ($section === 'semaines') {
                fputcsv($file, ['Période', 'Ventes', 'Montant FCFA'], ';');
                foreach ($synthese['par_semaine'] as $l) {
                    fputcsv($file, [$l['libelle'], $l['total_ventes'], $l['total_montant']], ';');
                }
            } elseif ($section === 'mois') {
                fputcsv($file, ['Mois', 'Ventes', 'Montant FCFA'], ';');
                foreach ($synthese['par_mois'] as $l) {
                    fputcsv($file, [$l['libelle'], $l['total_ventes'], $l['total_montant']], ';');
                }
            }
            fclose($file);
        }, 200, $headers);
    }

    private function exportCampagneSectionXlsx(
        Campagne $campagne,
        string $section,
        Carbon $dateDebut,
        Carbon $dateFin,
        ?int $filtreAgenceId,
        ?int $filtreUserId,
        ?int $filtreTypeCarteId
    ): StreamedResponse {
        $synthese = $this->campagneRapportService->synthese($campagne, $dateDebut, $dateFin, $filtreAgenceId, $filtreUserId);
        $baseName = 'rapport_campagne_'.$campagne->id.'_'.$section.'_'.$dateDebut->format('Y-m-d');

        if ($section === 'ventes') {
            $ventes = $this->campagneRapportService
                ->ventesFiltreesQuery($campagne->id, $dateDebut, $dateFin, $filtreAgenceId, $filtreUserId, $filtreTypeCarteId)
                ->with(['client', 'user', 'agence', 'typeCarte', 'campagne'])
                ->orderBy('created_at')
                ->get();
            $headers = ['Date', 'Campagne', 'Client', 'Téléphone', 'Type carte', 'Montant', 'Commercial', 'Agence', 'Statut'];
            $rows = $ventes->map(fn ($v) => [
                $v->created_at->format('d/m/Y H:i'),
                $v->campagne?->nom ?? '-',
                $v->client ? trim($v->client->prenom.' '.$v->client->nom) : '-',
                $v->client->telephone ?? '',
                $v->typeCarte?->code ?? '-',
                $v->montant ?? '',
                $v->user ? ($v->user->prenom ? trim($v->user->prenom.' '.$v->user->name) : $v->user->name) : '',
                $v->agence->nom ?? '',
                $v->statut_activation ?? '',
            ])->all();

            return $this->spreadsheetSingleSheet('Ventes détaillées', $headers, $rows, $baseName);
        }

        if ($section === 'commerciaux') {
            $headers = ['Rang', 'Commercial', 'Agence', 'Ventes', 'Montant FCFA'];
            $rows = $synthese['commerciaux']->map(fn ($l) => [
                $l['rang'], $l['user_name'], $l['agence_nom'] ?? '', $l['total_ventes'], $l['total_montant'],
            ])->all();

            return $this->spreadsheetSingleSheet('Commerciaux', $headers, $rows, $baseName);
        }

        if ($section === 'agences') {
            $headers = ['Agence', 'Ventes', 'Montant FCFA', 'Part % volume', 'Nb commerciaux rattachés'];
            $rows = $synthese['agences']->map(fn ($l) => [
                $l['agence_nom'], $l['total_ventes'], $l['total_montant'], $l['pct_volume'], $l['nb_commerciaux'],
            ])->all();

            return $this->spreadsheetSingleSheet('Agences', $headers, $rows, $baseName);
        }

        if ($section === 'types') {
            $headers = ['Type carte', 'Ventes', 'Montant FCFA', 'Part % volume'];
            $rows = $synthese['par_type_carte']->map(fn ($l) => [
                $l['code'], $l['total_ventes'], $l['total_montant'], $l['pct_volume'],
            ])->all();

            return $this->spreadsheetSingleSheet('Types de carte', $headers, $rows, $baseName);
        }

        if ($section === 'semaines') {
            $headers = ['Période', 'Ventes', 'Montant FCFA'];
            $rows = $synthese['par_semaine']->map(fn ($l) => [
                $l['libelle'], $l['total_ventes'], $l['total_montant'],
            ])->all();

            return $this->spreadsheetSingleSheet('Par semaine', $headers, $rows, $baseName);
        }

        $headers = ['Mois', 'Ventes', 'Montant FCFA'];
        $rows = $synthese['par_mois']->map(fn ($l) => [
            $l['libelle'], $l['total_ventes'], $l['total_montant'],
        ])->all();

        return $this->spreadsheetSingleSheet('Par mois', $headers, $rows, $baseName);
    }

    private function exportCampagneWorkbookXlsx(
        Campagne $campagne,
        Carbon $dateDebut,
        Carbon $dateFin,
        ?int $filtreAgenceId,
        ?int $filtreUserId,
        ?int $filtreTypeCarteId
    ): StreamedResponse {
        $synthese = $this->campagneRapportService->synthese($campagne, $dateDebut, $dateFin, $filtreAgenceId, $filtreUserId);
        $telepho = $this->campagneRapportService->agregatsTelephonique($campagne, $dateDebut, $dateFin, $filtreAgenceId, $filtreUserId);
        $ventes = $this->campagneRapportService
            ->ventesFiltreesQuery($campagne->id, $dateDebut, $dateFin, $filtreAgenceId, $filtreUserId, $filtreTypeCarteId)
            ->with(['client', 'user', 'agence', 'typeCarte', 'campagne'])
            ->orderBy('created_at')
            ->get();

        $clientsParVente = $ventes
            ->filter(fn ($v) => $v->client !== null)
            ->groupBy('client_id')
            ->map(fn ($group) => [
                'client' => $group->first()->client,
                'nb_ventes' => $group->count(),
                'montant' => $group->sum('montant'),
            ])
            ->sortBy(fn (array $x) => Str::lower($x['client']->nom.' '.$x['client']->prenom))
            ->values();

        $rapportsTel = $this->campagneRapportService
            ->telephoniqueRapportsPourCampagneQuery($campagne, $dateDebut, $dateFin, $filtreAgenceId, $filtreUserId)
            ->with(['user.agence', 'campagne'])
            ->orderByDesc('date_rapport')
            ->get();

        $hdrFichesTel = [
            'Date', 'Campagne', 'Collaborateur', 'Agence', 'Appels émis', 'Joignables', 'Non joignables',
            'Taux joign. %', 'Intéressés (nb)', 'Intéressés %', 'Déjà servis (nb)', 'Déjà servis %',
            'NJ répondeur', 'NJ n° erroné', 'NJ hors réseau', 'NJ autres nb', 'NJ autres précision',
            'Cartes proposées (résumé)', 'Cohérence NJ',
        ];
        $rowsFichesTel = $rapportsTel->map(fn ($r) => [
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
        $totauxFichesTel = [
            'TOTAUX', $rapportsTel->count().' fiche(s)', '', '',
            $rapportsTel->sum('appels_emis'),
            $rapportsTel->sum('appels_joignables'),
            $rapportsTel->sum('appels_non_joignables'),
            '', $rapportsTel->sum('clients_interesses_nombre'), '',
            $rapportsTel->sum('clients_deja_servis_nombre'), '',
            $rapportsTel->sum('nj_repondeur'),
            $rapportsTel->sum('nj_numero_errone'),
            $rapportsTel->sum('nj_hors_reseau'),
            $rapportsTel->sum('nj_autres_nombre'),
            '',
            '',
            '',
        ];

        $metaExport = [
            'Campagne : '.$campagne->nom,
            'Période : '.$dateDebut->format('d/m/Y').' → '.$dateFin->format('d/m/Y'),
            'Généré le '.now()->locale('fr')->translatedFormat('d F Y').' à '.now()->format('H:i'),
        ];

        $definitions = [
            [
                'title' => 'Ventes détaillées',
                'document_title' => 'Rapport campagne — Ventes détaillées',
                'meta_lines' => $metaExport,
                'headers' => ['Date', 'Campagne', 'Client', 'Téléphone', 'Type carte', 'Montant', 'Commercial', 'Agence', 'Statut'],
                'rows' => $ventes->map(fn ($v) => [
                    $v->created_at->format('d/m/Y H:i'),
                    $v->campagne?->nom ?? '-',
                    $v->client ? trim($v->client->prenom.' '.$v->client->nom) : '-',
                    $v->client->telephone ?? '',
                    $v->typeCarte?->code ?? '-',
                    $v->montant ?? '',
                    $v->user ? ($v->user->prenom ? trim($v->user->prenom.' '.$v->user->name) : $v->user->name) : '',
                    $v->agence->nom ?? '',
                    $v->statut_activation ?? '',
                ])->all(),
                'totals_row' => [
                    'TOTAUX ('.$ventes->count().' ligne(s))', '', '', '', '', $ventes->sum('montant'), '', '', '',
                ],
            ],
            [
                'title' => 'Clients',
                'document_title' => 'Rapport campagne — Clients (au moins une vente dans le périmètre)',
                'meta_lines' => $metaExport,
                'headers' => ['Client', 'Téléphone', 'Ville', 'Quartier', 'Nb ventes', 'Montant total FCFA'],
                'rows' => $clientsParVente->map(fn (array $x) => [
                    trim($x['client']->prenom.' '.$x['client']->nom),
                    $x['client']->telephone ?? '',
                    $x['client']->ville ?? '',
                    $x['client']->quartier ?? '',
                    $x['nb_ventes'],
                    $x['montant'],
                ])->all(),
                'totals_row' => [
                    'TOTAUX ('.$clientsParVente->count().' client(s))', '', '', '', $ventes->count(), $ventes->sum('montant'),
                ],
            ],
            [
                'title' => 'Commerciaux',
                'document_title' => 'Rapport campagne — Synthèse commerciaux',
                'meta_lines' => $metaExport,
                'headers' => ['Rang', 'Commercial', 'Agence', 'Ventes', 'Montant FCFA'],
                'rows' => $synthese['commerciaux']->map(fn ($l) => [
                    $l['rang'], $l['user_name'], $l['agence_nom'] ?? '', $l['total_ventes'], $l['total_montant'],
                ])->all(),
                'totals_row' => ['', '', 'TOTAUX', $synthese['commerciaux']->sum('total_ventes'), $synthese['commerciaux']->sum('total_montant')],
            ],
            [
                'title' => 'Agences',
                'document_title' => 'Rapport campagne — Synthèse agences',
                'meta_lines' => $metaExport,
                'headers' => ['Agence', 'Ventes', 'Montant FCFA', 'Part % volume', 'Nb commerciaux'],
                'rows' => $synthese['agences']->map(fn ($l) => [
                    $l['agence_nom'], $l['total_ventes'], $l['total_montant'], $l['pct_volume'], $l['nb_commerciaux'],
                ])->all(),
                'totals_row' => ['TOTAUX', $synthese['agences']->sum('total_ventes'), $synthese['agences']->sum('total_montant'), '', ''],
            ],
            [
                'title' => 'Types de carte',
                'document_title' => 'Rapport campagne — Types de carte',
                'meta_lines' => $metaExport,
                'headers' => ['Type carte', 'Ventes', 'Montant FCFA', 'Part % volume'],
                'rows' => $synthese['par_type_carte']->map(fn ($l) => [
                    $l['code'], $l['total_ventes'], $l['total_montant'], $l['pct_volume'],
                ])->all(),
                'totals_row' => ['TOTAUX', $synthese['par_type_carte']->sum('total_ventes'), $synthese['par_type_carte']->sum('total_montant'), ''],
            ],
            [
                'title' => 'Par semaine',
                'document_title' => 'Rapport campagne — Volume par semaine',
                'meta_lines' => $metaExport,
                'headers' => ['Période', 'Ventes', 'Montant FCFA'],
                'rows' => $synthese['par_semaine']->map(fn ($l) => [
                    $l['libelle'], $l['total_ventes'], $l['total_montant'],
                ])->all(),
                'totals_row' => ['TOTAUX', $synthese['par_semaine']->sum('total_ventes'), $synthese['par_semaine']->sum('total_montant')],
            ],
            [
                'title' => 'Par mois',
                'document_title' => 'Rapport campagne — Volume par mois',
                'meta_lines' => $metaExport,
                'headers' => ['Mois', 'Ventes', 'Montant FCFA'],
                'rows' => $synthese['par_mois']->map(fn ($l) => [
                    $l['libelle'], $l['total_ventes'], $l['total_montant'],
                ])->all(),
                'totals_row' => ['TOTAUX', $synthese['par_mois']->sum('total_ventes'), $synthese['par_mois']->sum('total_montant')],
            ],
            [
                'title' => 'Synthèse téléphonique',
                'document_title' => 'Rapport campagne — Synthèse téléphonique (indicateurs agrégés)',
                'meta_lines' => $metaExport,
                'headers' => ['Indicateur', 'Valeur'],
                'rows' => [
                    ['Nombre de fiches', $telepho['nb_fiches']],
                    ['Appels émis (cumul)', $telepho['appels_emis']],
                    ['Joignables (cumul)', $telepho['appels_joignables']],
                    ['Non joignables (cumul)', $telepho['appels_non_joignables']],
                    ['Clients intéressés (cumul)', $telepho['clients_interesses']],
                    ['Clients déjà servis (cumul)', $telepho['clients_deja_servis']],
                ],
            ],
            [
                'title' => 'Fiches téléphonique',
                'document_title' => 'Rapport campagne — Fiches reporting téléphonique (détail)',
                'meta_lines' => $metaExport,
                'headers' => $hdrFichesTel,
                'rows' => $rowsFichesTel,
                'totals_row' => $totauxFichesTel,
            ],
        ];

        $spreadsheet = $this->spreadsheetExportService->createMultiSheetSpreadsheet($definitions);
        $fn = 'rapport_campagne_'.$campagne->id.'_complet_'.$dateDebut->format('Y-m-d').'.xlsx';

        return $this->spreadsheetExportService->download($spreadsheet, $fn);
    }

    /**
     * @param  array<int, string>  $headers
     * @param  array<int, array<int, mixed>>  $rows
     */
    private function spreadsheetSingleSheet(string $title, array $headers, array $rows, string $fileBaseName): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($this->spreadsheetExportService->sanitizeSheetTitle($title));
        $this->spreadsheetExportService->fillSheet($sheet, $headers, $rows);

        return $this->spreadsheetExportService->download($spreadsheet, $fileBaseName.'.xlsx');
    }

    /**
     * @return array{0: Carbon, 1: Carbon, 2: int|null, 3: int|null}
     */
    private function parseFiltresSyntheseCampagne(Request $request, Campagne $campagne): array
    {
        $debutCampagne = $campagne->date_debut->copy()->startOfDay();
        $finCampagne = $campagne->date_fin->copy()->endOfDay();

        $du = $request->query('du');
        $au = $request->query('au');
        if ($request->filled('du') && $request->filled('au')) {
            $dateDebut = Carbon::parse($du)->startOfDay();
            $dateFin = Carbon::parse($au)->endOfDay();
            if ($dateDebut->lt($debutCampagne)) {
                $dateDebut = $debutCampagne->copy();
            }
            if ($dateFin->gt($finCampagne)) {
                $dateFin = $finCampagne->copy();
            }
            if ($dateDebut->gt($dateFin)) {
                [$dateDebut, $dateFin] = [$dateFin->copy()->startOfDay(), $dateDebut->copy()->endOfDay()];
            }
        } else {
            $dateDebut = $debutCampagne;
            $dateFin = $finCampagne;
        }

        $filtreAgenceId = $request->query('agence_id');
        $filtreAgenceId = $filtreAgenceId !== null && $filtreAgenceId !== '' ? (int) $filtreAgenceId : null;

        $filtreUserId = $request->query('user_id');
        $filtreUserId = $filtreUserId !== null && $filtreUserId !== '' ? (int) $filtreUserId : null;

        return [$dateDebut, $dateFin, $filtreAgenceId, $filtreUserId];
    }

    private function parseFiltreTypeCarteId(Request $request): ?int
    {
        $v = $request->query('type_carte_id');
        if ($v === null || $v === '') {
            return null;
        }

        return (int) $v;
    }

    public function export(Request $request): StreamedResponse
    {
        /** @var User $user */
        $user = $request->user();
        if (! $user->isAdmin() && ! $user->isDirection()) {
            abort(403);
        }

        $type = $request->query('type', 'mensuel');
        $agenceId = $request->query('agence');
        $date = $request->query('date', now()->format('Y-m'));

        if ($type === 'hebdomadaire') {
            $dateDebut = Carbon::parse($date.'-01')->startOfWeek();
            $dateFin = $dateDebut->copy()->endOfWeek();
        } else {
            $dateDebut = Carbon::parse($date.'-01')->startOfMonth();
            $dateFin = $dateDebut->copy()->endOfMonth();
        }

        $ventesQuery = Vente::with(['client', 'user', 'agence', 'typeCarte', 'campagne'])
            ->whereBetween('created_at', [$dateDebut, $dateFin]);

        if ($agenceId !== null && $agenceId !== '') {
            $ventesQuery->where('agence_id', $agenceId);
        }

        $ventes = $ventesQuery->orderBy('created_at')->get();

        $format = strtolower((string) $request->query('format', 'csv'));
        if ($format === 'xlsx') {
            $hdr = ['Date', 'Campagne', 'Client', 'Téléphone', 'Type carte', 'Montant', 'Commercial', 'Agence', 'Statut'];
            $rows = $ventes->map(fn ($v) => [
                $v->created_at->format('d/m/Y H:i'),
                $v->campagne?->nom ?? '-',
                trim($v->client->prenom.' '.$v->client->nom),
                $v->client->telephone ?? '',
                $v->typeCarte?->code ?? '-',
                $v->montant ?? '',
                $v->user ? ($v->user->prenom ? trim($v->user->prenom.' '.$v->user->name) : $v->user->name) : '',
                $v->agence->nom ?? '',
                $v->statut_activation,
            ])->all();
            $spreadsheet = new Spreadsheet;
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle($this->spreadsheetExportService->sanitizeSheetTitle('Ventes '.$type));
            $this->spreadsheetExportService->fillSheet($sheet, $hdr, $rows);
            $fn = "rapport_ventes_{$type}_{$dateDebut->format('Y-m-d')}.xlsx";

            return $this->spreadsheetExportService->download($spreadsheet, $fn);
        }

        $filename = "rapport_ventes_{$type}_{$dateDebut->format('Y-m-d')}.csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($ventes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Campagne', 'Client', 'Téléphone', 'Type carte', 'Montant', 'Commercial', 'Agence', 'Statut'], ';');
            foreach ($ventes as $v) {
                fputcsv($file, [
                    $v->created_at->format('d/m/Y H:i'),
                    $v->campagne?->nom ?? '-',
                    $v->client->prenom.' '.$v->client->nom,
                    $v->client->telephone ?? '',
                    $v->typeCarte?->code ?? '-',
                    $v->montant ?? '',
                    $v->user->name ?? '',
                    $v->agence->nom ?? '',
                    $v->statut_activation,
                ], ';');
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function assertUserCanAccessCampagne(?User $user, Campagne $campagne): void
    {
        if (! $user) {
            abort(403);
        }
        if ($user->isAdmin() || $user->isDirection()) {
            return;
        }
        abort(403, 'Accès non autorisé à cette campagne.');
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function parseDatesReportingTelCampagne(Request $request, Campagne $campagne): array
    {
        $campDebut = $campagne->date_debut->copy()->startOfDay();
        $campFin = $campagne->date_fin->copy()->endOfDay();

        $dateDebut = $request->date('date_debut')?->copy()->startOfDay() ?? $campDebut->copy();
        $dateFin = $request->date('date_fin')?->copy()->endOfDay() ?? $campFin->copy();

        $dateDebut = $dateDebut->max($campDebut);
        $dateFin = $dateFin->min($campFin);
        if ($dateDebut->gt($dateFin)) {
            $dateDebut = $campDebut->copy();
            $dateFin = $campFin->copy();
        }

        return [$dateDebut, $dateFin];
    }

    private function assertTelephoniqueRapportDansPerimetreCampagne(Campagne $campagne, TelephoniqueRapport $rapport): void
    {
        $du = $campagne->date_debut->copy()->startOfDay();
        $au = $campagne->date_fin->copy()->endOfDay();
        $exists = $this->campagneRapportService->telephoniqueRapportsPourCampagneQuery($campagne, $du, $au, null, null)
            ->whereKey($rapport->getKey())
            ->exists();
        abort_unless($exists, 404);
    }
}
