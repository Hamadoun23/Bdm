@extends('layouts.app')

@section('title', 'Nouvelle agence')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Nouvelle agence</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.agences.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nom *</label>
                <input type="text" name="nom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse</label>
                <input type="text" name="adresse" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Chef d'agence</label>
                <select name="chef_id" class="form-select">
                    <option value="">— Sans chef pour l'instant —</option>
                    @foreach($chefs as $c)
                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->email }})</option>
                    @endforeach
                </select>
                <small class="text-muted">Optionnel. Si sélectionné, le chef sera assigné à cette agence.</small>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
            <a href="{{ route('admin.agences.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
