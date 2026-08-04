<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Campagne;
use App\Models\CampagneAction;
use App\Models\CampagneContratArticle;
use App\Models\ContratPrestationReponse;
use App\Models\TypeCarte;
use App\Models\User;
use App\Services\CampagneCommerciauxImportService;
use App\Services\CampagneDetailService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;

class CampagneController extends Controller
{
    public function index(): Response
    {
        Campagne::syncStatuts();
        $campagnes = Campagne::query()->orderByDesc('date_debut')->paginate(10);

        return Inertia::render('Admin/Campagnes/Index', [
            'campagnes' => [
                'data' => $campagnes->getCollection()->map(fn (Campagne $c) => [
                    'id' => $c->id,
                    'nom' => $c->nom,
                    'date_debut' => $c->date_debut->format('d/m/Y'),
                    'date_debut_iso' => $c->date_debut->format('Y-m-d'),
                    'date_fin' => $c->date_fin->format('d/m/Y'),
                    'date_fin_iso' => $c->date_fin->format('Y-m-d'),
                    'prime_meilleur_vendeur' => number_format($c->prime_meilleur_vendeur, 0, ',', ' '),
                    'estEnrolement' => $c->type === Campagne::TYPE_ENROLEMENT_APP,
                    'statut' => $c->statut_effectif,
                    'peut_piloter' => in_array($c->statut_effectif, [Campagne::STATUT_PROGRAMMEE, Campagne::STATUT_EN_COURS]),
                ])->values(),
                'links' => $campagnes->linkCollection(),
                'from' => $campagnes->firstItem(),
                'to' => $campagnes->lastItem(),
                'total' => $campagnes->total(),
            ],
        ]);
    }

    public function create(): Response
    {
        $agences = Agence::query()->orderBy('ordre')->orderBy('nom')->get();
        $commerciaux = User::with('agence')->whereIn('role', ['commercial', 'commercial_telephonique'])->whereNotNull('agence_id')->orderBy('name')->get();

        return Inertia::render('Admin/Campagnes/Create', [
            'agences' => $agences->map(fn (Agence $a) => ['id' => $a->id, 'nom' => $a->nom])->values(),
            'commerciaux' => $commerciaux->map(fn (User $c) => [
                'id' => $c->id,
                'nom' => $c->prenom ? trim($c->prenom.' '.$c->name) : $c->name,
                'agence_nom' => $c->agence->nom ?? '?',
            ])->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $type = $request->input('type', Campagne::TYPE_VENTE_CARTE);
        $estVente = $type === Campagne::TYPE_VENTE_CARTE;

        $rules = $this->reglesCampagneBase();
        if ($estVente) {
            $rules = array_merge($rules, $this->reglesCampagneRemiseAide());
        }
        $request->validate($rules);

        if ($estVente) {
            $err = $this->validerEngagementCommerciaux($request);
            if ($err) {
                return back()->withErrors($err)->withInput();
            }

            $err = $this->validerAideHebdo($request);
            if ($err) {
                return back()->withErrors($err)->withInput();
            }

            $errRemiseTypes = $this->validerRemiseTypesCartes($request);
            if ($errRemiseTypes) {
                return back()->withErrors($errRemiseTypes)->withInput();
            }
        }

        $toutesAgences = $request->boolean('toutes_agences');
        $agenceIds = $toutesAgences ? [] : ($request->agences ?? []);

        if (! $toutesAgences && empty($agenceIds)) {
            return back()->withErrors(['agences' => 'Sélectionnez au moins une agence ou cochez "Toutes les agences".'])->withInput();
        }

        $errChevauchement = $this->validerPerimetreAgencesSansChevauchement(null, $request, $toutesAgences, $agenceIds, $type);
        if ($errChevauchement) {
            return back()->withErrors($errChevauchement)->withInput();
        }

        $attributs = [
            'nom' => $request->nom,
            'type' => $type,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'prime_meilleur_vendeur' => $request->prime_meilleur_vendeur,
            'actif' => false,
            'statut' => Campagne::STATUT_PROGRAMMEE,
            'toutes_agences' => $toutesAgences,
        ];

        if ($estVente) {
            $attributs = array_merge($attributs, [
                'remise_pourcentage' => $request->filled('remise_pourcentage') ? $request->remise_pourcentage : null,
                'aide_hebdo_active' => $request->boolean('aide_hebdo_active'),
                'aide_hebdo_montant' => (int) $request->input('aide_hebdo_montant', 5000),
                'aide_hebdo_carburant' => (int) $request->input('aide_hebdo_carburant', 3000),
                'aide_hebdo_credit_tel' => (int) $request->input('aide_hebdo_credit_tel', 2000),
                'aide_hebdo_tous_commerciaux' => $request->boolean('aide_hebdo_tous_commerciaux'),
                'remise_tous_types_cartes' => $request->boolean('remise_tous_types_cartes'),
                'contrat_tous_commerciaux' => $request->boolean('aide_hebdo_tous_commerciaux'),
                'contrat_emolument_forfait' => (int) $request->input('contrat_emolument_forfait', 50000),
                'contrat_forfait_communication' => (int) $request->input('contrat_forfait_communication', 2000),
                'contrat_forfait_deplacement' => (int) $request->input('contrat_forfait_deplacement', 3000),
                'contrat_representant_nom' => $request->input('contrat_representant_nom', 'Yaya H DIALLO'),
                'contrat_lieu_signature' => $request->input('contrat_lieu_signature', 'Bamako'),
                'contrat_clause_libre' => $request->filled('contrat_clause_libre') ? $request->contrat_clause_libre : null,
            ]);
        } else {
            // Pas de remise/aide hebdo pour ce type : les commerciaux engagés sont gérés
            // explicitement (import ou sélection manuelle) depuis l'onglet Commerciaux, jamais "tous".
            // Le contrat de prestation (articles génériques + acceptation) reste requis, cf. plus bas.
            $attributs['contrat_tous_commerciaux'] = false;
            $attributs['contrat_representant_nom'] = $request->input('contrat_representant_nom', 'Yaya H DIALLO');
            $attributs['contrat_lieu_signature'] = $request->input('contrat_lieu_signature', 'Bamako');
        }

        $campagne = Campagne::create($attributs);

        if (! $toutesAgences) {
            $campagne->agences()->sync($agenceIds);
        }

        if ($estVente) {
            $this->syncAideBeneficiaires($campagne, $request);
            $this->syncSignatairesContrat($campagne, $request);
            $this->syncRemiseTypesCartes($campagne, $request);
        }
        // Contrat de prestation requis pour les deux types : articles génériques + publication immédiate
        // (les réponses des commerciaux se créent au fil de l'eau, y compris pour ceux ajoutés plus tard
        // via l'import en masse côté enrôlement).
        $this->syncContratReponses($campagne, $request, true);
        CampagneContratArticle::seedDefaultsIfEmpty($campagne->id, $type);

        Campagne::syncStatuts();

        return redirect()->route('admin.campagnes.index')->with('success', 'Campagne créée.');
    }

    public function show(Request $request, Campagne $campagne, CampagneDetailService $detailService): Response
    {
        $data = $detailService->buildShowData($campagne, $request);

        return Inertia::render('Admin/Campagnes/Show', $detailService->toInertiaProps($data, false));
    }

    public function edit(Campagne $campagne): Response
    {
        $agences = Agence::query()->orderBy('ordre')->orderBy('nom')->get();
        $campagne->load(['beneficiairesAide', 'typesCartesRemise', 'signatairesContrat', 'contratArticles']);
        $commerciaux = User::with('agence')->whereIn('role', ['commercial', 'commercial_telephonique'])->whereNotNull('agence_id')->orderBy('name')->get();

        return Inertia::render('Admin/Campagnes/Edit', [
            'campagne' => [
                'id' => $campagne->id,
                'nom' => $campagne->nom,
                'type' => $campagne->type,
                'date_debut' => $campagne->date_debut->format('Y-m-d'),
                'date_fin' => $campagne->date_fin->format('Y-m-d'),
                'prime_meilleur_vendeur' => $campagne->prime_meilleur_vendeur,
                'toutes_agences' => $campagne->toutes_agences,
                'agence_ids' => $campagne->agences->pluck('id')->values(),
                'aide_hebdo_active' => $campagne->aide_hebdo_active,
                'aide_hebdo_montant' => $campagne->aide_hebdo_montant,
                'aide_hebdo_carburant' => $campagne->aide_hebdo_carburant,
                'aide_hebdo_credit_tel' => $campagne->aide_hebdo_credit_tel,
                'aide_hebdo_tous_commerciaux' => $campagne->aide_hebdo_tous_commerciaux,
                'aide_beneficiaire_ids' => $campagne->signatairesContrat->pluck('id')->values(),
                'contrat_emolument_forfait' => $campagne->contrat_emolument_forfait,
                'contrat_forfait_communication' => $campagne->contrat_forfait_communication,
                'contrat_forfait_deplacement' => $campagne->contrat_forfait_deplacement,
                'contrat_representant_nom' => $campagne->contrat_representant_nom,
                'contrat_lieu_signature' => $campagne->contrat_lieu_signature,
                'contrat_clause_libre' => $campagne->contrat_clause_libre,
                'contrat_publie_at' => $campagne->contrat_publie_at?->format('d/m/Y H:i'),
                'contrat_articles' => $campagne->contratArticles->map(fn ($a) => [
                    'id' => $a->id, 'titre' => $a->titre, 'contenu' => $a->contenu,
                ])->values(),
            ],
            'agences' => $agences->map(fn (Agence $a) => ['id' => $a->id, 'nom' => $a->nom])->values(),
            'commerciaux' => $commerciaux->map(fn (User $c) => [
                'id' => $c->id,
                'nom' => $c->prenom ? trim($c->prenom.' '.$c->name) : $c->name,
                'agence_nom' => $c->agence->nom ?? '?',
            ])->values(),
        ]);
    }

    public function update(Request $request, Campagne $campagne): RedirectResponse
    {
        // Le type est fixé à la création — jamais relu depuis la requête (empêche un changement
        // post-création qui laisserait des données orphelines type contrat/enrôlements).
        $type = $campagne->type;
        $estVente = $type === Campagne::TYPE_VENTE_CARTE;

        $rules = $this->reglesCampagneBase();
        unset($rules['type']);
        if ($estVente) {
            $rules = array_merge($rules, $this->reglesCampagneRemiseAide());
        }
        $request->validate($rules);

        if ($estVente) {
            $err = $this->validerEngagementCommerciaux($request);
            if ($err) {
                return back()->withErrors($err)->withInput();
            }

            $err = $this->validerAideHebdo($request);
            if ($err) {
                return back()->withErrors($err)->withInput();
            }

            $errRemiseTypes = $this->validerRemiseTypesCartes($request);
            if ($errRemiseTypes) {
                return back()->withErrors($errRemiseTypes)->withInput();
            }
        }

        $toutesAgences = $request->boolean('toutes_agences');
        $agenceIds = $toutesAgences ? [] : ($request->agences ?? []);

        if (! $toutesAgences && empty($agenceIds)) {
            return back()->withErrors(['agences' => 'Sélectionnez au moins une agence ou cochez "Toutes les agences".'])->withInput();
        }

        if ($this->perimetreOuDatesCampagneModifies($campagne, $request, $toutesAgences, $agenceIds)) {
            $errChevauchement = $this->validerPerimetreAgencesSansChevauchement($campagne, $request, $toutesAgences, $agenceIds, $type);
            if ($errChevauchement) {
                return back()->withErrors($errChevauchement)->withInput();
            }
        }

        $attributs = [
            'nom' => $request->nom,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'prime_meilleur_vendeur' => $request->prime_meilleur_vendeur,
            'toutes_agences' => $toutesAgences,
        ];

        if ($estVente) {
            $attributs = array_merge($attributs, [
                'remise_pourcentage' => $request->filled('remise_pourcentage') ? $request->remise_pourcentage : null,
                'aide_hebdo_active' => $request->boolean('aide_hebdo_active'),
                'aide_hebdo_montant' => (int) $request->input('aide_hebdo_montant', 5000),
                'aide_hebdo_carburant' => (int) $request->input('aide_hebdo_carburant', 3000),
                'aide_hebdo_credit_tel' => (int) $request->input('aide_hebdo_credit_tel', 2000),
                'aide_hebdo_tous_commerciaux' => $request->boolean('aide_hebdo_tous_commerciaux'),
                'remise_tous_types_cartes' => $request->boolean('remise_tous_types_cartes'),
                'contrat_tous_commerciaux' => $request->boolean('aide_hebdo_tous_commerciaux'),
                'contrat_emolument_forfait' => (int) $request->input('contrat_emolument_forfait', 50000),
                'contrat_forfait_communication' => (int) $request->input('contrat_forfait_communication', 2000),
                'contrat_forfait_deplacement' => (int) $request->input('contrat_forfait_deplacement', 3000),
                'contrat_representant_nom' => $request->input('contrat_representant_nom', 'Yaya H DIALLO'),
                'contrat_lieu_signature' => $request->input('contrat_lieu_signature', 'Bamako'),
                'contrat_clause_libre' => $request->filled('contrat_clause_libre') ? $request->contrat_clause_libre : null,
            ]);
        }

        $campagne->update($attributs);

        $campagne->agences()->sync($toutesAgences ? [] : $agenceIds);

        if ($estVente) {
            $this->syncAideBeneficiaires($campagne, $request);
            $this->syncSignatairesContrat($campagne, $request);
            $this->syncRemiseTypesCartes($campagne, $request);
        }
        // Filet de sécurité : garantit que le contrat reste publié et les articles présents
        // (couvre aussi les campagnes créées avant ce correctif).
        $this->syncContratReponses($campagne, $request, false);
        CampagneContratArticle::seedDefaultsIfEmpty($campagne->id, $type);

        Campagne::apresModificationDatesOuPerimetre($campagne);

        return redirect()->route('admin.campagnes.show', ['campagne' => $campagne, 'tab' => 'pilotage'])
            ->with('success', 'Campagne mise à jour.');
    }

    public function arreter(Request $request, Campagne $campagne): RedirectResponse
    {
        $request->validate(['description' => 'required|string|min:10']);

        if (! in_array($campagne->statut_effectif, [Campagne::STATUT_PROGRAMMEE, Campagne::STATUT_EN_COURS])) {
            return back()->withErrors(['description' => 'Cette campagne ne peut pas être arrêtée.']);
        }

        CampagneAction::create([
            'campagne_id' => $campagne->id,
            'action' => 'arreter',
            'description' => $request->description,
            'donnees_avant' => $campagne->only(['statut', 'actif']),
            'user_id' => auth()->user()?->id,
        ]);

        $campagne->update(['statut' => Campagne::STATUT_ARRETEE, 'actif' => false]);
        Campagne::resynchroniserActifsCommerciauxSelonCampagnesVivantes();

        return redirect()->route('admin.campagnes.show', ['campagne' => $campagne, 'tab' => 'pilotage'])
            ->with('success', 'Campagne arrêtée.');
    }

    public function annuler(Request $request, Campagne $campagne): RedirectResponse
    {
        $request->validate(['description' => 'required|string|min:10']);

        if (! in_array($campagne->statut_effectif, [Campagne::STATUT_PROGRAMMEE, Campagne::STATUT_EN_COURS])) {
            return back()->withErrors(['description' => 'Cette campagne ne peut pas être annulée.']);
        }

        CampagneAction::create([
            'campagne_id' => $campagne->id,
            'action' => 'annuler',
            'description' => $request->description,
            'donnees_avant' => $campagne->only(['statut', 'actif']),
            'user_id' => auth()->user()?->id,
        ]);

        $campagne->update(['statut' => Campagne::STATUT_ANNULEE, 'actif' => false]);
        Campagne::resynchroniserActifsCommerciauxSelonCampagnesVivantes();

        return redirect()->route('admin.campagnes.show', ['campagne' => $campagne, 'tab' => 'pilotage'])
            ->with('success', 'Campagne annulée.');
    }

    public function reprogrammer(Request $request, Campagne $campagne): RedirectResponse
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'description' => 'required|string|min:10',
        ]);

        if (! in_array($campagne->statut_effectif, [Campagne::STATUT_PROGRAMMEE, Campagne::STATUT_EN_COURS])) {
            return back()->withErrors(['description' => 'Seules les campagnes programmées ou en cours peuvent être reprogrammées.']);
        }

        $avant = $campagne->only(['date_debut', 'date_fin']);
        $apres = [
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ];

        CampagneAction::create([
            'campagne_id' => $campagne->id,
            'action' => 'reprogrammer',
            'description' => $request->description,
            'donnees_avant' => $avant,
            'donnees_apres' => $apres,
            'user_id' => auth()->user()?->id,
        ]);

        $campagne->update($apres);
        Campagne::apresModificationDatesOuPerimetre($campagne);

        return redirect()->route('admin.campagnes.show', ['campagne' => $campagne, 'tab' => 'pilotage'])
            ->with('success', 'Campagne reprogrammée.');
    }

    public function updateDates(Request $request, Campagne $campagne): RedirectResponse
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        $toutesAgences = $campagne->toutes_agences;
        $agenceIds = $campagne->agences()->pluck('agences.id')->all();

        $errChevauchement = $this->validerPerimetreAgencesSansChevauchement($campagne, $request, $toutesAgences, $agenceIds, $campagne->type);
        if ($errChevauchement) {
            return back()->withErrors($errChevauchement)->withInput();
        }

        $campagne->update([
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);
        Campagne::apresModificationDatesOuPerimetre($campagne);

        return redirect()->route('admin.campagnes.show', ['campagne' => $campagne, 'tab' => 'pilotage'])
            ->with('success', 'Dates mises à jour — statuts et comptes commerciaux resynchronisés.');
    }

    public function syncCommerciaux(Campagne $campagne): RedirectResponse
    {
        Campagne::apresModificationDatesOuPerimetre($campagne);

        return redirect()->route('admin.campagnes.show', ['campagne' => $campagne, 'tab' => 'commerciaux'])
            ->with('success', 'Comptes commerciaux resynchronisés selon les campagnes en cours.');
    }

    public function updateSignataires(Request $request, Campagne $campagne): RedirectResponse
    {
        $request->validate([
            'aide_hebdo_tous_commerciaux' => 'boolean',
            'aide_beneficiaires' => 'array',
            'aide_beneficiaires.*' => 'exists:users,id',
        ]);

        $err = $this->validerEngagementCommerciaux($request);
        if ($err) {
            return back()->withErrors($err)->withInput();
        }

        $tous = $request->boolean('aide_hebdo_tous_commerciaux');
        $campagne->update([
            'aide_hebdo_tous_commerciaux' => $tous,
            'contrat_tous_commerciaux' => $tous,
        ]);

        $this->syncSignatairesContrat($campagne, $request);
        $this->syncContratReponses($campagne, $request, false);
        Campagne::apresModificationDatesOuPerimetre($campagne);

        return redirect()->route('admin.campagnes.show', ['campagne' => $campagne, 'tab' => 'commerciaux'])
            ->with('success', 'Commerciaux engagés mis à jour.');
    }

    public function previsualiserImportCommerciaux(Request $request, CampagneCommerciauxImportService $service): JsonResponse
    {
        $request->validate(['texte' => 'required|string']);

        return response()->json($service->previsualiser($request->input('texte')));
    }

    public function importCommerciaux(Request $request, Campagne $campagne, CampagneCommerciauxImportService $service): RedirectResponse
    {
        $request->validate(['texte' => 'required|string']);

        $resultat = $service->importer($request->input('texte'));

        if ($resultat['user_ids'] === []) {
            return back()->withErrors(['texte' => 'Aucune ligne valide n’a pu être importée. Vérifiez le format (Nom, Prénom, Agence, Téléphone).']);
        }

        $existants = $campagne->signatairesContrat()->pluck('users.id')->map(fn ($id) => (int) $id)->all();
        $campagne->signatairesContrat()->sync(array_values(array_unique(array_merge($existants, $resultat['user_ids']))));
        Campagne::apresModificationDatesOuPerimetre($campagne);

        $msg = sprintf(
            '%d commercial(aux) réutilisé(s), %d compte(s) créé(s), %d nouvelle(s) agence(s).',
            $resultat['commerciaux_reutilises'],
            $resultat['commerciaux_crees'],
            $resultat['agences_creees']
        );
        if ($resultat['lignes_en_erreur'] > 0) {
            $msg .= ' '.$resultat['lignes_en_erreur'].' ligne(s) ignorée(s) (format invalide).';
        }

        return redirect()->route('admin.campagnes.show', ['campagne' => $campagne, 'tab' => 'commerciaux'])
            ->with('success', $msg);
    }

    public function republierContrat(Campagne $campagne): RedirectResponse
    {
        $campagne->update(['contrat_publie_at' => now()]);
        ContratPrestationReponse::where('campagne_id', $campagne->id)->update([
            'statut' => ContratPrestationReponse::STATUT_EN_ATTENTE,
            'repondu_at' => null,
        ]);

        return redirect()->route('admin.campagnes.show', ['campagne' => $campagne, 'tab' => 'contrat'])
            ->with('success', 'Contrat republié — nouveau délai de 5 jours pour accepter ou refuser.');
    }

    public function resetContratReponse(Campagne $campagne, ContratPrestationReponse $reponse): RedirectResponse
    {
        if ($reponse->campagne_id !== $campagne->id) {
            abort(404);
        }

        $reponse->update([
            'statut' => ContratPrestationReponse::STATUT_EN_ATTENTE,
            'repondu_at' => null,
        ]);

        return redirect()->route('admin.campagnes.show', ['campagne' => $campagne, 'tab' => 'contrat'])
            ->with('success', 'Réponse du commercial réinitialisée.');
    }

    public function destroy(Campagne $campagne): RedirectResponse
    {
        $campagne->delete();

        return redirect()->route('admin.campagnes.index')->with('success', 'Campagne supprimée.');
    }

    /**
     * @param  list<int|string>  $agenceIds
     */
    private function perimetreOuDatesCampagneModifies(Campagne $campagne, Request $request, bool $toutesAgences, array $agenceIds): bool
    {
        if ($toutesAgences !== $campagne->toutes_agences) {
            return true;
        }
        if ($request->date_debut !== $campagne->date_debut->format('Y-m-d')
            || $request->date_fin !== $campagne->date_fin->format('Y-m-d')) {
            return true;
        }
        if (! $toutesAgences) {
            $nouveau = array_values(array_unique(array_map('intval', $agenceIds)));
            sort($nouveau);
            $ancien = $campagne->agences()->pluck('agences.id')->map(fn ($id) => (int) $id)->sort()->values()->all();

            return $nouveau !== $ancien;
        }

        return false;
    }

    /**
     * Interdit qu’une même agence soit couverte par deux campagnes actives dont les périodes se chevauchent.
     *
     * @param  list<int|string>  $agenceIds  Ignoré si $toutesAgences.
     * @return array<string, string>|null
     */
    private function validerPerimetreAgencesSansChevauchement(?Campagne $campagne, Request $request, bool $toutesAgences, array $agenceIds, string $type): ?array
    {
        Campagne::syncStatuts();

        $ids = $toutesAgences
            ? Agence::query()->orderBy('id')->pluck('id')->map(fn ($id) => (int) $id)->all()
            : array_values(array_unique(array_map('intval', $agenceIds)));

        $debut = Carbon::parse($request->date_debut)->startOfDay();
        $fin = Carbon::parse($request->date_fin)->startOfDay();

        $excludeId = $campagne?->id ?? 0;

        // Deux campagnes de types différents (vente / enrôlement) sont des activités indépendantes :
        // elles peuvent tourner en parallèle sur les mêmes agences/dates sans conflit.
        $autres = Campagne::query()
            ->where('id', '!=', $excludeId)
            ->where('type', $type)
            ->where('actif', true)
            ->whereNotIn('statut', [Campagne::STATUT_ARRETEE, Campagne::STATUT_ANNULEE, Campagne::STATUT_TERMINEE])
            ->whereDate('date_debut', '<=', $fin)
            ->whereDate('date_fin', '>=', $debut)
            ->get();

        foreach ($autres as $autre) {
            $autreIds = $autre->toutes_agences
                ? Agence::query()->pluck('id')->map(fn ($id) => (int) $id)->all()
                : $autre->agences()->pluck('agences.id')->map(fn ($id) => (int) $id)->all();
            $conflits = array_values(array_intersect($ids, $autreIds));
            if ($conflits === []) {
                continue;
            }
            $noms = Agence::whereIn('id', $conflits)->orderBy('nom')->pluck('nom')->all();
            $liste = implode(', ', array_slice($noms, 0, 8));
            if (count($noms) > 8) {
                $liste .= '…';
            }

            return [
                'agences' => 'Cette campagne chevauche la période de « '.$autre->nom.' » (également active) : les agences '.$liste.' ne peuvent pas être sur les deux campagnes à la fois. Retirez « Toutes les agences » ou excluez ces agences d’une des campagnes.',
            ];
        }

        return null;
    }

    /** @return array<string, array<int, mixed>> */
    private function reglesCampagneBase(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'type' => ['required', Rule::in([Campagne::TYPE_VENTE_CARTE, Campagne::TYPE_ENROLEMENT_APP])],
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'prime_meilleur_vendeur' => 'required|numeric|min:0',
            'toutes_agences' => 'boolean',
            'agences' => 'array',
            'agences.*' => 'exists:agences,id',
        ];
    }

    /** @return array<string, array<int, mixed>> */
    private function reglesCampagneRemiseAide(): array
    {
        return [
            'remise_pourcentage' => 'nullable|numeric|min:0|max:100',
            'remise_tous_types_cartes' => 'boolean',
            'remise_types_cartes' => 'array',
            'remise_types_cartes.*' => 'exists:types_cartes,id',
            'aide_hebdo_active' => 'boolean',
            'aide_hebdo_montant' => 'nullable|integer|min:0',
            'aide_hebdo_carburant' => 'nullable|integer|min:0',
            'aide_hebdo_credit_tel' => 'nullable|integer|min:0',
            'aide_hebdo_tous_commerciaux' => 'boolean',
            'aide_beneficiaires' => 'array',
            'aide_beneficiaires.*' => 'exists:users,id',
            'contrat_emolument_forfait' => 'nullable|integer|min:0',
            'contrat_forfait_communication' => 'nullable|integer|min:0',
            'contrat_forfait_deplacement' => 'nullable|integer|min:0',
            'contrat_representant_nom' => 'nullable|string|max:191',
            'contrat_lieu_signature' => 'nullable|string|max:191',
            'contrat_clause_libre' => 'nullable|string|max:20000',
            'contrat_republier' => 'boolean',
        ];
    }

    /** @return array<string, string>|null */
    private function validerEngagementCommerciaux(Request $request): ?array
    {
        if (! $request->boolean('aide_hebdo_tous_commerciaux')) {
            $ids = $request->input('aide_beneficiaires', []);
            if (! is_array($ids) || count($ids) === 0) {
                return ['aide_beneficiaires' => 'Sélectionnez au moins un commercial engagé sur le contrat ou cochez « Tous les commerciaux des agences concernées ».'];
            }
        }

        return null;
    }

    /** @return array<string, string>|null */
    private function validerAideHebdo(Request $request): ?array
    {
        if (! $request->boolean('aide_hebdo_active')) {
            return null;
        }
        $total = (int) $request->input('aide_hebdo_montant', 0);
        $carb = (int) $request->input('aide_hebdo_carburant', 0);
        $tel = (int) $request->input('aide_hebdo_credit_tel', 0);
        if ($carb + $tel !== $total) {
            return ['aide_hebdo_montant' => 'Carburant + crédit téléphonique doit égaler le montant total hebdomadaire.'];
        }

        return null;
    }

    private function syncAideBeneficiaires(Campagne $campagne, Request $request): void
    {
        if (! $request->boolean('aide_hebdo_active') || $request->boolean('aide_hebdo_tous_commerciaux')) {
            $campagne->beneficiairesAide()->detach();

            return;
        }
        $ids = array_unique(array_map('intval', $request->input('aide_beneficiaires', [])));
        $valid = User::whereIn('id', $ids)->whereIn('role', ['commercial', 'commercial_telephonique'])->pluck('id')->all();
        $campagne->beneficiairesAide()->sync($valid);
    }

    /** @return array<string, string>|null */
    private function validerRemiseTypesCartes(Request $request): ?array
    {
        if (! $this->remiseEstActive($request)) {
            return null;
        }
        if ($request->boolean('remise_tous_types_cartes')) {
            return null;
        }
        $ids = $request->input('remise_types_cartes', []);
        if (! is_array($ids) || count($ids) === 0) {
            return ['remise_types_cartes' => 'Sélectionnez au moins un type de carte ou cochez « Tous les types de cartes ».'];
        }

        return null;
    }

    private function remiseEstActive(Request $request): bool
    {
        if (! $request->filled('remise_pourcentage')) {
            return false;
        }

        return (float) $request->remise_pourcentage > 0;
    }

    private function syncRemiseTypesCartes(Campagne $campagne, Request $request): void
    {
        if (! $this->remiseEstActive($request) || $request->boolean('remise_tous_types_cartes')) {
            $campagne->typesCartesRemise()->detach();

            return;
        }
        $ids = array_unique(array_map('intval', $request->input('remise_types_cartes', [])));
        $valid = TypeCarte::whereIn('id', $ids)->pluck('id')->all();
        $campagne->typesCartesRemise()->sync($valid);
    }

    private function syncSignatairesContrat(Campagne $campagne, Request $request): void
    {
        if ($request->boolean('aide_hebdo_tous_commerciaux')) {
            $q = User::query()->whereIn('role', ['commercial', 'commercial_telephonique'])->whereNotNull('agence_id');
            if (! $campagne->toutes_agences) {
                $agenceIds = $campagne->agences()->pluck('agences.id');
                $q->whereIn('agence_id', $agenceIds);
            }
            $ids = $q->pluck('id')->all();
            $campagne->signatairesContrat()->sync($ids);
        } else {
            $ids = array_unique(array_map('intval', $request->input('aide_beneficiaires', [])));
            $valid = User::whereIn('id', $ids)->whereIn('role', ['commercial', 'commercial_telephonique'])->pluck('id')->all();
            $campagne->signatairesContrat()->sync($valid);
        }
    }

    private function syncContratReponses(Campagne $campagne, Request $request, bool $isCreate): void
    {
        $republier = $request->boolean('contrat_republier');

        if ($isCreate) {
            $campagne->update(['contrat_publie_at' => now()]);
        } elseif ($republier) {
            $campagne->update(['contrat_publie_at' => now()]);
            ContratPrestationReponse::where('campagne_id', $campagne->id)->update([
                'statut' => ContratPrestationReponse::STATUT_EN_ATTENTE,
                'repondu_at' => null,
            ]);
        }

        $ids = $campagne->signatairesContrat()->pluck('users.id');
        foreach ($ids as $uid) {
            ContratPrestationReponse::firstOrCreate(
                ['campagne_id' => $campagne->id, 'user_id' => $uid],
                ['statut' => ContratPrestationReponse::STATUT_EN_ATTENTE]
            );
        }
        ContratPrestationReponse::where('campagne_id', $campagne->id)->whereNotIn('user_id', $ids)->delete();

        if (! $campagne->contrat_publie_at && $ids->isNotEmpty()) {
            $campagne->update(['contrat_publie_at' => now()]);
        }
    }
}
