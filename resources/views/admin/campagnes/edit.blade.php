@extends('layouts.app')

@section('title', 'Modifier campagne')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Modifier {{ $campagne->nom }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.campagnes.update', $campagne) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nom *</label>
                <input type="text" name="nom" class="form-control" value="{{ old('nom', $campagne->nom) }}" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date début *</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ old('date_debut', $campagne->date_debut->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date fin *</label>
                    <input type="date" name="date_fin" class="form-control" value="{{ old('date_fin', $campagne->date_fin->format('Y-m-d')) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prime Top 1 (FCFA) *</label>
                    <input type="number" name="prime_top1" class="form-control" value="{{ old('prime_top1', $campagne->prime_top1) }}" min="0" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prime Top 2 (FCFA) *</label>
                    <input type="number" name="prime_top2" class="form-control" value="{{ old('prime_top2', $campagne->prime_top2) }}" min="0" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Agences concernées *</label>
                <input type="hidden" name="toutes_agences" value="0">
                <div class="form-check mb-2">
                    <input type="checkbox" name="toutes_agences" value="1" class="form-check-input" id="toutes_agences" {{ old('toutes_agences', $campagne->toutes_agences) ? 'checked' : '' }} onchange="toggleAgences(this)">
                    <label class="form-check-label" for="toutes_agences">Toutes les agences</label>
                </div>
                <div id="agences-wrap" style="display: {{ old('toutes_agences', $campagne->toutes_agences) ? 'none' : 'block' }}">
                    @foreach($agences as $a)
                    <div class="form-check">
                        <input type="checkbox" name="agences[]" value="{{ $a->id }}" class="form-check-input" id="ag{{ $a->id }}" {{ in_array($a->id, old('agences', $campagne->agences->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <label class="form-check-label" for="ag{{ $a->id }}">{{ $a->nom }}</label>
                    </div>
                    @endforeach
                    @error('agences')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            @php
                $aideOn = filter_var(old('aide_hebdo_active', $campagne->aide_hebdo_active), FILTER_VALIDATE_BOOLEAN);
                $aideTous = filter_var(old('aide_hebdo_tous_commerciaux', $campagne->aide_hebdo_tous_commerciaux), FILTER_VALIDATE_BOOLEAN);
                $benefIds = old('aide_beneficiaires', $campagne->beneficiairesAide->pluck('id')->toArray());
            @endphp

            <hr class="my-4">
            <h6 class="text-primary">Remise sur les ventes</h6>
            <p class="text-muted small">Pendant la campagne active, le prix catalogue des cartes est réduit selon le pourcentage (montant de vente enregistré = prix après remise).</p>
            <div class="mb-3">
                <label class="form-label">Remise (%)</label>
                <input type="number" name="remise_pourcentage" class="form-control" value="{{ old('remise_pourcentage', $campagne->remise_pourcentage) }}" min="0" max="100" step="0.01" placeholder="0 = pas de remise">
            </div>

            <hr class="my-4">
            <h6 class="text-primary">Coût / aide hebdomadaire commerciaux</h6>
            <p class="text-muted small">Chaque semaine de la campagne, montant versé par l’entreprise (ex. 5&nbsp;000&nbsp;F dont carburant et crédit téléphonique). À attribuer à tous les commerciaux concernés par la campagne ou à une sélection.</p>
            <input type="hidden" name="aide_hebdo_active" value="0">
            <div class="form-check mb-3">
                <input type="checkbox" name="aide_hebdo_active" value="1" class="form-check-input" id="aide_hebdo_active" {{ $aideOn ? 'checked' : '' }} onchange="toggleAideChamps(this)">
                <label class="form-check-label" for="aide_hebdo_active">Activer l’aide hebdomadaire pour cette campagne</label>
            </div>
            <div id="aide-champs" style="display: {{ $aideOn ? 'block' : 'none' }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Montant total / semaine (FCFA)</label>
                        <input type="number" name="aide_hebdo_montant" class="form-control" value="{{ old('aide_hebdo_montant', $campagne->aide_hebdo_montant) }}" min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Carburant (FCFA)</label>
                        <input type="number" name="aide_hebdo_carburant" class="form-control" value="{{ old('aide_hebdo_carburant', $campagne->aide_hebdo_carburant) }}" min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Crédit téléphonique (FCFA)</label>
                        <input type="number" name="aide_hebdo_credit_tel" class="form-control" value="{{ old('aide_hebdo_credit_tel', $campagne->aide_hebdo_credit_tel) }}" min="0">
                    </div>
                </div>
                <p class="small text-muted">La somme carburant + crédit doit égaler le montant total.</p>
                @error('aide_hebdo_montant')<div class="text-danger small">{{ $message }}</div>@enderror
                <input type="hidden" name="aide_hebdo_tous_commerciaux" value="0">
                <div class="form-check mb-2">
                    <input type="checkbox" name="aide_hebdo_tous_commerciaux" value="1" class="form-check-input" id="aide_hebdo_tous" {{ $aideTous ? 'checked' : '' }} onchange="toggleBeneficiaires(this)">
                    <label class="form-check-label" for="aide_hebdo_tous">Tous les commerciaux des agences concernées</label>
                </div>
                <div id="wrap-beneficiaires" style="display: {{ $aideTous ? 'none' : 'block' }}">
                    <label class="form-label">Commerciaux bénéficiaires</label>
                    <div class="border rounded p-2" style="max-height: 220px; overflow-y: auto;">
                        @foreach($commerciaux as $c)
                        <div class="form-check">
                            <input type="checkbox" name="aide_beneficiaires[]" value="{{ $c->id }}" class="form-check-input" id="cb{{ $c->id }}" {{ in_array($c->id, (array) $benefIds) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cb{{ $c->id }}">{{ $c->prenom ? trim($c->prenom.' '.$c->name) : $c->name }} — {{ $c->agence->nom ?? '?' }}</label>
                        </div>
                        @endforeach
                    </div>
                    @error('aide_beneficiaires')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
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
</script>
@endsection
