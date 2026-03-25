@extends('layouts.app')

@section('title', 'Modifier réclamation')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Réclamation #{{ $reclamation->id }}</h5>
    </div>
    <div class="card-body">
        <p><strong>Client :</strong> {{ $reclamation->client->prenom }} {{ $reclamation->client->nom }}</p>
        <p><strong>Type :</strong> {{ str_replace('_', ' ', $reclamation->type) }}</p>
        <p><strong>Description :</strong> {{ $reclamation->description ?? '-' }}</p>

        <form method="POST" action="{{ route('reclamations.update', $reclamation) }}" class="mt-3">
            @csrf
            @method('PUT')
            <div class="mb-2">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select" style="max-width: 200px;">
                    <option value="ouvert" {{ $reclamation->statut === 'ouvert' ? 'selected' : '' }}>Ouvert</option>
                    <option value="en_cours" {{ $reclamation->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="resolu" {{ $reclamation->statut === 'resolu' ? 'selected' : '' }}>Résolu</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
<a href="{{ route('reclamations.index') }}" class="btn btn-outline-secondary mt-2">Retour</a>
@endsection
