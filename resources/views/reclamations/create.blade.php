@extends('layouts.app')

@section('title', 'Nouvelle réclamation')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Nouvelle réclamation</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('reclamations.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Client *</label>
                <select name="client_id" class="form-select" required>
                    <option value="">Sélectionner un client</option>
                    @foreach($clients as $c)
                    <option value="{{ $c->id }}">{{ $c->prenom }} {{ $c->nom }} - {{ $c->telephone ?? '-' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Type *</label>
                <select name="type" class="form-select" required>
                    <option value="activation">Activation</option>
                    <option value="mot_de_passe">Mot de passe</option>
                    <option value="rechargement">Rechargement</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
            <a href="{{ route('reclamations.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
