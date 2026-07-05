@extends('layouts.app')

@section('title', 'Transfert d’agence — '.$user->name)

@section('content')
@php
    $displayName = $user->prenom ? trim($user->prenom.' '.$user->name) : $user->name;
    $qFilters = array_filter([
        'du' => request('du'),
        'au' => request('au'),
        'campagne_id' => request('campagne_id'),
        'agence_id' => request('agence_id'),
        'return_campagne' => request('return_campagne'),
        'mode' => request('mode'),
    ], fn ($v) => $v !== null && $v !== '');
    $majProfilDefault = ($modeProfil ?? false) || old('maj_profil') || old('maj_profil') === '1';
@endphp
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <h4 class="mb-0">Transfert d’agence — {{ $displayName }}</h4>
        <p class="small text-muted mb-0">
            Agence actuelle du profil : <strong>{{ $user->agence->nom ?? '—' }}</strong>
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @if($returnCampagne ?? null)
            <a href="{{ route('admin.campagnes.show', ['campagne' => $returnCampagne, 'tab' => 'commerciaux']) }}" class="btn btn-outline-secondary btn-sm">← Retour campagne</a>
        @endif
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-secondary btn-sm">Fiche utilisateur</a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($returnCampagne ?? null)
<div class="alert alert-info py-2 small mb-3">
    <strong>Changement d’agence (cas le plus fréquent)</strong> — cochez uniquement
    « Mettre à jour l’agence du profil », choisissez la nouvelle agence, <em>sans</em> cocher de ventes.
    Les ventes déjà enregistrées restent rattachées à leur agence d’origine ; seules les <strong>prochaines ventes</strong> utiliseront la nouvelle agence.
</div>
@endif

<div class="card shadow-sm mb-3">
    <div class="card-header"><strong>Filtres (liste des ventes)</strong></div>
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            @if(request('return_campagne'))
                <input type="hidden" name="return_campagne" value="{{ request('return_campagne') }}">
            @endif
            @if(request('mode'))
                <input type="hidden" name="mode" value="{{ request('mode') }}">
            @endif
            <div class="col-md-2">
                <label class="form-label small mb-0">Du</label>
                <input type="date" name="du" class="form-control form-control-sm" value="{{ request('du') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Au</label>
                <input type="date" name="au" class="form-control form-control-sm" value="{{ request('au') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-0">Campagne</label>
                <select name="campagne_id" class="form-select form-select-sm">
                    <option value="">— Toutes —</option>
                    @foreach($campagnes as $c)
                        <option value="{{ $c->id }}" @selected((string) request('campagne_id') === (string) $c->id)>{{ $c->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-0">Agence (sur la vente)</label>
                <select name="agence_id" class="form-select form-select-sm">
                    <option value="">— Toutes —</option>
                    @foreach($agences as $a)
                        <option value="{{ $a->id }}" @selected((string) request('agence_id') === (string) $a->id)>{{ $a->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filtrer</button>
            </div>
        </form>
    </div>
</div>

<form method="POST" action="{{ route('admin.users.transfert-agence.apply', $user) }}" id="form-transfert-agence" class="card shadow-sm">
    @csrf
    @foreach($qFilters as $k => $v)
        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
    @endforeach
    <div class="card-header"><strong>Ventes existantes</strong> <span class="text-muted small">({{ $ventes->total() }} au total sur les filtres) — cochez seulement si vous voulez aussi déplacer l’historique</span></div>
    <div class="card-body p-0">
        @error('transfert')<div class="alert alert-danger m-3 mb-0">{{ $message }}</div>@enderror
        @error('vente_ids')<div class="alert alert-danger m-3 mb-0">{{ $message }}</div>@enderror
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:2.5rem;">
                            <input type="checkbox" class="form-check-input" id="check-all-ventes" title="Tout sélectionner sur cette page">
                        </th>
                        <th>Date</th>
                        <th>Campagne</th>
                        <th>Type</th>
                        <th>Agence (vente — figée)</th>
                        <th class="text-end">#</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventes as $v)
                    <tr>
                        <td>
                            <input type="checkbox" name="vente_ids[]" value="{{ $v->id }}" class="form-check-input vente-cb">
                        </td>
                        <td class="text-nowrap">{{ $v->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $v->campagne?->nom ?? '—' }}</td>
                        <td><span class="badge bg-secondary">{{ $v->typeCarte?->code ?? '—' }}</span></td>
                        <td>{{ $v->agence?->nom ?? '—' }}</td>
                        <td class="text-end"><code class="small">{{ $v->id }}</code></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Aucune vente pour ces filtres.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ventes->hasPages())
        <div class="card-body border-top py-2">
            {{ $ventes->links() }}
        </div>
        @endif
    </div>
    <div class="card-body border-top bg-light">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nouvelle agence *</label>
                <select name="agence_cible_id" class="form-select @error('agence_cible_id') is-invalid @enderror" required>
                    <option value="">— Choisir —</option>
                    @foreach($agences as $a)
                        <option value="{{ $a->id }}" @selected((string) old('agence_cible_id') === (string) $a->id)>{{ $a->nom }}</option>
                    @endforeach
                </select>
                @error('agence_cible_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Note (journal interne)</label>
                <textarea name="note" class="form-control" rows="2" maxlength="2000" placeholder="Optionnel">{{ old('note') }}</textarea>
            </div>
            <div class="col-12">
                <div class="form-check mb-2">
                    <input type="hidden" name="maj_profil" value="0">
                    <input type="checkbox" name="maj_profil" value="1" class="form-check-input" id="maj_profil" @checked($majProfilDefault)>
                    <label class="form-check-label" for="maj_profil">
                        <strong>Mettre à jour l’agence du profil</strong> (prochaines ventes)
                    </label>
                </div>
                <p class="small text-muted mb-0">
                    Laissez les ventes <em>non cochées</em> pour conserver l’historique sur l’ancienne agence.
                    Cochez des ventes uniquement si vous souhaitez aussi corriger rétroactivement leur agence.
                </p>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Appliquer le transfert</button>
        </div>
    </div>
</form>

<script>
(function () {
    var all = document.getElementById('check-all-ventes');
    var cbs = document.querySelectorAll('.vente-cb');
    if (all && cbs.length) {
        all.addEventListener('change', function () {
            cbs.forEach(function (cb) { cb.checked = all.checked; });
        });
    }
    var form = document.getElementById('form-transfert-agence');
    if (form) {
        form.addEventListener('submit', function (e) {
            var anyCb = Array.prototype.some.call(document.querySelectorAll('.vente-cb'), function (cb) { return cb.checked; });
            var maj = document.getElementById('maj_profil');
            var majProfil = maj && maj.checked;
            if (!anyCb && !majProfil) {
                e.preventDefault();
                alert('Cochez « Mettre à jour l’agence du profil » et/ou sélectionnez des ventes à réattribuer.');
                return;
            }
            var msg = majProfil && !anyCb
                ? 'Confirmer le changement d’agence du profil ? Les ventes existantes ne seront pas modifiées.'
                : 'Confirmer le transfert (profil et/ou ventes cochées) ?';
            if (!confirm(msg)) {
                e.preventDefault();
            }
        });
    }
})();
</script>
@endsection
