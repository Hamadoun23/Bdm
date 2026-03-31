@extends('layouts.app')

@section('title', 'Nouvel utilisateur')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Nouvel utilisateur (Commercial ou Direction)</h5>
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
                    <small class="text-muted">Identifiant de connexion (unique).</small>
                    @error('telephone')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3" id="direction-email-wrap" style="display: {{ old('role', 'commercial') === 'direction' ? 'block' : 'none' }};">
                <label class="form-label">E-mail <span class="text-muted">(Direction — facultatif)</span></label>
                <input type="email" name="email" id="direction-email-input" class="form-control" value="{{ old('email') }}" autocomplete="email">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="password">Mot de passe *</label>
                    @include('layouts.partials.password-input-group', [
                        'name' => 'password',
                        'id' => 'password',
                        'required' => true,
                        'autocomplete' => 'new-password',
                        'inputClass' => $errors->has('password') ? 'is-invalid' : '',
                    ])
                    @error('password')
                        <div class="invalid-feedback d-block text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="password_confirmation">Confirmer mot de passe *</label>
                    @include('layouts.partials.password-input-group', [
                        'name' => 'password_confirmation',
                        'id' => 'password_confirmation',
                        'required' => true,
                        'autocomplete' => 'new-password',
                        'inputClass' => ($errors->has('password_confirmation') || $passwordConfirmMismatch) ? 'is-invalid' : '',
                    ])
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Rôle *</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="commercial" @selected(old('role', 'commercial') === 'commercial')>Commercial</option>
                    <option value="direction" @selected(old('role') === 'direction')>Direction (lecture &amp; exports)</option>
                </select>
            </div>
            <div class="mb-3" id="agence-wrap">
                <label class="form-label">Agence <span class="text-muted" id="agence-hint">*</span></label>
                <select name="agence_id" id="agence_id" class="form-select @error('agence_id') is-invalid @enderror">
                    <option value="">— Sélectionner —</option>
                    @foreach($agences as $a)
                    <option value="{{ $a->id }}" @selected(old('agence_id') == $a->id)>{{ $a->nom }}</option>
                    @endforeach
                </select>
                @error('agence_id')<div class="text-danger small">{{ $message }}</div>@enderror
                <small class="text-muted">Obligatoire pour un commercial uniquement.</small>
            </div>
            <div id="contrat-commercial-wrap" style="display: {{ old('role', 'commercial') === 'commercial' ? 'block' : 'none' }};">
                <h6 class="text-muted small text-uppercase">Contrat de prestation (commercial)</h6>
                <div class="mb-3">
                    <label class="form-label">Adresse (contrat)</label>
                    <textarea name="adresse_contrat" class="form-control" rows="2">{{ old('adresse_contrat') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Réf. pièce d’identité</label>
                    <input type="text" name="piece_identite_ref" class="form-control" value="{{ old('piece_identite_ref') }}">
                </div>
            </div>
            <div class="mb-3">
                <input type="hidden" name="actif" value="0">
                <div class="form-check">
                    <input type="checkbox" name="actif" value="1" class="form-check-input" id="actif" {{ old('actif', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="actif">Compte actif (peut se connecter)</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
<script>
(function () {
    var role = document.getElementById('role');
    var emailWrap = document.getElementById('direction-email-wrap');
    var emailInput = document.getElementById('direction-email-input');
    var agenceSelect = document.getElementById('agence_id');
    var agenceHint = document.getElementById('agence-hint');
    function sync() {
        var isDirection = role && role.value === 'direction';
        if (emailWrap) emailWrap.style.display = isDirection ? 'block' : 'none';
        if (emailInput && !isDirection) emailInput.value = '';
        if (agenceSelect) {
            agenceSelect.disabled = isDirection;
            if (isDirection) agenceSelect.value = '';
        }
        if (agenceHint) agenceHint.style.display = isDirection ? 'none' : 'inline';
        var contratWrap = document.getElementById('contrat-commercial-wrap');
        if (contratWrap) contratWrap.style.display = isDirection ? 'none' : 'block';
    }
    if (role) role.addEventListener('change', sync);
    sync();
})();
</script>
@endsection
