@extends('layouts.app')

@section('title', 'Fiche reporting téléphonique')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="mb-0">Fiche de reporting téléopératrice</h4>
    <a href="{{ route('commercial.telephonique.index') }}" class="btn btn-outline-secondary">← Historique</a>
</div>

<p class="text-muted small">Une fiche par jour. Les chiffres sont enregistrés pour la date indiquée (modifiable si vous devez compléter une journée passée).</p>

@if($campagneActive)
<p class="small mb-2"><span class="text-muted">Campagne active :</span> <strong>{{ $campagneActive->nom }}</strong> — les types de cartes ci-dessous correspondent à cette campagne.</p>
@else
<div class="alert alert-warning small">Aucune campagne active pour votre agence : la section « types de cartes » est vide. Contactez l’administrateur.</div>
@endif

@php $rapportVerrouille = $rapport && ! $rapport->peutEtreModifieOuSupprime(); @endphp
@if($rapportVerrouille)
<div class="alert alert-secondary small mb-3">Cette fiche a été enregistrée il y a plus de 48 h : consultation seule. Pour saisir une autre journée, modifiez la date ci-dessous.</div>
@endif

<form method="POST" action="{{ route('commercial.telephonique.store') }}" class="card shadow-sm" id="form-reporting-tel" @if($rapportVerrouille) onsubmit="return false;" @endif>
    @csrf
    <div class="card-body">
        <h6 class="text-uppercase text-muted small border-bottom pb-2 mb-3">1. Identification</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Date du reporting *</label>
                <input type="date" name="date_rapport" id="date_rapport" class="form-control @error('date_rapport') is-invalid @enderror" value="{{ old('date_rapport', $dateRapport) }}" required @if($rapportVerrouille) onchange="if (this.value) { window.location.href = '{{ route('commercial.telephonique.create') }}?date=' + encodeURIComponent(this.value); }" @endif>
                @error('date_rapport')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 d-flex align-items-end">
                @php $u = auth()->user(); @endphp
                <p class="small text-muted mb-0">Téléopératrice : <strong>{{ $u->prenom ? trim($u->prenom.' '.$u->name) : $u->name }}</strong></p>
            </div>
        </div>

        <h6 class="text-uppercase text-muted small border-bottom pb-2 mb-3">2. Activité journalière</h6>
        <div class="row g-2 mb-4">
            <div class="col-6 col-md-3">
                <label class="form-label small" for="appels_emis">Appels émis *</label>
                <input type="number" name="appels_emis" id="appels_emis" min="0" step="1" class="form-control form-control-sm @error('appels_emis') is-invalid @enderror" value="{{ old('appels_emis', $rapport?->appels_emis ?? 0) }}" required @if($rapportVerrouille) readonly @endif>
                @error('appels_emis')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small" for="appels_joignables">Joignables *</label>
                <input type="number" name="appels_joignables" id="appels_joignables" min="0" step="1" class="form-control form-control-sm @error('appels_joignables') is-invalid @enderror" value="{{ old('appels_joignables', $rapport?->appels_joignables ?? 0) }}" required @if($rapportVerrouille) readonly @endif>
                @error('appels_joignables')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted">Non joignables</label>
                <div class="form-control form-control-sm bg-light text-muted" id="display_non_joignables" tabindex="-1">{{ $rapport ? max(0, $rapport->appels_emis - $rapport->appels_joignables) : 0 }}</div>
                <span class="small text-muted">Calculé : émis − joignables</span>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted">Taux de joignabilité</label>
                <div class="form-control form-control-sm bg-light text-muted" id="display_taux_joignabilite" tabindex="-1">@if($rapport && $rapport->appels_emis > 0){{ number_format((float) $rapport->taux_joignabilite, 2, ',', ' ') }} %@else—@endif</div>
                <span class="small text-muted">Calculé automatiquement</span>
            </div>
        </div>

        <h6 class="text-uppercase text-muted small border-bottom pb-2 mb-3">3. Résultats des appels</h6>
        <div class="row g-2 mb-4">
            <div class="col-6 col-md-4">
                <label class="form-label small">Clients intéressés (nombre) *</label>
                <input type="number" name="clients_interesses_nombre" id="clients_interesses_nombre" min="0" step="1" class="form-control form-control-sm" value="{{ old('clients_interesses_nombre', $rapport?->clients_interesses_nombre ?? 0) }}" required @if($rapportVerrouille) readonly @endif>
            </div>
            <div class="col-6 col-md-4">
                <label class="form-label small">Clients déjà servis — cartes récupérées (nombre) *</label>
                <input type="number" name="clients_deja_servis_nombre" id="clients_deja_servis_nombre" min="0" step="1" class="form-control form-control-sm" value="{{ old('clients_deja_servis_nombre', $rapport?->clients_deja_servis_nombre ?? 0) }}" required @if($rapportVerrouille) readonly @endif>
            </div>
        </div>

        <h6 class="text-uppercase text-muted small border-bottom pb-2 mb-3">4. Type de carte proposée (nombre par type, campagne en cours)</h6>
        @if($typesCampagne->isEmpty())
        <p class="text-muted small mb-0">Aucun type de carte disponible pour cette campagne — complétez les autres sections puis enregistrez.</p>
        @else
        <div class="row g-2 mb-4">
            @foreach($typesCampagne as $tc)
            <div class="col-6 col-md-4 col-lg-3">
                <label class="form-label small">{{ $tc->code }} <span class="text-muted">({{ number_format($tc->prix) }} F)</span> *</label>
                <input type="number" name="propose[{{ $tc->id }}]" min="0" step="1" class="form-control form-control-sm @error('propose.'.$tc->id) is-invalid @enderror" value="{{ old('propose.'.$tc->id, $rapport?->nombreProposePourType((int) $tc->id) ?? 0) }}" required @if($rapportVerrouille) readonly @endif>
                @error('propose.'.$tc->id)<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            @endforeach
        </div>
        @endif

        <h6 class="text-uppercase text-muted small border-bottom pb-2 mb-3">5. Appels non joignables — analyse</h6>
        <p class="small text-muted mb-2">Le total des quatre cases ci-dessous ne doit pas dépasser le <strong>non joignable</strong> de la section 2 (émis − joignables).</p>
        @error('nj_analyse')
        <div class="alert alert-danger py-2 small mb-2">{{ $message }}</div>
        @enderror
        <div id="nj_analyse_feedback" class="alert alert-warning py-2 small mb-2 d-none" role="alert"></div>
        <div class="row g-2 mb-3">
            <div class="col-6 col-md-3">
                <label class="form-label small">Répondeur *</label>
                <input type="number" name="nj_repondeur" id="nj_repondeur" min="0" step="1" class="form-control form-control-sm nj-analyse-input @error('nj_analyse') is-invalid @enderror" value="{{ old('nj_repondeur', $rapport?->nj_repondeur ?? 0) }}" required @if($rapportVerrouille) readonly @endif>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small">N° erroné *</label>
                <input type="number" name="nj_numero_errone" id="nj_numero_errone" min="0" step="1" class="form-control form-control-sm nj-analyse-input @error('nj_analyse') is-invalid @enderror" value="{{ old('nj_numero_errone', $rapport?->nj_numero_errone ?? 0) }}" required @if($rapportVerrouille) readonly @endif>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small">Hors réseau *</label>
                <input type="number" name="nj_hors_reseau" id="nj_hors_reseau" min="0" step="1" class="form-control form-control-sm nj-analyse-input @error('nj_analyse') is-invalid @enderror" value="{{ old('nj_hors_reseau', $rapport?->nj_hors_reseau ?? 0) }}" required @if($rapportVerrouille) readonly @endif>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small">Autres (nb) *</label>
                <input type="number" name="nj_autres_nombre" id="nj_autres_nombre" min="0" step="1" class="form-control form-control-sm nj-analyse-input @error('nj_analyse') is-invalid @enderror" value="{{ old('nj_autres_nombre', $rapport?->nj_autres_nombre ?? 0) }}" required @if($rapportVerrouille) readonly @endif>
            </div>
            <div class="col-12">
                <label class="form-label small">Autres (précision) @if(!$rapportVerrouille)<span class="text-muted fw-normal" id="nj_prec_hint">— obligatoire si « Autres (nb) » &gt; 0</span>@endif</label>
                <input type="text" name="nj_autres_precision" id="nj_autres_precision" class="form-control form-control-sm @error('nj_autres_precision') is-invalid @enderror" value="{{ old('nj_autres_precision', $rapport?->nj_autres_precision) }}" maxlength="500" autocomplete="off" @if($rapportVerrouille) readonly @endif>
                @error('nj_autres_precision')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-light">
        @if($rapportVerrouille)
            <button type="button" class="btn btn-secondary" disabled>Enregistrer la fiche</button>
            <span class="text-muted small ms-2">Fiche verrouillée (48 h)</span>
        @else
            <button type="submit" class="btn btn-primary">Enregistrer la fiche</button>
        @endif
    </div>
</form>
@push('scripts')
<script>
(function () {
    var verrouille = @json($rapportVerrouille);
    var form = document.getElementById('form-reporting-tel');
    var emis = document.getElementById('appels_emis');
    var joign = document.getElementById('appels_joignables');
    var outNj = document.getElementById('display_non_joignables');
    var outTaux = document.getElementById('display_taux_joignabilite');
    function getCapNonJoignables() {
        if (!emis || !joign) return 0;
        var e = parseInt(emis.value, 10) || 0;
        var j = parseInt(joign.value, 10) || 0;
        if (j > e) j = e;
        return Math.max(0, e - j);
    }

    function getNjAnalyseSomme() {
        var ids = ['nj_repondeur', 'nj_numero_errone', 'nj_hors_reseau', 'nj_autres_nombre'];
        var s = 0;
        for (var i = 0; i < ids.length; i++) {
            var el = document.getElementById(ids[i]);
            s += el ? (parseInt(el.value, 10) || 0) : 0;
        }
        return s;
    }

    var njAnalyseFeedback = document.getElementById('nj_analyse_feedback');
    var njRepEl = document.getElementById('nj_repondeur');

    function syncNjAnalyseUi() {
        if (verrouille || !njAnalyseFeedback) return;
        var cap = getCapNonJoignables();
        var sum = getNjAnalyseSomme();
        var over = sum > cap;
        document.querySelectorAll('.nj-analyse-input').forEach(function (inp) {
            inp.classList.toggle('is-invalid', over);
        });
        if (over) {
            njAnalyseFeedback.textContent = 'Total section 5 : ' + sum + ' — maximum autorisé (non joignables) : ' + cap + '. Ajustez les montants.';
            njAnalyseFeedback.classList.remove('d-none');
        } else {
            njAnalyseFeedback.classList.add('d-none');
            njAnalyseFeedback.textContent = '';
        }
        if (njRepEl) njRepEl.setCustomValidity('');
    }

    if (emis && joign && outNj && outTaux) {
        function sync() {
            var e = parseInt(emis.value, 10) || 0;
            var j = parseInt(joign.value, 10) || 0;
            if (j > e) j = e;
            var nj = Math.max(0, e - j);
            outNj.textContent = String(nj);
            if (e > 0) {
                outTaux.textContent = ((j / e) * 100).toFixed(2).replace('.', ',') + ' %';
            } else {
                outTaux.textContent = '—';
            }
            syncNjAnalyseUi();
        }
        @unless($rapportVerrouille)
        emis.addEventListener('input', sync);
        joign.addEventListener('input', sync);
        sync();
        @endunless
    }

    var autresNbEl = document.getElementById('nj_autres_nombre');
    var precEl = document.getElementById('nj_autres_precision');
    function syncPrecRequired() {
        if (!autresNbEl || !precEl || verrouille) return;
        var n = parseInt(autresNbEl.value, 10) || 0;
        if (n > 0) {
            precEl.setAttribute('required', 'required');
        } else {
            precEl.removeAttribute('required');
            precEl.classList.remove('is-invalid');
        }
    }
    if (!verrouille) {
        document.querySelectorAll('.nj-analyse-input').forEach(function (inp) {
            inp.addEventListener('input', syncNjAnalyseUi);
            inp.addEventListener('change', syncNjAnalyseUi);
        });
    }

    if (autresNbEl && precEl && !verrouille) {
        autresNbEl.addEventListener('input', syncPrecRequired);
        autresNbEl.addEventListener('change', syncPrecRequired);
        syncPrecRequired();
    }

    if (emis && joign && !verrouille) {
        syncNjAnalyseUi();
    }

    if (form && !verrouille) {
        form.addEventListener('submit', function (e) {
            syncNjAnalyseUi();
            var capNj = getCapNonJoignables();
            var sumNj = getNjAnalyseSomme();
            if (njRepEl) njRepEl.setCustomValidity('');
            if (sumNj > capNj) {
                e.preventDefault();
                if (njRepEl) {
                    njRepEl.setCustomValidity('La somme des quatre catégories (' + sumNj + ') ne peut pas dépasser les non joignables (' + capNj + ').');
                    njRepEl.reportValidity();
                }
                return;
            }
            syncPrecRequired();
            var n = autresNbEl ? (parseInt(autresNbEl.value, 10) || 0) : 0;
            if (precEl) {
                precEl.setCustomValidity('');
            }
            if (n > 0 && precEl && (!precEl.value || !String(precEl.value).trim())) {
                e.preventDefault();
                precEl.setCustomValidity('Indiquez une précision lorsque « Autres (nb) » est supérieur à 0.');
                precEl.reportValidity();
                return;
            }
            if (!form.checkValidity()) {
                e.preventDefault();
                form.reportValidity();
            }
        });
        if (precEl) {
            precEl.addEventListener('input', function () {
                this.setCustomValidity('');
            });
        }
    }
})();
</script>
@endpush
@endsection
