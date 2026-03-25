@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<h4 class="mb-4">Dashboard Admin</h4>
@include('dashboard._user_context', ['user' => $user])

@if($alertes->isNotEmpty())
<div class="alert alert-warning">
    <strong>Alertes stock faible :</strong>
    @foreach($alertes as $a)
        {{ $a->agence->nom }} - {{ $a->typeCarte?->code ?? '?' }} : {{ $a->quantite }}
        @if(!$loop->last) | @endif
    @endforeach
</div>
@endif

<div class="row g-3">
    <div class="col-md-6">
        <div class="card bg-primary text-white mb-2">
            <div class="card-body">
                <h6>Ventes totales</h6>
                <h3>{{ $ventesTotal }}</h3>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header"><strong>Top performances du mois</strong></div>
            <ul class="list-group list-group-flush">
                @foreach($classement->take(5) as $c)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $c['rang'] }}. {{ $c['user_name'] }}</span>
                    <span class="badge bg-primary">{{ $c['total_ventes'] }} ventes</span>
                </li>
                @endforeach
                @if($classement->isEmpty())
                <li class="list-group-item">Aucune vente ce mois.</li>
                @endif
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <h6>Ventes ce mois</h6>
                <h3>{{ $ventesMois }}</h3>
            </div>
        </div>
        <div class="card border-0 text-white mb-3 gda-card-hero">
            <div class="card-body">
                <h6>Campagnes</h6>
                <div class="d-flex align-items-baseline gap-2 mb-1">
                    <span class="fs-4 fw-bold">{{ $campagnesTotal }}</span>
                    <small class="opacity-90">total</small>
                </div>
                @if($campagneActive)
                <div class="small opacity-90">
                    <strong>Active :</strong> {{ $campagneActive->nom }}<br>
                    <small>{{ $campagneActive->date_debut->format('d/m/Y') }} - {{ $campagneActive->date_fin->format('d/m/Y') }}</small>
                </div>
                @else
                <div class="small opacity-90">
                    @if($campagnesEnCours > 0 || $campagnesProgrammees > 0)
                        @if($campagnesEnCours > 0){{ $campagnesEnCours }} en cours @endif
                        @if($campagnesProgrammees > 0) • {{ $campagnesProgrammees }} programmée(s) @endif
                    @else
                        Aucune campagne active
                    @endif
                </div>
                @endif
                <a href="{{ route('admin.campagnes.index') }}" class="btn btn-sm btn-light mt-2">Voir les campagnes →</a>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header"><strong>Liens rapides</strong></div>
            <div class="card-body">
                <a href="{{ route('admin.campagnes.index') }}" class="btn btn-outline-primary me-2 mb-2">Campagnes</a>
                <a href="{{ url('/admin/stocks') }}" class="btn btn-outline-primary me-2 mb-2">Stocks</a>
                <a href="{{ url('/admin/rapports') }}" class="btn btn-outline-primary me-2 mb-2">Rapports</a>
                <a href="{{ url('/performances') }}" class="btn btn-outline-primary me-2 mb-2">Performances</a>
            </div>
        </div>
    </div>
</div>
@endsection
