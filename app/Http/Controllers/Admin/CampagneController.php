<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Campagne;
use App\Models\CampagneAction;
use App\Models\Prime;
use App\Models\TypeCarte;
use App\Models\User;
use App\Models\Vente;
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

        if (!$toutesAgences && empty($agenceIds)) {
            return back()->withErrors(['agences' => 'Sélectionnez au moins une agence ou cochez "Toutes les agences".'])->withInput();
        }

        $campagne = Campagne::create([
            'nom' => $request->nom,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'prime_top1' => $request->prime_top1,
            'prime_top2' => $request->prime_top2,
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
        ]);

        if (!$toutesAgences) {
            $campagne->agences()->sync($agenceIds);
        }

        $this->syncAideBeneficiaires($campagne, $request);
        $this->syncRemiseTypesCartes($campagne, $request);

        Campagne::syncStatuts();

        return redirect()->route('admin.campagnes.index')->with('success', 'Campagne créée.');
    }

    public function show(Campagne $campagne): View
    {
        $campagne->load(['agences', 'actions.user', 'beneficiairesAide.agence', 'typesCartesRemise']);
        $dateDebut = $campagne->date_debut->copy()->startOfDay();
        $dateFin = $campagne->date_fin->copy()->endOfDay();

        $agenceIdsCampagne = $campagne->toutes_agences ? null : $campagne->agences->pluck('id');
        $queryVentes = Vente::query()->where(function ($q) use ($campagne, $dateDebut, $dateFin, $agenceIdsCampagne) {
            $q->where('campagne_id', $campagne->id)
                ->orWhere(function ($q2) use ($dateDebut, $dateFin, $agenceIdsCampagne) {
                    $q2->whereNull('campagne_id')
                        ->whereBetween('created_at', [$dateDebut, $dateFin]);
                    if ($agenceIdsCampagne !== null && $agenceIdsCampagne->isNotEmpty()) {
                        $q2->whereIn('agence_id', $agenceIdsCampagne);
                    }
                });
        });

        $stats = [
            'total_ventes' => (clone $queryVentes)->count(),
            'montant_total' => (clone $queryVentes)->sum('montant'),
            'par_type' => (clone $queryVentes)->selectRaw('type_carte_id, COUNT(*) as nb, SUM(montant) as mt')
                ->groupBy('type_carte_id')->get()->keyBy('type_carte_id'),
            'par_agence' => (clone $queryVentes)->selectRaw('agence_id, COUNT(*) as nb, SUM(montant) as mt')
                ->groupBy('agence_id')->get(),
        ];
        $agenceIds = $stats['par_agence']->pluck('agence_id')->filter()->unique();
        $agencesMap = Agence::whereIn('id', $agenceIds)->get()->keyBy('id');
        $stats['par_agence'] = $stats['par_agence']->map(function ($row) use ($agencesMap) {
            $row->agence_nom = $agencesMap->get($row->agence_id)?->nom ?? 'N/A';
            return $row;
        });

        $classementRaw = (clone $queryVentes)
            ->selectRaw('user_id, COUNT(*) as total_ventes, SUM(montant) as montant_total')
            ->groupBy('user_id')
            ->orderByDesc('total_ventes')
            ->get();

        $userIds = $classementRaw->pluck('user_id')->filter()->unique();
        $users = \App\Models\User::whereIn('id', $userIds)->get()->keyBy('id');
        $classement = $classementRaw->map(function ($row, $i) use ($users) {
            $user = $users->get($row->user_id);
            $name = $user?->prenom ? trim($user->prenom . ' ' . $user->name) : ($user?->name ?? '-');
            return [
                'rang' => $i + 1,
                'user_name' => $name,
                'total_ventes' => $row->total_ventes,
                'montant_total' => $row->montant_total,
            ];
        })->values();

        $periodes = [];
        $current = $dateDebut->copy();
        while ($current->lte($dateFin)) {
            $periodes[] = $current->format('Y-m');
            $current->addMonth();
        }
        $primes = Prime::whereIn('user_id', $userIds)
            ->whereIn('periode', array_unique($periodes))
            ->with('user')
            ->orderBy('periode')
            ->get();

        $typesCartes = TypeCarte::orderBy('code')->get();

        return view('admin.campagnes.show', compact('campagne', 'stats', 'classement', 'primes', 'typesCartes'));
    }

    public function edit(Campagne $campagne): View
    {
        $agences = Agence::all();
        $campagne->load(['beneficiairesAide', 'typesCartesRemise']);
        $commerciaux = User::with('agence')->where('role', 'commercial')->whereNotNull('agence_id')->orderBy('name')->get();
        $typesCartes = TypeCarte::orderBy('code')->get();

        return view('admin.campagnes.edit', compact('campagne', 'agences', 'commerciaux', 'typesCartes'));
    }

    public function update(Request $request, Campagne $campagne): RedirectResponse
    {
        $request->validate(array_merge($this->reglesCampagneBase(), $this->reglesCampagneRemiseAide()));

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

        if (!$toutesAgences && empty($agenceIds)) {
            return back()->withErrors(['agences' => 'Sélectionnez au moins une agence ou cochez "Toutes les agences".'])->withInput();
        }

        $campagne->update([
            'nom' => $request->nom,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'prime_top1' => $request->prime_top1,
            'prime_top2' => $request->prime_top2,
            'toutes_agences' => $toutesAgences,
            'remise_pourcentage' => $request->filled('remise_pourcentage') ? $request->remise_pourcentage : null,
            'aide_hebdo_active' => $request->boolean('aide_hebdo_active'),
            'aide_hebdo_montant' => (int) $request->input('aide_hebdo_montant', 5000),
            'aide_hebdo_carburant' => (int) $request->input('aide_hebdo_carburant', 3000),
            'aide_hebdo_credit_tel' => (int) $request->input('aide_hebdo_credit_tel', 2000),
            'aide_hebdo_tous_commerciaux' => $request->boolean('aide_hebdo_tous_commerciaux'),
            'remise_tous_types_cartes' => $request->boolean('remise_tous_types_cartes'),
        ]);

        $campagne->agences()->sync($toutesAgences ? [] : $agenceIds);
        $this->syncAideBeneficiaires($campagne, $request);
        $this->syncRemiseTypesCartes($campagne, $request);

        Campagne::syncStatuts();

        return redirect()->route('admin.campagnes.index')->with('success', 'Campagne mise à jour.');
    }

    public function arreter(Request $request, Campagne $campagne): RedirectResponse
    {
        $request->validate(['description' => 'required|string|min:10']);

        if (!in_array($campagne->statut_effectif, [Campagne::STATUT_PROGRAMMEE, Campagne::STATUT_EN_COURS])) {
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

        return redirect()->route('admin.campagnes.index')->with('success', 'Campagne arrêtée.');
    }

    public function annuler(Request $request, Campagne $campagne): RedirectResponse
    {
        $request->validate(['description' => 'required|string|min:10']);

        if (!in_array($campagne->statut_effectif, [Campagne::STATUT_PROGRAMMEE, Campagne::STATUT_EN_COURS])) {
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

        return redirect()->route('admin.campagnes.index')->with('success', 'Campagne annulée.');
    }

    public function reprogrammer(Request $request, Campagne $campagne): RedirectResponse
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'description' => 'required|string|min:10',
        ]);

        if (!in_array($campagne->statut_effectif, [Campagne::STATUT_PROGRAMMEE, Campagne::STATUT_EN_COURS])) {
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
            'prime_top1' => 'required|numeric|min:0',
            'prime_top2' => 'required|numeric|min:0',
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
        ];
    }

    /** @return array<string, string>|null */
    private function validerAideHebdo(Request $request): ?array
    {
        if (!$request->boolean('aide_hebdo_active')) {
            return null;
        }
        $total = (int) $request->input('aide_hebdo_montant', 0);
        $carb = (int) $request->input('aide_hebdo_carburant', 0);
        $tel = (int) $request->input('aide_hebdo_credit_tel', 0);
        if ($carb + $tel !== $total) {
            return ['aide_hebdo_montant' => 'Carburant + crédit téléphonique doit égaler le montant total hebdomadaire.'];
        }
        if (!$request->boolean('aide_hebdo_tous_commerciaux')) {
            $ids = $request->input('aide_beneficiaires', []);
            if (!is_array($ids) || count($ids) === 0) {
                return ['aide_beneficiaires' => 'Sélectionnez au moins un commercial ou cochez « Tous les commerciaux ».'];
            }
        }

        return null;
    }

    private function syncAideBeneficiaires(Campagne $campagne, Request $request): void
    {
        if (!$request->boolean('aide_hebdo_active') || $request->boolean('aide_hebdo_tous_commerciaux')) {
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
}
