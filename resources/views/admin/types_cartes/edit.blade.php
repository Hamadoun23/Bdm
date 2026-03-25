@extends('layouts.app')

@section('title', 'Modifier type de carte')

@section('content')
<div class="card shadow-sm col-md-8">
    <div class="card-header"><h5 class="mb-0">Modifier : {{ $typeCarte->code }}</h5></div>
    <div class="card-body">
        <p class="text-muted small">Code : <code>{{ $typeCarte->code }}</code> (non modifiable)</p>
        <form method="POST" action="{{ route('admin.types-cartes.update', $typeCarte) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Prix conseillé (FCFA) *</label>
                <input type="number" name="prix" class="form-control" value="{{ old('prix', $typeCarte->prix) }}" min="0" required>
            </div>
            <input type="hidden" name="actif" value="0">
            <div class="form-check mb-3">
                <input type="checkbox" name="actif" value="1" class="form-check-input" id="actif" {{ old('actif', $typeCarte->actif) ? 'checked' : '' }}>
                <label class="form-check-label" for="actif">Actif</label>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('admin.types-cartes.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
