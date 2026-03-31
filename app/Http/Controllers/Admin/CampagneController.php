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
use App\Services\CampagneDetailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampagneController extends Controller
{
    public function index(): View
    {
        Campagne::syncStatuts();
        $campagnes = Campagne::with('agences')->orderByDesc('date_debut')->paginate(10);

        return view('admin.campagnes.index', compact('campagnes'));
    }

    public function create(): View
    {
        $agences = Agence::all();
        $commerciaux = User::with('agence')->where('role', 'commercial')->whereNotNull('agence_id')->orderBy('name')->get();
        $typesCartes = TypeCarte::orderBy('code')->get();

        return view('admin.campagnes.create', compact('agences', 'commerciaux', 'typesCartes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(array_merge($this->reglesCampagneBase(), $this->reglesCampagneRemiseAide()));

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

        $toutesAgences = $request->boolean('toutes_agences');
        $agenceIds = $toutesAgences ? [] : ($request->agences ?? []);

        if (! $toutesAgences && empty($agenceIds)) {
            return back()->withErrors(['agences' => 'Sélectionnez au moins une agence ou cochez "Toutes les agences".'])->withInput();
        }

        $campagne = Campagne::create([
            'nom' => $request->nom,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'prime_meilleur_vendeur' => $request->prime_meilleur_vendeur,
            'actif' => false,
            'statut' => Campagne::STATUT_PROGRAMMEE,
            'toutes_agences' => $toutesAgences,
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

        if (! $toutesAgences) {
            $campagne->agences()->sync($agenceIds);
        }

        $this->syncAideBeneficiaires($campagne, $request);
        $this->syncSignatairesContrat($campagne, $request);
        $this->syncRemiseTypesCartes($campagne, $request);
        $this->syncContratReponses($campagne, $request, true);
        CampagneContratArticle::seedDefaultsIfEmpty($campagne->id);

        Campagne::syncStatuts();

        return redirect()->route('admin.campagnes.index')->with('success', 'Campagne créée.');
    }

    public function show(Campagne $campagne, CampagneDetailService $detailService): View
    {
        return view('admin.campagnes.show', $detailService->buildShowData($campagne));
    }

    public function edit(Campagne $campagne): View
    {
        $agences = Agence::all();
        $campagne->load(['beneficiairesAide', 'typesCartesRemise', 'signatairesContrat', 'contratArticles']);
        $commerciaux = User::with('agence')->where('role', 'commercial')->whereNotNull('agence_id')->orderBy('name')->get();
        $typesCartes = TypeCarte::orderBy('code')->get();

        return view('admin.campagnes.edit', compact('campagne', 'agences', 'commerciaux', 'typesCartes'));
    }

    public function update(Request $request, Campagne $campagne): RedirectResponse
    {
        $request->validate(array_merge($this->reglesCampagneBase(), $this->reglesCampagneRemiseAide()));

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

        $toutesAgences = $request->boolean('toutes_agences');
        $agenceIds = $toutesAgences ? [] : ($request->agences ?? []);

        if (! $toutesAgences && empty($agenceIds)) {
            return back()->withErrors(['agences' => 'Sélectionnez au moins une agence ou cochez "Toutes les agences".'])->withInput();
        }

        $campagne->update([
            'nom' => $request->nom,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'prime_meilleur_vendeur' => $request->prime_meilleur_vendeur,
            'toutes_agences' => $toutesAgences,
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

        $campagne->agences()->sync($toutesAgences ? [] : $agenceIds);
        $this->syncAideBeneficiaires($campagne, $request);
        $this->syncSignatairesContrat($campagne, $request);
        $this->syncRemiseTypesCartes($campagne, $request);
        $this->syncContratReponses($campagne, $request, false);
        CampagneContratArticle::seedDefaultsIfEmpty($campagne->id);

        Campagne::syncStatuts();

        return redirect()->route('admin.campagnes.index')->with('success', 'Campagne mise à jour.');
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

        return redirect()->route('admin.campagnes.index')->with('success', 'Campagne arrêtée.');
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

        return redirect()->route('admin.campagnes.index')->with('success', 'Campagne annulée.');
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
        Campagne::syncStatuts();

        return redirect()->route('admin.campagnes.index')->with('success', 'Campagne reprogrammée.');
    }

    public function destroy(Campagne $campagne): RedirectResponse
    {
        $campagne->delete();

        return redirect()->route('admin.campagnes.index')->with('success', 'Campagne supprimée.');
    }

    /** @return array<string, array<int, mixed>> */
    private function reglesCampagneBase(): array
    {
        return [
            'nom' => 'required|string|max:255',
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
        $valid = User::whereIn('id', $ids)->where('role', 'commercial')->pluck('id')->all();
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
            $q = User::query()->where('role', 'commercial')->whereNotNull('agence_id');
            if (! $campagne->toutes_agences) {
                $agenceIds = $campagne->agences()->pluck('agences.id');
                $q->whereIn('agence_id', $agenceIds);
            }
            $ids = $q->pluck('id')->all();
            $campagne->signatairesContrat()->sync($ids);
        } else {
            $ids = array_unique(array_map('intval', $request->input('aide_beneficiaires', [])));
            $valid = User::whereIn('id', $ids)->where('role', 'commercial')->pluck('id')->all();
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
