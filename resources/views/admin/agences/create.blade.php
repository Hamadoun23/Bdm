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
                <label class="form-label">Numérotation (ordre d’affichage) *</label>
                <input type="number" name="ordre" class="form-control" min="0" value="{{ old('ordre', $ordreSuggest) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nom *</label>
                <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
            <a href="{{ route('admin.agences.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
