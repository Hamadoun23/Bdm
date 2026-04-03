@extends('layouts.app')

@section('title', 'Modifier le client')

@section('content')
@php $clientVerrouille = ! $client->peutEtreModifieOuSupprimeParCommercial(); @endphp
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Modifier les informations du client</h5>
            </div>
            <div class="card-body">
                @if($clientVerrouille)
                <div class="alert alert-secondary small">Cette fiche client a été créée il y a plus de {{ \App\Models\Client::DELAI_SUPPRESSION_COMMERCIAL_HEURES }} h : consultation seule.</div>
                @endif
                <p class="small text-muted">Corrigez les erreurs éventuelles. Le type de carte vendu n’est pas modifiable ici.</p>
                <div class="mb-3 p-2 bg-light rounded small">
                    <strong>Type de carte (vente)</strong> :
                    <span class="badge bg-info">{{ $client->typeCarte?->code ?? '?' }}</span>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger py-2">{{ session('error') }}</div>
                @endif
                <form method="post" action="{{ route('commercial.clients.update', $client) }}" enctype="multipart/form-data" @if($clientVerrouille) onsubmit="return false;" @endif>
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prénom *</label>
                            <input type="text" class="form-control" name="prenom" value="{{ old('prenom', $client->prenom) }}" required maxlength="100" @if($clientVerrouille) readonly @endif>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom *</label>
                            <input type="text" class="form-control" name="nom" value="{{ old('nom', $client->nom) }}" required maxlength="100" @if($clientVerrouille) readonly @endif>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" name="telephone" value="{{ old('telephone', $client->telephone) }}" maxlength="20" @if($clientVerrouille) readonly @endif>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Pièce d’identité <span class="text-muted fw-normal">(nouveau fichier optionnel)</span></label>
                            <input type="file" class="form-control" name="carte_identite" accept="image/jpeg,image/png,image/gif,image/webp,.pdf,application/pdf" @if($clientVerrouille) disabled @endif>
                            <small class="text-muted">JPG, PNG, GIF, WebP, PDF — max. 10 Mo.</small>
                            @if($client->carte_identite)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/'.$client->carte_identite) }}" target="_blank" rel="noopener" class="small">Fichier actuel</a>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ville</label>
                            <input type="text" class="form-control" name="ville" value="{{ old('ville', $client->ville) }}" maxlength="100" @if($clientVerrouille) readonly @endif>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quartier</label>
                            <input type="text" class="form-control" name="quartier" value="{{ old('quartier', $client->quartier) }}" maxlength="100" @if($clientVerrouille) readonly @endif>
                        </div>
                    </div>
                    <div class="mt-4 d-grid gap-2">
                        @if($clientVerrouille)
                            <button type="button" class="btn btn-secondary" disabled>Enregistrer</button>
                            <span class="text-muted small">Fiche verrouillée ({{ \App\Models\Client::DELAI_SUPPRESSION_COMMERCIAL_HEURES }} h)</span>
                        @else
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        @endif
                        <a href="{{ route('ventes.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
                <hr class="my-4">
                @if($client->peutEtreModifieOuSupprimeParCommercial())
                    <p class="small text-danger mb-2">Supprimer définitivement cette fiche et les ventes liées (possible pendant {{ \App\Models\Client::DELAI_SUPPRESSION_COMMERCIAL_HEURES }} h après création uniquement).</p>
                    <form method="post" action="{{ route('commercial.clients.destroy', $client) }}" onsubmit="return confirm('Supprimer ce client et toutes les ventes associées ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer la fiche client</button>
                    </form>
                @else
                    <p class="small text-muted mb-2">Suppression par vos soins :</p>
                    <span class="btn btn-outline-secondary btn-sm disabled" tabindex="-1" title="Suppression impossible après {{ \App\Models\Client::DELAI_SUPPRESSION_COMMERCIAL_HEURES }} h.">Supprimer la fiche client</span>
                    <p class="small text-muted mt-2 mb-0">Contactez l’administration si besoin.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
