@extends('layouts.app')

@section('title', 'Modifier agence')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Modifier {{ $agence->nom }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.agences.update', $agence) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nom *</label>
                <input type="text" name="nom" class="form-control" value="{{ old('nom', $agence->nom) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse</label>
                <input type="text" name="adresse" class="form-control" value="{{ old('adresse', $agence->adresse) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Chef d'agence</label>
                <select name="chef_id" class="form-select">
                    <option value="">— Aucun —</option>
                    @foreach($chefs as $c)
                    <option value="{{ $c->id }}" {{ $agence->chef_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('admin.agences.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
