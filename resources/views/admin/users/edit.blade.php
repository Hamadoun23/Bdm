@extends('layouts.app')

@section('title', 'Modifier utilisateur')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Modifier {{ $user->name }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="{{ old('prenom', $user->prenom) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Téléphone *</label>
                    <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $user->telephone) }}" required autocomplete="tel">
                    <small class="text-muted">Identifiant de connexion (numéro unique).</small>
                    @error('telephone')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail <span class="text-muted">(facultatif)</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" autocomplete="email">
            </div>
            <div class="mb-3">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-control" autocomplete="new-password">
                <small class="text-muted">Laisser vide pour conserver l'actuel</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmer mot de passe</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Rôle *</label>
                <select name="role" class="form-select" required>
                    <option value="commercial" {{ $user->role === 'commercial' ? 'selected' : '' }}>Commercial</option>
                    <option value="chef_agence" {{ $user->role === 'chef_agence' ? 'selected' : '' }}>Chef d'agence</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Agence *</label>
                <select name="agence_id" class="form-select" required>
                    <option value="">— Sélectionner —</option>
                    @foreach($agences as $a)
                    <option value="{{ $a->id }}" {{ $user->agence_id == $a->id ? 'selected' : '' }}>{{ $a->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <input type="hidden" name="actif" value="0">
                <div class="form-check">
                    <input type="checkbox" name="actif" value="1" class="form-check-input" id="actif" {{ old('actif', $user->actif) ? 'checked' : '' }}>
                    <label class="form-check-label" for="actif">Compte actif (peut se connecter)</label>
                </div>
                <small class="text-muted">Si désactivé : plus de connexion ni d’enregistrement de ventes pour ce profil.</small>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
