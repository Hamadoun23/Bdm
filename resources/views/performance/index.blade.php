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

<form method="GET" class="mb-4 d-flex gap-2 flex-wrap align-items-end">
    <div>
        <label class="form-label">Période</label>
        <input type="month" name="periode" class="form-control" value="{{ $periode }}">
    </div>
    @if(auth()->user()?->isAdmin())
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
</form>

@if($vueCommerciale)
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Mes ventes (période)</h6>
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
                </tr>
            </thead>
            <tbody>
                @php $campagne = \App\Models\Campagne::getActiveForAgence($agenceId); @endphp
                @if($vueCommerciale)
                    @foreach($classementTop3 as $c)
                    <tr>
                        <td>
                            @if($c['rang'] == 1)<span class="badge bg-warning text-dark">Top 1</span>
                            @elseif($c['rang'] == 2)<span class="badge bg-secondary">Top 2</span>
                            @else{{ $c['rang'] }}@endif
                        </td>
                        <td>{{ $c['user_name'] }}</td>
                        <td>{{ $c['total_ventes'] }}</td>
                        <td>
                            @if($campagne && $c['rang'] == 1){{ number_format($campagne->prime_top1) }} F
                            @elseif($campagne && $c['rang'] == 2){{ number_format($campagne->prime_top2) }} F
                            @else - @endif
                        </td>
                    </tr>
                    @endforeach
                    @if($maLigne)
                    <tr class="table-secondary"><td colspan="4" class="small fw-bold">Votre position</td></tr>
                    <tr class="table-info">
                        <td><span class="badge bg-dark">{{ $maLigne['rang'] }}ᵉ</span></td>
                        <td>{{ $maLigne['user_name'] }}</td>
                        <td>{{ $maLigne['total_ventes'] }}</td>
                        <td>-</td>
                    </tr>
                    @endif
                    @if($classementTop3->isEmpty() && !$maLigne)
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
                            @if($campagne && $c['rang'] == 1){{ number_format($campagne->prime_top1) }} F
                            @elseif($campagne && $c['rang'] == 2){{ number_format($campagne->prime_top2) }} F
                            @else - @endif
                        </td>
                    </tr>
                    @endforeach
                    @if($classement->isEmpty())
                    <tr><td colspan="4" class="text-center py-4">Aucun commercial à afficher.</td></tr>
                    @endif
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
