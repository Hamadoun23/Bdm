@extends('layouts.app')

@section('title', 'Détail campagne')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Rapport détaillé : {{ $campagne->nom }}</h4>
    <a href="{{ route('admin.campagnes.index') }}" class="btn btn-outline-secondary">← Retour</a>
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
                    <tr><th class="text-muted">Prime Top 1</th><td>{{ number_format($campagne->prime_top1) }} FCFA</td></tr>
                    <tr><th class="text-muted">Prime Top 2</th><td>{{ number_format($campagne->prime_top2) }} FCFA</td></tr>
                    <tr>
                        <th class="text-muted">Remise ventes</th>
                        <td>
                            @if($campagne->remise_pourcentage !== null && (float) $campagne->remise_pourcentage > 0)
                                @php
                                    $rpRemise = (float) $campagne->remise_pourcentage;
                                    $rpRemiseTxt = $rpRemise == floor($rpRemise)
                                        ? number_format($rpRemise, 0, ',', ' ')
                                        : number_format($rpRemise, 2, ',', ' ');
                                @endphp
                                {{ $rpRemiseTxt }}&nbsp;% sur les cartes
                            @else
                                Aucune
                            @endif
                        </td>
                    </tr>
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

{{-- Performances --}}
<div class="card shadow-sm mb-4">
    <div class="card-header"><strong>Performances commerciales</strong></div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body py-3">
                        <h6 class="mb-0">Total ventes</h6>
                        <h3 class="mb-0">{{ $stats['total_ventes'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body py-3">
                        <h6 class="mb-0">Montant total</h6>
                        <h3 class="mb-0">{{ number_format($stats['montant_total']) }} F</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <h6>Ventes par type de carte</h6>
                <table class="table table-sm">
                    <thead><tr><th>Type</th><th class="text-end">Nombre</th><th class="text-end">Montant</th></tr></thead>
                    <tbody>
                        @foreach($typesCartes as $tc)
                        @php $p = $stats['par_type'][$tc->id] ?? null; @endphp
                        <tr><td>{{ $tc->code }}</td><td class="text-end">{{ $p?->nb ?? 0 }}</td><td class="text-end">{{ $p ? number_format($p->mt) : 0 }} F</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Ventes par agence</h6>
                <table class="table table-sm">
                    <thead><tr><th>Agence</th><th class="text-end">Nombre</th><th class="text-end">Montant</th></tr></thead>
                    <tbody>
                        @foreach($stats['par_agence'] as $pa)
                        <tr><td>{{ $pa->agence_nom }}</td><td class="text-end">{{ $pa->nb }}</td><td class="text-end">{{ number_format($pa->mt) }} F</td></tr>
                        @endforeach
                        @if($stats['par_agence']->isEmpty())
                        <tr><td colspan="3" class="text-muted text-center">Aucune vente</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <h6>Classement des commerciaux</h6>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>Rang</th><th>Commercial</th><th class="text-end">Ventes</th><th class="text-end">Montant</th></tr></thead>
                <tbody>
                    @foreach($classement as $c)
                    <tr>
                        <td>@if($c['rang']==1)<span class="badge bg-warning text-dark">Top 1</span>@elseif($c['rang']==2)<span class="badge bg-secondary">Top 2</span>@else{{ $c['rang'] }}@endif</td>
                        <td>{{ $c['user_name'] }}</td>
                        <td class="text-end">{{ $c['total_ventes'] }}</td>
                        <td class="text-end">{{ number_format($c['montant_total']) }} F</td>
                    </tr>
                    @endforeach
                    @if($classement->isEmpty())
                    <tr><td colspan="4" class="text-muted text-center">Aucune vente sur la période</td></tr>
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
    <a href="{{ route('admin.campagnes.index') }}" class="btn btn-outline-secondary">← Retour à la liste</a>
    <a href="{{ route('admin.campagnes.edit', $campagne) }}" class="btn btn-primary">Modifier la campagne</a>
</div>
@endsection
