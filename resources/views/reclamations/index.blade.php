@extends('layouts.app')

@section('title', 'Réclamations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Réclamations</h4>
    <a href="{{ route('reclamations.create') }}" class="btn btn-primary">Nouvelle réclamation</a>
</div>

<form method="GET" class="mb-3 d-flex gap-2 flex-wrap">
    <select name="statut" class="form-select" style="max-width: 150px;">
        <option value="">Tous les statuts</option>
        <option value="ouvert" {{ request('statut') === 'ouvert' ? 'selected' : '' }}>Ouvert</option>
        <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
        <option value="resolu" {{ request('statut') === 'resolu' ? 'selected' : '' }}>Résolu</option>
    </select>
    <select name="type" class="form-select" style="max-width: 150px;">
        <option value="">Tous les types</option>
        <option value="activation" {{ request('type') === 'activation' ? 'selected' : '' }}>Activation</option>
        <option value="mot_de_passe" {{ request('type') === 'mot_de_passe' ? 'selected' : '' }}>Mot de passe</option>
        <option value="rechargement" {{ request('type') === 'rechargement' ? 'selected' : '' }}>Rechargement</option>
    </select>
    <button type="submit" class="btn btn-secondary">Filtrer</button>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Créé par</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reclamations as $r)
                <tr>
                    <td>{{ $r->created_at->format('d/m/Y') }}</td>
                    <td>{{ $r->client->prenom }} {{ $r->client->nom }}</td>
                    <td>{{ str_replace('_', ' ', $r->type) }}</td>
                    <td>
                        <span class="badge bg-{{ match($r->statut) { 'ouvert' => 'warning', 'en_cours' => 'info', default => 'success' } }}">
                            {{ $r->statut }}
                        </span>
                    </td>
                    <td>{{ $r->user->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('reclamations.edit', $r) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                    </td>
                </tr>
                @endforeach
                @if($reclamations->isEmpty())
                <tr><td colspan="6" class="text-center py-4">Aucune réclamation.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    @if($reclamations->hasPages())
    <div class="card-footer">{{ $reclamations->links() }}</div>
    @endif
</div>
@endsection
