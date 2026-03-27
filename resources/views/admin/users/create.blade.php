@extends('layouts.app')

@section('title', 'Nouvel utilisateur')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Nouvel utilisateur (Commercial ou Chef d'agence)</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            @php
                $passwordError = $errors->first('password');
                $passwordConfirmMismatch = $passwordError && \Illuminate\Support\Str::contains(strtolower($passwordError), ['confirmation', 'confirm', 'correspond', 'match']);
            @endphp
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="{{ old('prenom') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Téléphone *</label>
                    <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}" required autocomplete="tel">
                    <small class="text-muted">Identifiant de connexion pour commercial / chef d’agence (unique).</small>
                    @error('telephone')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail <span class="text-muted">(facultatif)</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" autocomplete="email">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mot de passe *</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback d-block text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirmer mot de passe *</label>
                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror {{ $passwordConfirmMismatch ? 'is-invalid' : '' }}" autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Rôle *</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="commercial" @selected(old('role', 'commercial') === 'commercial')>Commercial</option>
                    <option value="chef_agence" @selected(old('role') === 'chef_agence')>Chef d'agence</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Agence *</label>
                <select name="agence_id" class="form-select" required>
                    <option value="">— Sélectionner —</option>
                    @foreach($agences as $a)
                    <option value="{{ $a->id }}" @selected(old('agence_id') == $a->id)>{{ $a->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <input type="hidden" name="actif" value="0">
                <div class="form-check">
                    <input type="checkbox" name="actif" value="1" class="form-check-input" id="actif" {{ old('actif', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="actif">Compte actif (peut se connecter)</label>
                </div>
                <small class="text-muted">Désactivez pour bloquer la connexion et les ventes (commercial / chef).</small>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
