@extends('layouts.app')

@section('title', 'Performances')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="mb-0">Tableau de bord des performances</h4>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Retour au Dashboard</a>
</div>
@if($vueChef)
<p class="text-muted small mb-3">Statistiques de votre agence uniquement.</p>
@endif

<p class="small text-muted mb-2">
    <strong>Période affichée :</strong> {{ $libellePeriode ?? '—' }}
</p>
@if($vueCommerciale && !empty($classementLigneTop1))
<p class="small mb-3 border-start border-3 border-warning ps-2">
    <strong>1<sup>er</sup> du classement</strong>
    @if(!empty($campagnePerformances))
        — campagne « {{ $campagnePerformances->nom }} »
    @endif
    :
    <strong>{{ $classementLigneTop1['user_name'] }}</strong>
    — {{ $classementLigneTop1['total_ventes'] }} vente(s).
</p>
@endif

<form method="GET" class="mb-4 d-flex gap-2 flex-wrap align-items-end">
    <div>
        <label class="form-label">Du</label>
        <input type="date" name="du" class="form-control" value="{{ old('du', $filtreIntervalle ? ($du ?? '') : '') }}">
    </div>
    <div>
        <label class="form-label">Au</label>
        <input type="date" name="au" class="form-control" value="{{ old('au', $filtreIntervalle ? ($au ?? '') : '') }}">
    </div>
    @if(auth()->user()?->isAdmin() || auth()->user()?->isDirection())
    <div>
        <label class="form-label">Agence</label>
        <select name="agence" class="form-select" style="min-width: 180px;">
            <option value="">Toutes</option>
            @foreach(\App\Models\Agence::all() as $a)
            <option value="{{ $a->id }}" {{ $agenceId == $a->id ? 'selected' : '' }}>{{ $a->nom }}</option>
            @endforeach
        </select>
    </div>
    @endif
    <button type="submit" class="btn btn-primary">Filtrer</button>
    <a href="{{ route('performances.index', array_filter(['agence' => $agenceId])) }}" class="btn btn-outline-secondary">Campagne (défaut)</a>
</form>
<p class="small text-muted mb-3">Sans dates : statistiques sur toute la période de la campagne affichée. Renseignez <strong>Du</strong> et <strong>Au</strong> pour un intervalle précis.</p>

@php
    $performancesDetailQuery = array_filter([
        'du' => ! empty($filtreIntervalle) ? ($du ?? null) : null,
        'au' => ! empty($filtreIntervalle) ? ($au ?? null) : null,
        'agence' => $agenceId ?? null,
    ], fn ($v) => $v !== null && $v !== '');
@endphp

@if($vueCommerciale)
<div class="mb-3">
    <a href="{{ route('performances.commercial.show', array_merge(['user' => $user->id], $performancesDetailQuery)) }}" class="btn btn-outline-primary">Voir mon détail (ventes, clients, cartes)</a>
</div>
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Mes ventes</h6>
                <h3>{{ $stats['mes_ventes'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6>Mon rang</h6>
                <h3>{{ isset($stats['mon_rang']) && $stats['mon_rang'] ? $stats['mon_rang'] . 'ᵉ' : '-' }}</h3>
            </div>
        </div>
    </div>
</div>
@else
<div class="row mb-4 g-2">
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <h6>Total ventes</h6>
                <h3>{{ $stats['total_ventes'] }}</h3>
            </div>
        </div>
    </div>
    @foreach($typesCartes as $tc)
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="small">{{ $tc->code }}</h6>
                <h4>{{ $stats['par_type'][$tc->id] ?? 0 }}</h4>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<div class="card shadow-sm">
    <div class="card-header"><strong>Classement des commerciaux</strong></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Commercial</th>
                    <th>Nombre de ventes</th>
                    <th>Prime (estimée)</th>
                    @if(!$vueCommerciale)
                    <th class="text-end">Détail</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php $campagne = \App\Models\Campagne::getActiveForAgence($agenceId); @endphp
                @if($vueCommerciale)
                    @if($classementLigneTop1)
                        @php $c = $classementLigneTop1; @endphp
                    <tr>
                        <td><span class="badge bg-warning text-dark">1<sup>er</sup></span></td>
                        <td>{{ $c['user_name'] }}</td>
                        <td>{{ $c['total_ventes'] }}</td>
                        <td>
                            @if($campagne){{ number_format($campagne->prime_meilleur_vendeur) }} F
                            @else - @endif
                        </td>
                    </tr>
                    @endif
                    @if($ligneCommercialConnecte)
                    <tr class="table-secondary"><td colspan="4" class="small fw-bold">Ma position</td></tr>
                    <tr class="table-info">
                        <td><span class="badge bg-dark">{{ $ligneCommercialConnecte['rang'] }}ᵉ</span></td>
                        <td>
                            {{ $ligneCommercialConnecte['user_name'] }}
                            @if((int) $user->id === (int) $ligneCommercialConnecte['user_id'])
                                <span class="badge bg-secondary ms-1">vous</span>
                            @endif
                        </td>
                        <td>{{ $ligneCommercialConnecte['total_ventes'] }}</td>
                        <td>-</td>
                    </tr>
                    @endif
                    @if(!$classementLigneTop1 && !$ligneCommercialConnecte)
                    <tr><td colspan="4" class="text-center py-4">Aucun commercial à afficher.</td></tr>
                    @endif
                @else
                    @foreach($classement as $c)
                    <tr>
                        <td>
                            @if($c['rang'] == 1)<span class="badge bg-warning text-dark">Top 1</span>
                            @elseif($c['rang'] == 2)<span class="badge bg-secondary">Top 2</span>
                            @else{{ $c['rang'] }}@endif
                        </td>
                        <td>{{ $c['user_name'] }}</td>
                        <td>{{ $c['total_ventes'] }}</td>
                        <td>
                            @if($campagne && $c['rang'] == 1){{ number_format($campagne->prime_meilleur_vendeur) }} F
                            @else - @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('performances.commercial.show', array_merge(['user' => $c['user_id']], $performancesDetailQuery)) }}" class="btn btn-sm btn-outline-primary">Détail</a>
                        </td>
                    </tr>
                    @endforeach
                    @if($classement->isEmpty())
                    <tr><td colspan="5" class="text-center py-4">Aucun commercial à afficher.</td></tr>
                    @endif
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
