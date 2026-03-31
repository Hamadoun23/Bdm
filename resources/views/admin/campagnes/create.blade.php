@extends('layouts.app')

@section('title', 'Nouvelle campagne')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Nouvelle campagne</h5>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">Une campagne est une activité de vente de cartes durant une période donnée.</p>
        <form method="POST" action="{{ route('admin.campagnes.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nom *</label>
                <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date début *</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ old('date_debut') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date fin *</label>
                    <input type="date" name="date_fin" class="form-control" value="{{ old('date_fin') }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Prime du meilleur vendeur (FCFA) *</label>
                <input type="number" name="prime_meilleur_vendeur" class="form-control" value="{{ old('prime_meilleur_vendeur', 25000) }}" min="0" required>
                <p class="text-muted small mb-0 mt-1">Attribuée au seul 1<sup>er</sup> du classement (ventes) sur la période.</p>
            </div>
            <div class="mb-3">
                <label class="form-label">Agences concernées *</label>
                <input type="hidden" name="toutes_agences" value="0">
                <div class="form-check mb-2">
                    <input type="checkbox" name="toutes_agences" value="1" class="form-check-input" id="toutes_agences" {{ old('toutes_agences', true) ? 'checked' : '' }} onchange="toggleAgences(this)">
                    <label class="form-check-label" for="toutes_agences">Toutes les agences</label>
                </div>
                <div id="agences-wrap" style="display: {{ old('toutes_agences', true) ? 'none' : 'block' }}">
                    @foreach($agences as $a)
                    <div class="form-check">
                        <input type="checkbox" name="agences[]" value="{{ $a->id }}" class="form-check-input" id="ag{{ $a->id }}" {{ in_array($a->id, old('agences', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="ag{{ $a->id }}">{{ $a->nom }}</label>
                    </div>
                    @endforeach
                    @error('agences')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            @php
                $aideTousCreate = filter_var(old('aide_hebdo_tous_commerciaux', true), FILTER_VALIDATE_BOOLEAN);
            @endphp

            <hr class="my-4">
            <h6 class="text-primary">Commerciaux engagés (contrat de prestation)</h6>
            <p class="text-muted small">Obligatoire : tous les commerciaux des agences concernées, ou une sélection.</p>
            <input type="hidden" name="aide_hebdo_tous_commerciaux" value="0">
            <div class="form-check mb-2">
                <input type="checkbox" name="aide_hebdo_tous_commerciaux" value="1" class="form-check-input" id="aide_hebdo_tous" {{ $aideTousCreate ? 'checked' : '' }} onchange="toggleBeneficiaires(this)">
                <label class="form-check-label" for="aide_hebdo_tous">Tous les commerciaux des agences concernées</label>
            </div>
            <div id="wrap-beneficiaires" style="display: {{ $aideTousCreate ? 'none' : 'block' }}">
                <label class="form-label">Sélection des commerciaux</label>
                <div class="border rounded p-2" style="max-height: 220px; overflow-y: auto;">
                    @foreach($commerciaux as $c)
                    <div class="form-check">
                        <input type="checkbox" name="aide_beneficiaires[]" value="{{ $c->id }}" class="form-check-input" id="cb{{ $c->id }}" {{ in_array($c->id, old('aide_beneficiaires', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="cb{{ $c->id }}">{{ $c->prenom ? trim($c->prenom.' '.$c->name) : $c->name }} — {{ $c->agence->nom ?? '?' }}</label>
                    </div>
                    @endforeach
                </div>
                @error('aide_beneficiaires')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <hr class="my-4">
            <h6 class="text-primary">Paramètres du contrat de prestation</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Émolument forfait mission (FCFA)</label>
                    <input type="number" name="contrat_emolument_forfait" class="form-control" value="{{ old('contrat_emolument_forfait', 50000) }}" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Forfait communication (FCFA)</label>
                    <input type="number" name="contrat_forfait_communication" class="form-control" value="{{ old('contrat_forfait_communication', 2000) }}" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Forfait déplacement (FCFA)</label>
                    <input type="number" name="contrat_forfait_deplacement" class="form-control" value="{{ old('contrat_forfait_deplacement', 3000) }}" min="0">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Représentant GDA (nom pour le contrat)</label>
                    <input type="text" name="contrat_representant_nom" class="form-control" value="{{ old('contrat_representant_nom', 'Yaya H DIALLO') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Lieu « Fait à … »</label>
                    <input type="text" name="contrat_lieu_signature" class="form-control" value="{{ old('contrat_lieu_signature', 'Bamako') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Clause libre (facultatif)</label>
                <textarea name="contrat_clause_libre" class="form-control" rows="3" placeholder="Texte juridique complémentaire…">{{ old('contrat_clause_libre') }}</textarea>
            </div>

            <hr class="my-4">
            <h6 class="text-primary">Remise sur les ventes</h6>
            <p class="text-muted small">Pendant la campagne active, indiquez un pourcentage de remise puis choisissez si elle s’applique à <strong>tous</strong> les types de cartes ou seulement à une <strong>sélection</strong> (montant de vente enregistré = prix catalogue après remise).</p>
            <div class="mb-3">
                <label class="form-label">Remise (%)</label>
                <input type="number" name="remise_pourcentage" class="form-control" value="{{ old('remise_pourcentage') }}" min="0" max="100" step="0.01" placeholder="0 = pas de remise">
            </div>
            @php
                $remiseTousTypes = filter_var(old('remise_tous_types_cartes', true), FILTER_VALIDATE_BOOLEAN);
                $remiseTypeIds = old('remise_types_cartes', []);
            @endphp
            <input type="hidden" name="remise_tous_types_cartes" value="0">
            <div class="form-check mb-2">
                <input type="checkbox" name="remise_tous_types_cartes" value="1" class="form-check-input" id="remise_tous_types" {{ $remiseTousTypes ? 'checked' : '' }} onchange="toggleRemiseTypes(this)">
                <label class="form-check-label" for="remise_tous_types">Appliquer la remise à <strong>tous</strong> les types de cartes</label>
            </div>
            <div id="wrap-remise-types" style="display: {{ $remiseTousTypes ? 'none' : 'block' }}">
                <label class="form-label">Types de cartes concernés par la remise</label>
                <div class="border rounded p-2" style="max-height: 220px; overflow-y: auto;">
                    @forelse($typesCartes as $tc)
                    <div class="form-check">
                        <input type="checkbox" name="remise_types_cartes[]" value="{{ $tc->id }}" class="form-check-input" id="remtc{{ $tc->id }}" {{ in_array((string) $tc->id, array_map('strval', $remiseTypeIds)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="remtc{{ $tc->id }}">{{ $tc->code }} — {{ number_format($tc->prix) }} F</label>
                    </div>
                    @empty
                    <p class="text-muted small mb-0">Aucun type de carte. Créez-en dans l’administration.</p>
                    @endforelse
                </div>
                @error('remise_types_cartes')<small class="text-danger d-block">{{ $message }}</small>@enderror
            </div>

            @php
                $aideOnCreate = filter_var(old('aide_hebdo_active', false), FILTER_VALIDATE_BOOLEAN);
            @endphp
            <hr class="my-4">
            <h6 class="text-primary">Coût / aide hebdomadaire commerciaux</h6>
            <p class="text-muted small">Montants hebdomadaires. Les bénéficiaires sont les commerciaux engagés définis plus haut.</p>
            <input type="hidden" name="aide_hebdo_active" value="0">
            <div class="form-check mb-3">
                <input type="checkbox" name="aide_hebdo_active" value="1" class="form-check-input" id="aide_hebdo_active" {{ $aideOnCreate ? 'checked' : '' }} onchange="toggleAideChamps(this)">
                <label class="form-check-label" for="aide_hebdo_active">Activer l’aide hebdomadaire pour cette campagne</label>
            </div>
            <div id="aide-champs" style="display: {{ $aideOnCreate ? 'block' : 'none' }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Montant total / semaine (FCFA)</label>
                        <input type="number" name="aide_hebdo_montant" class="form-control" value="{{ old('aide_hebdo_montant', 5000) }}" min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Carburant (FCFA)</label>
                        <input type="number" name="aide_hebdo_carburant" class="form-control" value="{{ old('aide_hebdo_carburant', 3000) }}" min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Crédit téléphonique (FCFA)</label>
                        <input type="number" name="aide_hebdo_credit_tel" class="form-control" value="{{ old('aide_hebdo_credit_tel', 2000) }}" min="0">
                    </div>
                </div>
                <p class="small text-muted">La somme carburant + crédit doit égaler le montant total.</p>
                @error('aide_hebdo_montant')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary">Créer</button>
            <a href="{{ route('admin.campagnes.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
<script>
function toggleAgences(el) {
    document.getElementById('agences-wrap').style.display = el.checked ? 'none' : 'block';
    if (el.checked) document.querySelectorAll('#agences-wrap input[type=checkbox]').forEach(c => c.checked = false);
}
function toggleAideChamps(el) {
    document.getElementById('aide-champs').style.display = el.checked ? 'block' : 'none';
}
function toggleBeneficiaires(el) {
    document.getElementById('wrap-beneficiaires').style.display = el.checked ? 'none' : 'block';
    if (el.checked) document.querySelectorAll('#wrap-beneficiaires input[type=checkbox]').forEach(c => c.checked = false);
}
function toggleRemiseTypes(el) {
    document.getElementById('wrap-remise-types').style.display = el.checked ? 'none' : 'block';
    if (el.checked) document.querySelectorAll('#wrap-remise-types input[type=checkbox]').forEach(c => c.checked = false);
}
</script>
@endsection
