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
                    <small class="text-muted">Identifiant de connexion.</small>
                    @error('telephone')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3" id="direction-email-wrap" style="display: {{ old('role', $user->role) === 'direction' ? 'block' : 'none' }};">
                <label class="form-label">E-mail <span class="text-muted">(Direction — facultatif)</span></label>
                <input type="email" name="email" id="direction-email-input" class="form-control" value="{{ old('email', $user->email) }}" autocomplete="email">
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Nouveau mot de passe</label>
                @include('layouts.partials.password-input-group', [
                    'name' => 'password',
                    'id' => 'password',
                    'required' => false,
                    'autocomplete' => 'new-password',
                ])
                <small class="text-muted">Laisser vide pour conserver l'actuel</small>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password_confirmation">Confirmer mot de passe</label>
                @include('layouts.partials.password-input-group', [
                    'name' => 'password_confirmation',
                    'id' => 'password_confirmation',
                    'required' => false,
                    'autocomplete' => 'new-password',
                ])
            </div>
            <div class="mb-3">
                <label class="form-label">Rôle *</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="commercial" {{ old('role', $user->role) === 'commercial' ? 'selected' : '' }}>Commercial terrain</option>
                    <option value="commercial_telephonique" {{ old('role', $user->role) === 'commercial_telephonique' ? 'selected' : '' }}>Commercial téléphonique</option>
                    <option value="direction" {{ old('role', $user->role) === 'direction' ? 'selected' : '' }}>Direction</option>
                </select>
            </div>
            <div class="mb-3" id="agence-wrap">
                <label class="form-label">Agence <span class="text-muted" id="agence-hint">{{ in_array(old('role', $user->role), ['commercial', 'commercial_telephonique'], true) ? '*' : '' }}</span></label>
                <select name="agence_id" id="agence_id" class="form-select @error('agence_id') is-invalid @enderror">
                    <option value="">— Sélectionner —</option>
                    @foreach($agences as $a)
                    <option value="{{ $a->id }}" {{ (string) old('agence_id', $user->agence_id) === (string) $a->id ? 'selected' : '' }}>{{ $a->nom }}</option>
                    @endforeach
                </select>
                @error('agence_id')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div id="contrat-commercial-wrap" style="display: {{ in_array(old('role', $user->role), ['commercial', 'commercial_telephonique'], true) ? 'block' : 'none' }};">
                <h6 class="text-muted small text-uppercase">Contrat de prestation</h6>
                <div class="mb-3">
                    <label class="form-label">Adresse (contrat) — « Demeurant à »</label>
                    <textarea name="adresse_contrat" class="form-control" rows="2">{{ old('adresse_contrat', $user->adresse_contrat) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Réf. pièce d’identité</label>
                    <input type="text" name="piece_identite_ref" class="form-control" value="{{ old('piece_identite_ref', $user->piece_identite_ref) }}" placeholder="Nº CNI / passeport…">
                </div>
            </div>
            <div class="mb-3">
                <input type="hidden" name="actif" value="0">
                <div class="form-check">
                    <input type="checkbox" name="actif" value="1" class="form-check-input" id="actif" {{ old('actif', $user->actif) ? 'checked' : '' }}>
                    <label class="form-check-label" for="actif">Compte actif (peut se connecter)</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
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
        if (agenceHint) {
            agenceHint.style.display = isDirection ? 'none' : 'inline';
            agenceHint.textContent = isDirection ? '' : '*';
        }
        var contratWrap = document.getElementById('contrat-commercial-wrap');
        var terrainOuTel = role && (role.value === 'commercial' || role.value === 'commercial_telephonique');
        if (contratWrap) contratWrap.style.display = isDirection ? 'none' : (terrainOuTel ? 'block' : 'none');
    }
    if (role) role.addEventListener('change', sync);
    sync();
})();
</script>
@endsection
