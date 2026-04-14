@extends('layouts.app')

@section('title', 'Détail campagne')

@section('content')
@php $isDirectionDetail = $isDirectionDetail ?? false; @endphp
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Rapport détaillé : {{ $campagne->nom }}</h4>
    <a href="{{ $isDirectionDetail ? route('direction.campagnes.index') : route('admin.campagnes.index') }}" class="btn btn-outline-secondary">← Retour</a>
</div>

<div class="alert alert-light border mb-3 small mb-4">
    <strong>Raccourcis pilotage :</strong>
    <a href="{{ route('rapports.campagnes.synthese', $campagne) }}">Synthèse & graphiques</a>
    · <a href="{{ route('rapports.campagnes.ventes', $campagne) }}">Liste ventes</a>
    · <a href="{{ route('rapports.campagnes.clients', $campagne) }}">Clients</a>
    · <a href="{{ route('rapports.campagnes.reporting-telephonique', $campagne) }}">Reporting téléphonique</a>
</div>

@php $statut = $campagne->statut_effectif; @endphp

{{-- Informations générales --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <strong>Informations de la campagne</strong>
        <span class="badge fs-6
            @if($statut === 'en_cours') bg-success
            @elseif($statut === 'programmee') bg-info
            @elseif($statut === 'arretee') bg-warning text-dark
            @elseif($statut === 'annulee') bg-danger
            @else bg-secondary @endif
        ">
            @if($statut === 'en_cours') En cours
            @elseif($statut === 'programmee') Programmée
            @elseif($statut === 'arretee') Arrêtée
            @elseif($statut === 'annulee') Annulée
            @else Terminée @endif
        </span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted" style="width: 140px;">Période</th><td>{{ $campagne->date_debut->format('d/m/Y') }} → {{ $campagne->date_fin->format('d/m/Y') }}</td></tr>
                    <tr><th class="text-muted">Agences</th><td>{{ $campagne->toutes_agences ? 'Toutes les agences' : $campagne->agences->pluck('nom')->join(', ') }}</td></tr>
                    <tr><th class="text-muted">Prime du meilleur vendeur</th><td>{{ number_format($campagne->prime_meilleur_vendeur) }} FCFA</td></tr>
                    <tr><th class="text-muted">Aide hebdo.</th><td>
                        @if($campagne->aide_hebdo_active)
                            {{ number_format($campagne->aide_hebdo_montant) }} F / semaine (carburant {{ number_format($campagne->aide_hebdo_carburant) }} F + crédit {{ number_format($campagne->aide_hebdo_credit_tel) }} F).
                            @if($campagne->aide_hebdo_tous_commerciaux)
                                <span class="text-muted">Bénéficiaires : tous les commerciaux des agences concernées.</span>
                            @else
                                <span class="text-muted">Bénéficiaires sélectionnés :</span>
                                <ul class="mb-0 small mt-1">
                                    @forelse($campagne->beneficiairesAide as $u)
                                    <li>{{ $u->prenom ? trim($u->prenom.' '.$u->name) : $u->name }} — {{ $u->agence->nom ?? '?' }}</li>
                                    @empty
                                    <li class="text-muted">Aucun (à préciser en modification).</li>
                                    @endforelse
                                </ul>
                            @endif
                        @else
                            Non activée
                        @endif
                    </td></tr>
                    <tr><th class="text-muted">Créée le</th><td>{{ $campagne->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header"><strong>Contrat de prestation — signataires et réponses</strong></div>
    <div class="card-body">
        <p class="small text-muted">Émolument forfait : {{ number_format($campagne->contrat_emolument_forfait) }} F · Communication {{ number_format($campagne->contrat_forfait_communication) }} F · Déplacement {{ number_format($campagne->contrat_forfait_deplacement) }} F · Représentant : {{ $campagne->contrat_representant_nom }}</p>
        <p class="small text-muted mb-2">Articles du corps du contrat : <strong>{{ $campagne->contratArticles->count() }}</strong>@unless($isDirectionDetail) — <a href="{{ route('admin.campagnes.edit', $campagne) }}">Modifier les articles</a>@endunless</p>
        @if($campagne->contrat_publie_at)
            <p class="small text-muted">Publié le {{ $campagne->contrat_publie_at->format('d/m/Y H:i') }} — délai de réponse : 5 jours après cette date.</p>
        @endif
        <h6 class="mt-3">Signataires ({{ $campagne->signatairesContrat->count() }})</h6>
        <ul class="small mb-3">
            @forelse($campagne->signatairesContrat as $u)
                <li>{{ $u->prenom ? trim($u->prenom.' '.$u->name) : $u->name }} — {{ $u->agence->nom ?? '?' }}</li>
            @empty
                <li class="text-muted">Aucun — modifiez la campagne pour engager des commerciaux.</li>
            @endforelse
        </ul>
        <h6>Réponses au contrat</h6>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th>Commercial</th><th>Statut</th><th>Répondu le</th></tr></thead>
                <tbody>
                    @forelse($campagne->contratReponses as $rep)
                        @php
                            $verrou = $campagne->contrat_publie_at && $campagne->contrat_publie_at->copy()->addDays(5)->isPast();
                        @endphp
                        <tr>
                            <td>{{ $rep->user?->prenom ? trim($rep->user->prenom.' '.$rep->user->name) : $rep->user?->name }}</td>
                            <td>
                                @if($rep->statut === 'accepte')<span class="badge bg-success">Accepté</span>
                                @elseif($rep->statut === 'rejete')<span class="badge bg-danger">Refusé</span>
                                @else<span class="badge bg-secondary">En attente</span>@endif
                                @if($verrou && $rep->statut === 'en_attente')<span class="text-muted small"> (délai expiré)</span>@endif
                            </td>
                            <td>{{ $rep->repondu_at?->format('d/m/Y H:i') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-muted">Aucune ligne de réponse (enregistrez des signataires).</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($isDirectionDetail)
        <h6 class="mt-4">Texte du contrat (articles)</h6>
        @if($campagne->contratArticles->isEmpty())
        <p class="text-muted small mb-0">Aucun article enregistré.</p>
        @else
        <div class="accordion accordion-flush" id="directionContratArticles">
            @foreach($campagne->contratArticles->sortBy('sort_order') as $art)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed py-2 small" type="button" data-bs-toggle="collapse" data-bs-target="#dirArticle{{ $art->id }}">
                        {{ $art->titre }}
                    </button>
                </h2>
                <div id="dirArticle{{ $art->id }}" class="accordion-collapse collapse" data-bs-parent="#directionContratArticles">
                    <div class="accordion-body small text-body-secondary">{!! nl2br(e($art->contenu)) !!}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        @endif
    </div>
</div>

@if($campagne->aide_hebdo_active)
<div class="card shadow-sm mb-4">
    <div class="card-header"><strong>Versements aide hebdo (carburant / crédit) — accusés</strong></div>
    <div class="card-body">
        @unless($isDirectionDetail)
        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('admin.campagnes.versements.store', $campagne) }}" class="row g-2 align-items-end mb-4">
            @csrf
            <div class="col-md-3">
                <label class="form-label small mb-0">Commercial</label>
                <select name="user_id" class="form-select form-select-sm" required>
                    <option value="">—</option>
                    @foreach($campagne->signatairesContrat as $u)
                        <option value="{{ $u->id }}">{{ $u->prenom ? trim($u->prenom.' '.$u->name) : $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Semaine (lundi)</label>
                <input type="date" name="semaine_debut" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Carburant (F)</label>
                <input type="number" name="montant_carburant" class="form-control form-control-sm" value="{{ $campagne->aide_hebdo_carburant }}" min="0" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Crédit tel. (F)</label>
                <input type="number" name="montant_credit_tel" class="form-control form-control-sm" value="{{ $campagne->aide_hebdo_credit_tel }}" min="0" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">Enregistrer le versement</button>
            </div>
        </form>
        @else
        <p class="small text-muted mb-3">Lecture seule : suivi des versements et des accusés de réception.</p>
        @endunless
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th>Semaine</th><th>Commercial</th><th>Carburant</th><th>Crédit</th><th>Accusé</th>@unless($isDirectionDetail)<th></th>@endunless</tr></thead>
                <tbody>
                    @forelse($campagne->aideVersements->sortByDesc('semaine_debut') as $v)
                        <tr>
                            <td>{{ $v->semaine_debut->format('d/m/Y') }}</td>
                            <td>{{ $v->user?->prenom ? trim($v->user->prenom.' '.$v->user->name) : $v->user?->name }}</td>
                            <td>{{ number_format($v->montant_carburant) }}</td>
                            <td>{{ number_format($v->montant_credit_tel) }}</td>
                            <td>@if($v->accuse_at)<span class="badge bg-success">{{ $v->accuse_at->format('d/m/Y H:i') }}</span>@else<span class="badge bg-warning text-dark">En attente</span>@endif</td>
                            @unless($isDirectionDetail)
                            <td>
                                @if(!$v->accuse_at)
                                <form method="POST" action="{{ route('admin.campagnes.versements.destroy', [$campagne, $v]) }}" class="d-inline" onsubmit="return confirm('Supprimer ce versement ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Suppr.</button>
                                </form>
                                @endif
                            </td>
                            @endunless
                        </tr>
                    @empty
                        <tr><td colspan="{{ $isDirectionDetail ? 5 : 6 }}" class="text-muted">Aucun versement.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@php
    $preset = $preset ?? 'campagne';
    $periode_debut = $periode_debut ?? $campagne->date_debut;
    $periode_fin = $periode_fin ?? $campagne->date_fin;
    $periodeLib = match ($preset) {
        'semaine' => 'Semaine en cours (découpée selon les dates de campagne)',
        'mois' => 'Mois civil en cours (découpé selon les dates de campagne)',
        'perso' => 'Intervalle personnalisé',
        default => 'Toute la durée de la campagne',
    };
@endphp
<div class="card shadow-sm mb-4 border-primary border-opacity-25">
    <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center gap-2">
        <strong>Période d’analyse (ventes &amp; classement)</strong>
        <span class="badge bg-secondary">{{ $periode_debut->format('d/m/Y') }} → {{ $periode_fin->format('d/m/Y') }}</span>
    </div>
    <div class="card-body py-3">
        <form method="get" id="form-periode-campagne" class="row g-2 align-items-end mb-2">
            <div class="col-12 col-md-3">
                <label class="form-label small mb-0">Préréglage</label>
                <select name="periode" id="select-periode-preset" class="form-select form-select-sm">
                    <option value="campagne" @selected($preset === 'campagne')>Toute la campagne</option>
                    <option value="semaine" @selected($preset === 'semaine')>Semaine en cours</option>
                    <option value="mois" @selected($preset === 'mois')>Mois en cours</option>
                    <option value="perso" @selected($preset === 'perso')>Dates au choix</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small mb-0">Du</label>
                <input type="date" name="date_debut" class="form-control form-control-sm" value="{{ request('date_debut', $periode_debut->format('Y-m-d')) }}" @if($preset !== 'perso') disabled @endif>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small mb-0">Au</label>
                <input type="date" name="date_fin" class="form-control form-control-sm" value="{{ request('date_fin', $periode_fin->format('Y-m-d')) }}" @if($preset !== 'perso') disabled @endif>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">Appliquer</button>
            </div>
        </form>
        @push('scripts')
        <script>
        (function () {
            var sel = document.getElementById('select-periode-preset');
            var form = document.getElementById('form-periode-campagne');
            if (!sel || !form) return;
            sel.addEventListener('change', function () {
                var perso = sel.value === 'perso';
                form.querySelectorAll('[name="date_debut"],[name="date_fin"]').forEach(function (el) {
                    el.disabled = !perso;
                });
                if (!perso) form.submit();
            });
        })();
        </script>
        @endpush
        <p class="small text-muted mb-0"><strong>{{ $periodeLib }}</strong> — Les ventes prises en compte sont celles enregistrées sur la fenêtre affichée (recoupée avec les dates de campagne).</p>
    </div>
</div>

@php
    $tc = $telephoniqueCampagne ?? [
        'nb_fiches' => 0, 'appels_emis' => 0, 'appels_joignables' => 0, 'appels_non_joignables' => 0,
        'clients_interesses' => 0, 'clients_deja_servis' => 0,
    ];
    $qTelCampagne = [
        'campagne' => $campagne,
        'date_debut' => $periode_debut->format('Y-m-d'),
        'date_fin' => $periode_fin->format('Y-m-d'),
    ];
@endphp
<div class="card shadow-sm mb-4 border-info border-opacity-25">
    <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center gap-2">
        <strong>Reporting téléphonique (même période d’analyse)</strong>
        <a href="{{ route('rapports.campagnes.reporting-telephonique', $qTelCampagne) }}" class="btn btn-sm btn-outline-primary">Liste des fiches</a>
    </div>
    <div class="card-body py-3">
        <div class="row g-2 small">
            <div class="col-6 col-md-4">Fiches : <strong>{{ number_format($tc['nb_fiches']) }}</strong></div>
            <div class="col-6 col-md-4">Appels émis : <strong>{{ number_format($tc['appels_emis']) }}</strong></div>
            <div class="col-6 col-md-4">Joignables : <strong>{{ number_format($tc['appels_joignables']) }}</strong></div>
            <div class="col-6 col-md-4">Non joignables : <strong>{{ number_format($tc['appels_non_joignables']) }}</strong></div>
            <div class="col-6 col-md-4">Clients intéressés : <strong>{{ number_format($tc['clients_interesses']) }}</strong></div>
            <div class="col-6 col-md-4">Déjà servis : <strong>{{ number_format($tc['clients_deja_servis']) }}</strong></div>
        </div>
        <p class="small text-muted mb-0 mt-2">Périmètre aligné sur la synthèse campagne : fiches rattachées à cette campagne ou sans <code>campagne_id</code> mais agence / dates cohérentes.</p>
    </div>
</div>

{{-- Performances --}}
<div class="card shadow-sm mb-4">
    <div class="card-header"><strong>Performances commerciales</strong></div>
    <div class="card-body">
        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body py-3">
                        <h6 class="mb-0">Total ventes</h6>
                        <h3 class="mb-0">{{ $stats['total_ventes'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <h6>Ventes par type de carte</h6>
                <table class="table table-sm table-striped">
                    <thead class="table-light"><tr><th>Type</th><th class="text-end">Nombre</th></tr></thead>
                    <tbody>
                        @foreach($typesCartes as $tc)
                        @php $p = $stats['par_type'][$tc->id] ?? null; @endphp
                        <tr><td>{{ $tc->code }}</td><td class="text-end">{{ $p?->nb ?? 0 }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Ventes par agence</h6>
                <table class="table table-sm table-striped">
                    <thead class="table-light"><tr><th>Agence</th><th class="text-end">Nombre</th></tr></thead>
                    <tbody>
                        @foreach($stats['par_agence'] as $pa)
                        <tr><td>{{ $pa->agence_nom }}</td><td class="text-end">{{ $pa->nb }}</td></tr>
                        @endforeach
                        @if($stats['par_agence']->isEmpty())
                        <tr><td colspan="2" class="text-muted text-center">Aucune vente</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <h6>Classement des commerciaux</h6>
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light"><tr><th>Rang</th><th>Commercial</th><th class="text-end">Ventes</th></tr></thead>
                <tbody>
                    @foreach($classement as $c)
                    <tr>
                        <td>@if($c['rang']==1)<span class="badge bg-warning text-dark">Top 1</span>@elseif($c['rang']==2)<span class="badge bg-secondary">Top 2</span>@else{{ $c['rang'] }}@endif</td>
                        <td>{{ $c['user_name'] }}</td>
                        <td class="text-end">{{ $c['total_ventes'] }}</td>
                    </tr>
                    @endforeach
                    @if($classement->isEmpty())
                    <tr><td colspan="3" class="text-muted text-center">Aucune vente sur la période</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Historique des actions (arrêt, annulation, reprogrammation) --}}
<div class="card shadow-sm mb-4">
    <div class="card-header"><strong>Historique des actions (reporting)</strong></div>
    <div class="card-body">
        <p class="text-muted small">Documentation des décisions prises sur cette campagne : arrêts, annulations, reprogrammations.</p>
        @if($campagne->actions->isEmpty())
        <p class="text-muted mb-0">Aucune action enregistrée sur cette campagne.</p>
        @else
        <div class="list-group list-group-flush">
            @foreach($campagne->actions as $action)
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge
                            @if($action->action === 'arreter') bg-warning text-dark
                            @elseif($action->action === 'annuler') bg-danger
                            @else bg-secondary @endif
                        ">
                            @if($action->action === 'arreter') Arrêt
                            @elseif($action->action === 'annuler') Annulation
                            @else Reprogrammation @endif
                        </span>
                        <small class="text-muted ms-2">{{ $action->created_at->format('d/m/Y H:i') }}</small>
                        @if($action->user)
                        <small class="text-muted">par {{ $action->user->name }}</small>
                        @endif
                    </div>
                </div>
                <p class="mt-2 mb-1"><strong>Justification :</strong></p>
                <p class="mb-2 ps-2 border-start border-2 border-light">{{ $action->description }}</p>
                @if($action->action === 'reprogrammer' && $action->donnees_avant && $action->donnees_apres)
                <div class="row small text-muted">
                    @php
                        $av = $action->donnees_avant;
                        $ap = $action->donnees_apres;
                    @endphp
                    <div class="col-md-6">Avant : {{ $av['date_debut'] ? \Carbon\Carbon::parse($av['date_debut'])->format('d/m/Y') : '-' }} → {{ $av['date_fin'] ? \Carbon\Carbon::parse($av['date_fin'])->format('d/m/Y') : '-' }}</div>
                    <div class="col-md-6">Après : {{ $ap['date_debut'] ? \Carbon\Carbon::parse($ap['date_debut'])->format('d/m/Y') : '-' }} → {{ $ap['date_fin'] ? \Carbon\Carbon::parse($ap['date_fin'])->format('d/m/Y') : '-' }}</div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Primes versées --}}
@if($primes->isNotEmpty())
<div class="card shadow-sm mb-4">
    <div class="card-header"><strong>Primes versées (périodes couvertes)</strong></div>
    <div class="card-body">
        <table class="table table-sm">
            <thead><tr><th>Période</th><th>Commercial</th><th>Rang</th><th class="text-end">Montant</th></tr></thead>
            <tbody>
                @foreach($primes as $p)
                <tr>
                    <td>{{ $p->periode }}</td>
                    <td>{{ $p->user?->prenom ? trim($p->user->prenom . ' ' . $p->user->name) : $p->user?->name }}</td>
                    <td>{{ $p->rang }}</td>
                    <td class="text-end">{{ number_format($p->montant) }} F</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="mb-4">
    <a href="{{ $isDirectionDetail ? route('direction.campagnes.index') : route('admin.campagnes.index') }}" class="btn btn-outline-secondary">← Retour à la liste</a>
    @unless($isDirectionDetail)
    <a href="{{ route('admin.campagnes.edit', $campagne) }}" class="btn btn-primary">Modifier la campagne</a>
    @endunless
</div>
@endsection
