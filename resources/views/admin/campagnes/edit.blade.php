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
            <div class="mb-3">
                <label class="form-label">Prime du meilleur vendeur (FCFA) *</label>
                <input type="number" name="prime_meilleur_vendeur" class="form-control" value="{{ old('prime_meilleur_vendeur', $campagne->prime_meilleur_vendeur) }}" min="0" required>
                <p class="text-muted small mb-0 mt-1">Attribuée au seul 1<sup>er</sup> du classement (ventes) sur la période.</p>
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

            @php /** @var \App\Models\Campagne $campagne */ @endphp
            @php
                $aideOn = filter_var(old('aide_hebdo_active', $campagne->aide_hebdo_active), FILTER_VALIDATE_BOOLEAN);
                $aideTous = filter_var(old('aide_hebdo_tous_commerciaux', $campagne->aide_hebdo_tous_commerciaux), FILTER_VALIDATE_BOOLEAN);
                $benefIds = old('aide_beneficiaires', $campagne->signatairesContrat->pluck('id')->toArray());
                $remiseTousTypes = filter_var(old('remise_tous_types_cartes', $campagne->remise_tous_types_cartes ?? true), FILTER_VALIDATE_BOOLEAN);
                $remiseTypeIds = old('remise_types_cartes', $campagne->typesCartesRemise->pluck('id')->toArray());
            @endphp

            <hr class="my-4">
            <h6 class="text-primary">Commerciaux engagés (contrat de prestation)</h6>
            <p class="text-muted small">Obligatoire : tous les commerciaux des agences concernées, ou une sélection. Cette liste sert au contrat, à la fin de campagne (comptes) et aux bénéficiaires de l’aide hebdomadaire lorsque celle-ci est activée.</p>
            <input type="hidden" name="aide_hebdo_tous_commerciaux" value="0">
            <div class="form-check mb-2">
                <input type="checkbox" name="aide_hebdo_tous_commerciaux" value="1" class="form-check-input" id="aide_hebdo_tous" {{ $aideTous ? 'checked' : '' }} onchange="toggleBeneficiaires(this)">
                <label class="form-check-label" for="aide_hebdo_tous">Tous les commerciaux des agences concernées</label>
            </div>
            <div id="wrap-beneficiaires" style="display: {{ $aideTous ? 'none' : 'block' }}">
                <label class="form-label">Sélection des commerciaux</label>
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

            <hr class="my-4">
            <h6 class="text-primary">Paramètres du contrat de prestation</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Émolument forfait mission (FCFA)</label>
                    <input type="number" name="contrat_emolument_forfait" class="form-control" value="{{ old('contrat_emolument_forfait', $campagne->contrat_emolument_forfait) }}" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Forfait communication (FCFA)</label>
                    <input type="number" name="contrat_forfait_communication" class="form-control" value="{{ old('contrat_forfait_communication', $campagne->contrat_forfait_communication) }}" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Forfait déplacement (FCFA)</label>
                    <input type="number" name="contrat_forfait_deplacement" class="form-control" value="{{ old('contrat_forfait_deplacement', $campagne->contrat_forfait_deplacement) }}" min="0">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Représentant GDA (nom pour le contrat)</label>
                    <input type="text" name="contrat_representant_nom" class="form-control" value="{{ old('contrat_representant_nom', $campagne->contrat_representant_nom) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Lieu « Fait à … »</label>
                    <input type="text" name="contrat_lieu_signature" class="form-control" value="{{ old('contrat_lieu_signature', $campagne->contrat_lieu_signature) }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Clause libre (facultatif, affichée en fin de contrat)</label>
                <textarea name="contrat_clause_libre" class="form-control" rows="3" placeholder="Texte juridique complémentaire…">{{ old('contrat_clause_libre', $campagne->contrat_clause_libre) }}</textarea>
            </div>
            <input type="hidden" name="contrat_republier" value="0">
            <div class="form-check mb-3">
                <input type="checkbox" name="contrat_republier" value="1" class="form-check-input" id="contrat_republier">
                <label class="form-check-label" for="contrat_republier"><strong>Republier le contrat</strong> — nouvelle date limite de 5 jours pour accepter/refuser et réinitialisation des réponses en attente.</label>
            </div>
            @if($campagne->contrat_publie_at)
                <p class="small text-muted mb-0">Dernière publication : {{ $campagne->contrat_publie_at->format('d/m/Y H:i') }}</p>
            @endif

            <hr class="my-4">
            <h6 class="text-primary">Remise sur les ventes</h6>
            <p class="text-muted small">Pendant la campagne active, le prix catalogue peut être réduit selon le pourcentage. Choisissez si la remise s’applique à tous les types de cartes ou seulement à certains.</p>
            <div class="mb-3">
                <label class="form-label">Remise (%)</label>
                <input type="number" name="remise_pourcentage" class="form-control" value="{{ old('remise_pourcentage', $campagne->remise_pourcentage) }}" min="0" max="100" step="0.01" placeholder="0 = pas de remise">
            </div>
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
                    <p class="text-muted small mb-0">Aucun type de carte.</p>
                    @endforelse
                </div>
                @error('remise_types_cartes')<small class="text-danger d-block">{{ $message }}</small>@enderror
            </div>

            <hr class="my-4">
            <h6 class="text-primary">Coût / aide hebdomadaire commerciaux</h6>
            <p class="text-muted small">Montants hebdomadaires (carburant + crédit téléphonique). Les bénéficiaires sont ceux définis ci-dessus dans « Commerciaux engagés ».</p>
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
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer la campagne</button>
            <a href="{{ route('admin.campagnes.index') }}" class="btn btn-secondary">Annuler</a>
        </form>

        <hr class="my-4">
        <h6 class="text-primary">Articles du contrat de prestation</h6>
        <p class="text-muted small">Texte affiché aux commerciaux entre l’en-tête et le bloc « Rémunération et aides ». Vous pouvez modifier, ajouter ou supprimer des articles.</p>
        @if(session('success_article'))
            <div class="alert alert-success py-2 small">{{ session('success_article') }}</div>
        @endif

        @foreach($campagne->contratArticles as $article)
            <div class="border rounded p-3 mb-3 bg-light">
                <form method="POST" action="{{ route('admin.campagnes.contrat-articles.update', [$campagne, $article]) }}" class="mb-2">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <label class="form-label small mb-0">Titre</label>
                        <input type="text" name="titre" class="form-control form-control-sm" value="{{ $article->titre }}" required maxlength="255">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small mb-0">Contenu</label>
                        <textarea name="contenu" class="form-control form-control-sm" rows="5" required maxlength="50000">{{ $article->contenu }}</textarea>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-sm btn-primary">Enregistrer cet article</button>
                    </div>
                </form>
                <form method="POST" action="{{ route('admin.campagnes.contrat-articles.destroy', [$campagne, $article]) }}" class="d-inline" onsubmit="return confirm('Supprimer cet article ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer cet article</button>
                </form>
            </div>
        @endforeach

        <div class="border rounded p-3 border-primary border-2">
            <h6 class="small text-uppercase text-muted mb-2">Nouvel article</h6>
            <form method="POST" action="{{ route('admin.campagnes.contrat-articles.store', $campagne) }}">
                @csrf
                <div class="mb-2">
                    <label class="form-label small mb-0">Titre</label>
                    <input type="text" name="titre" class="form-control form-control-sm" value="{{ old('titre') }}" required maxlength="255" placeholder="Ex. Article 4 : …">
                </div>
                <div class="mb-2">
                    <label class="form-label small mb-0">Contenu</label>
                    <textarea name="contenu" class="form-control form-control-sm" rows="4" required maxlength="50000" placeholder="Texte de l’article">{{ old('contenu') }}</textarea>
                </div>
                <button type="submit" class="btn btn-sm btn-success">Ajouter l’article</button>
            </form>
        </div>
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
