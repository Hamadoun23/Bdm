@extends('layouts.app')

@section('title', 'Nouveau type de carte')

@section('content')
<div class="card shadow-sm col-md-8">
    <div class="card-header bg-primary text-white"><h5 class="mb-0">Nouveau type de carte</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.types-cartes.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Code *</label>
                <input type="text" name="code" class="form-control" value="{{ old('code') }}" required placeholder="Ex: VIP, ADAN, GDA">
                <small class="text-muted">Identifiant unique (lettres, chiffres ; sera normalisé en majuscules)</small>
            </div>
            <input type="hidden" name="actif" value="0">
            <div class="form-check mb-3">
                <input type="checkbox" name="actif" value="1" class="form-check-input" id="actif" {{ old('actif', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="actif">Actif (visible pour les ventes)</label>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
            <a href="{{ route('admin.types-cartes.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
