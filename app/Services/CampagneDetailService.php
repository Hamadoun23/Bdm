<?php

namespace App\Services;

use App\Models\Agence;
use App\Models\Campagne;
use App\Models\EnrolementClient;
use App\Models\Prime;
use App\Models\TypeCarte;
use App\Models\User;
use App\Models\Vente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CampagneDetailService
{
    public function __construct(
        private CampagneRapportService $campagneRapportService
    ) {}

    /**
     * @return array{preset: string, periode_debut: Carbon, periode_fin: Carbon, campagne: Campagne, stats: array, classement: Collection, primes: Collection, typesCartes: Collection, telephoniqueCampagne: array{nb_fiches: int, appels_emis: int, appels_joignables: int, appels_non_joignables: int, clients_interesses: int, clients_deja_servis: int}}
     */
    public function buildShowData(Campagne $campagne, ?Request $request = null): array
    {
        Campagne::syncStatuts();
        $campagne->refresh();

        [$preset, $dateDebut, $dateFin] = self::resolvePeriodeFromRequest($request, $campagne);

        $campagne->load([
            'agences', 'actions.user', 'beneficiairesAide.agence', 'typesCartesRemise',
            'signatairesContrat.agence', 'contratReponses.user', 'aideVersements.user',
            'contratArticles',
        ]);

        $commerciauxPerimetre = $campagne->queryCommerciauxPerimetre()
            ->with('agence')
            ->orderBy('name')
            ->get();

        $agenceIds = $campagne->idsAgencesPerimetre();
        $commerciauxCandidats = User::with('agence')
            ->whereIn('role', ['commercial', 'commercial_telephonique'])
            ->whereNotNull('agence_id')
            ->when(! $campagne->toutes_agences, fn ($q) => $q->whereIn('agence_id', $agenceIds))
            ->orderBy('name')
            ->get();

        $reponsesParUser = $campagne->contratReponses->keyBy('user_id');

        $nbCommerciauxActifs = $commerciauxPerimetre->where('actif', true)->count();
        $nbCommerciauxInactifs = $commerciauxPerimetre->where('actif', false)->count();

        $activeTab = in_array($request?->get('tab'), ['pilotage', 'commerciaux', 'contrat', 'aide', 'performances', 'historique'], true)
            ? $request->get('tab')
            : 'pilotage';

        $campDebut = $campagne->date_debut->copy()->startOfDay();
        $campFin = $campagne->date_fin->copy()->endOfDay();

        $dateDebut = $dateDebut->copy()->max($campDebut);
        $dateFin = $dateFin->copy()->min($campFin);
        if ($dateDebut->gt($dateFin)) {
            $dateDebut = $campDebut->copy();
            $dateFin = $campFin->copy();
        }

        $agenceIdsCampagne = $campagne->toutes_agences ? null : $campagne->agences->pluck('id');
        $estEnrolement = $campagne->type === Campagne::TYPE_ENROLEMENT_APP;

        if ($estEnrolement) {
            // Pas de notion de type de carte / agence hors-campagne pour ce type : périmètre simple sur campagne_id.
            $queryBase = EnrolementClient::query()
                ->where('campagne_id', $campagne->id)
                ->whereBetween('created_at', [$dateDebut, $dateFin]);

            $stats = [
                'total_ventes' => (clone $queryBase)->count(),
                'par_type' => collect(),
                'par_agence' => (clone $queryBase)->selectRaw('agence_id, COUNT(*) as nb')
                    ->groupBy('agence_id')->get(),
            ];
        } else {
            $queryVentes = Vente::query()->where(function ($q) use ($campagne, $dateDebut, $dateFin, $agenceIdsCampagne) {
                $q->where(function ($q1) use ($campagne, $dateDebut, $dateFin) {
                    $q1->where('campagne_id', $campagne->id)
                        ->whereBetween('created_at', [$dateDebut, $dateFin]);
                })->orWhere(function ($q2) use ($dateDebut, $dateFin, $agenceIdsCampagne) {
                    $q2->whereNull('campagne_id')
                        ->whereBetween('created_at', [$dateDebut, $dateFin]);
                    if ($agenceIdsCampagne !== null && $agenceIdsCampagne->isNotEmpty()) {
                        $q2->whereIn('agence_id', $agenceIdsCampagne);
                    }
                });
            });
            $queryBase = $queryVentes;

            $stats = [
                'total_ventes' => (clone $queryVentes)->count(),
                'par_type' => (clone $queryVentes)->selectRaw('type_carte_id, COUNT(*) as nb')
                    ->groupBy('type_carte_id')->get()->keyBy('type_carte_id'),
                'par_agence' => (clone $queryVentes)->selectRaw('agence_id, COUNT(*) as nb')
                    ->groupBy('agence_id')->get(),
            ];
        }

        $agenceIds = $stats['par_agence']->pluck('agence_id')->filter()->unique();
        $agencesMap = Agence::whereIn('id', $agenceIds)->get()->keyBy('id');
        $stats['par_agence'] = $stats['par_agence']->map(function ($row) use ($agencesMap) {
            $row->agence_nom = $agencesMap->get($row->agence_id)?->nom ?? 'N/A';

            return $row;
        });

        $classementRaw = (clone $queryBase)
            ->selectRaw('user_id, COUNT(*) as total_ventes')
            ->groupBy('user_id')
            ->orderByDesc('total_ventes')
            ->get();

        $userIds = $classementRaw->pluck('user_id')->filter()->unique();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');
        $classement = $classementRaw->map(function ($row, $i) use ($users) {
            $user = $users->get($row->user_id);
            $name = $user?->prenom ? trim($user->prenom.' '.$user->name) : ($user?->name ?? '-');

            return [
                'rang' => $i + 1,
                'user_name' => $name,
                'total_ventes' => $row->total_ventes,
            ];
        })->values();

        $periodes = [];
        $current = $dateDebut->copy()->startOfMonth();
        $endMonth = $dateFin->copy()->startOfMonth();
        while ($current->lte($endMonth)) {
            $periodes[] = $current->format('Y-m');
            $current->addMonth();
        }
        $primes = Prime::whereIn('user_id', $userIds)
            ->whereIn('periode', array_unique($periodes))
            ->with('user')
            ->orderBy('periode')
            ->get();

        $typesCartes = TypeCarte::orderBy('code')->get();

        $periode_debut = $dateDebut;
        $periode_fin = $dateFin;

        $telephoniqueCampagne = $this->campagneRapportService->agregatsTelephonique(
            $campagne,
            $dateDebut,
            $dateFin,
            null,
            null
        );

        return compact(
            'campagne', 'stats', 'classement', 'primes', 'typesCartes',
            'preset', 'periode_debut', 'periode_fin', 'telephoniqueCampagne',
            'commerciauxPerimetre', 'commerciauxCandidats', 'reponsesParUser',
            'nbCommerciauxActifs', 'nbCommerciauxInactifs', 'activeTab'
        );
    }

    /**
     * Convertit le résultat de buildShowData() en props sérialisables pour Inertia.
     *
     * @param  array<string, mixed>  $d  Sortie de buildShowData()
     * @return array<string, mixed>
     */
    public function toInertiaProps(array $d, bool $isDirectionDetail): array
    {
        /** @var Campagne $campagne */
        $campagne = $d['campagne'];
        $statut = $campagne->statut_effectif;

        return [
            'isDirectionDetail' => $isDirectionDetail,
            'activeTab' => $d['activeTab'],
            'preset' => $d['preset'],
            'periode' => [
                'debut' => $d['periode_debut']->format('Y-m-d'),
                'fin' => $d['periode_fin']->format('Y-m-d'),
                'debut_affiche' => $d['periode_debut']->format('d/m/Y'),
                'fin_affiche' => $d['periode_fin']->format('d/m/Y'),
            ],
            'campagne' => [
                'id' => $campagne->id,
                'nom' => $campagne->nom,
                'type' => $campagne->type,
                'statut' => $statut,
                'peut_piloter' => in_array($statut, ['programmee', 'en_cours'], true),
                'date_debut' => $campagne->date_debut->format('d/m/Y'),
                'date_fin' => $campagne->date_fin->format('d/m/Y'),
                'date_debut_iso' => $campagne->date_debut->format('Y-m-d'),
                'date_fin_iso' => $campagne->date_fin->format('Y-m-d'),
                'agences_libelle' => $campagne->toutes_agences ? 'Toutes les agences' : $campagne->agences->pluck('nom')->join(', '),
                'prime_meilleur_vendeur' => number_format($campagne->prime_meilleur_vendeur, 0, ',', ' '),
                'aide_hebdo_active' => $campagne->aide_hebdo_active,
                'aide_hebdo_montant' => number_format($campagne->aide_hebdo_montant, 0, ',', ' '),
                'aide_hebdo_carburant' => number_format($campagne->aide_hebdo_carburant, 0, ',', ' '),
                'aide_hebdo_credit_tel' => number_format($campagne->aide_hebdo_credit_tel, 0, ',', ' '),
                'remise_libelle' => $campagne->remise_pourcentage
                    ? $campagne->remise_pourcentage.' %'.($campagne->remise_tous_types_cartes ? ' — tous types' : ' — '.($campagne->typesCartesRemise->pluck('code')->join(', ') ?: 'types non définis'))
                    : null,
                'created_at' => $campagne->created_at->format('d/m/Y H:i'),
                'contrat_tous_commerciaux' => $campagne->contrat_tous_commerciaux,
                'contrat_emolument_forfait' => number_format($campagne->contrat_emolument_forfait, 0, ',', ' '),
                'contrat_forfait_communication' => number_format($campagne->contrat_forfait_communication, 0, ',', ' '),
                'contrat_forfait_deplacement' => number_format($campagne->contrat_forfait_deplacement, 0, ',', ' '),
                'contrat_representant_nom' => $campagne->contrat_representant_nom,
                'contrat_lieu_signature' => $campagne->contrat_lieu_signature,
                'contrat_clause_libre' => $campagne->contrat_clause_libre,
                'contrat_publie_at' => $campagne->contrat_publie_at?->format('d/m/Y H:i'),
                'contrat_articles' => $campagne->contratArticles->map(fn ($a) => [
                    'id' => $a->id, 'titre' => $a->titre, 'contenu' => $a->contenu,
                ])->values(),
                'contrat_reponses' => $campagne->contratReponses->map(fn ($rep) => [
                    'id' => $rep->id,
                    'user_name' => $rep->user?->prenom ? trim($rep->user->prenom.' '.$rep->user->name) : $rep->user?->name,
                    'statut' => $rep->statut,
                    'verrou' => $campagne->contratDelaiExpire() && $rep->statut === 'en_attente',
                    'repondu_at' => $rep->repondu_at?->format('d/m/Y H:i'),
                ])->values(),
                'aide_versements' => $campagne->aideVersements->sortByDesc('semaine_debut')->map(fn ($v) => [
                    'id' => $v->id,
                    'semaine_debut' => $v->semaine_debut->format('d/m/Y'),
                    'user_name' => $v->user?->prenom ? trim($v->user->prenom.' '.$v->user->name) : $v->user?->name,
                    'montant_carburant' => number_format($v->montant_carburant, 0, ',', ' '),
                    'montant_credit_tel' => number_format($v->montant_credit_tel, 0, ',', ' '),
                    'accuse_at' => $v->accuse_at?->format('d/m/Y H:i'),
                ])->values(),
                'signataires_pour_versement' => $campagne->signatairesContrat->map(fn ($u) => [
                    'id' => $u->id,
                    'nom' => $u->prenom ? trim($u->prenom.' '.$u->name) : $u->name,
                ])->values(),
                'actions' => $campagne->actions->map(fn ($a) => [
                    'id' => $a->id,
                    'action' => $a->action,
                    'description' => $a->description,
                    'created_at' => $a->created_at->format('d/m/Y H:i'),
                    'user_name' => $a->user?->name,
                    'avant' => $a->donnees_avant ? [
                        'date_debut' => isset($a->donnees_avant['date_debut']) ? Carbon::parse($a->donnees_avant['date_debut'])->format('d/m/Y') : null,
                        'date_fin' => isset($a->donnees_avant['date_fin']) ? Carbon::parse($a->donnees_avant['date_fin'])->format('d/m/Y') : null,
                    ] : null,
                    'apres' => $a->donnees_apres ? [
                        'date_debut' => isset($a->donnees_apres['date_debut']) ? Carbon::parse($a->donnees_apres['date_debut'])->format('d/m/Y') : null,
                        'date_fin' => isset($a->donnees_apres['date_fin']) ? Carbon::parse($a->donnees_apres['date_fin'])->format('d/m/Y') : null,
                    ] : null,
                ])->values(),
            ],
            'nbCommerciauxActifs' => $d['nbCommerciauxActifs'],
            'nbCommerciauxInactifs' => $d['nbCommerciauxInactifs'],
            'commerciauxPerimetre' => $d['commerciauxPerimetre']->map(function ($u) use ($d) {
                $rep = $d['reponsesParUser']->get($u->id);

                return [
                    'id' => $u->id,
                    'nom' => $u->prenom ? trim($u->prenom.' '.$u->name) : $u->name,
                    'agence_nom' => $u->agence->nom ?? '—',
                    'telephone' => $u->telephone,
                    'actif' => (bool) $u->actif,
                    'contrat_statut' => $rep?->statut,
                ];
            })->values(),
            'commerciauxCandidats' => $d['commerciauxCandidats']->map(fn ($c) => [
                'id' => $c->id,
                'nom' => $c->prenom ? trim($c->prenom.' '.$c->name) : $c->name,
                'agence_nom' => $c->agence->nom ?? '?',
            ])->values(),
            'benefIds' => $campagne->signatairesContrat->pluck('id')->values(),
            'stats' => [
                'total_ventes' => $d['stats']['total_ventes'],
                'par_type' => collect($d['typesCartes'])->map(fn ($tc) => [
                    'code' => $tc->code,
                    'nb' => (int) ($d['stats']['par_type'][$tc->id]->nb ?? 0),
                ])->values(),
                'par_agence' => collect($d['stats']['par_agence'])->map(fn ($row) => [
                    'agence_nom' => $row->agence_nom,
                    'nb' => (int) $row->nb,
                ])->values(),
            ],
            'classement' => $d['classement'],
            'primes' => $d['primes']->map(fn ($p) => [
                'periode' => $p->periode,
                'user_name' => $p->user?->prenom ? trim($p->user->prenom.' '.$p->user->name) : $p->user?->name,
                'rang' => $p->rang,
                'montant' => number_format($p->montant, 0, ',', ' '),
            ])->values(),
            'telephoniqueCampagne' => $d['telephoniqueCampagne'],
            'telephoniqueListUrl' => route('rapports.campagnes.reporting-telephonique', [
                'campagne' => $campagne->id,
                'date_debut' => $d['periode_debut']->format('Y-m-d'),
                'date_fin' => $d['periode_fin']->format('Y-m-d'),
            ]),
        ];
    }

    /**
     * @return array{0: string, 1: Carbon, 2: Carbon}
     */
    public static function resolvePeriodeFromRequest(?Request $request, Campagne $campagne): array
    {
        $campDebut = $campagne->date_debut->copy()->startOfDay();
        $campFin = $campagne->date_fin->copy()->endOfDay();

        if ($request === null) {
            return ['campagne', $campDebut, $campFin];
        }

        $preset = $request->get('periode', 'campagne');
        $now = now();

        switch ($preset) {
            case 'semaine':
                return ['semaine', $now->copy()->startOfWeek()->startOfDay(), $now->copy()->endOfWeek()->endOfDay()];
            case 'mois':
                return ['mois', $now->copy()->startOfMonth()->startOfDay(), $now->copy()->endOfMonth()->endOfDay()];
            case 'perso':
                $from = $request->date('date_debut')?->copy()->startOfDay() ?? $campDebut;
                $to = $request->date('date_fin')?->copy()->endOfDay() ?? $campFin;

                return ['perso', $from, $to];
            default:
                return ['campagne', $campDebut, $campFin];
        }
    }
}
