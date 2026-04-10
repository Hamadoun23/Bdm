@extends('layouts.app')

@section('title', isset($readOnly) && $readOnly ? 'Dashboard Direction' : 'Dashboard Admin')

@section('content')
<h4 class="mb-4">{{ ($readOnly ?? false) ? 'Dashboard Direction' : 'Dashboard Admin' }}</h4>
@include('dashboard._user_context', ['user' => $user])

@if($alertes->isNotEmpty())
@php
    $alertesApercu = $alertes->take(2);
    $alertesTotal = $alertes->count();
@endphp
<div class="alert alert-warning d-flex flex-column flex-sm-row flex-wrap align-items-start align-items-sm-center gap-2">
    <div class="flex-grow-1 min-w-0">
        <strong>Alertes stock faible :</strong>
        <span class="d-inline ms-1">
            @foreach($alertesApercu as $a)
                <span class="text-nowrap">{{ $a->agence->nom }} — {{ $a->typeCarte?->code ?? '?' }} : <strong>{{ $a->quantite }}</strong></span>@if(!$loop->last)<span class="mx-1">·</span>@endif
            @endforeach
            @if($alertesTotal > 2)
                <span class="text-muted">(+ {{ $alertesTotal - 2 }} autre(s))</span>
            @endif
        </span>
    </div>
    <a href="{{ route('alertes.stock-faible') }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-dark text-nowrap flex-shrink-0">
        Voir tout
    </a>
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
                @if(isset($campagnesActivesListe) && $campagnesActivesListe->isNotEmpty())
                <div class="small opacity-90">
                    <strong>Active(s) :</strong>
                    <ul class="mb-0 ps-3 mt-1">
                        @foreach($campagnesActivesListe as $ca)
                        <li>{{ $ca->nom }} <small class="text-white-50">({{ $ca->date_debut->format('d/m/Y') }} – {{ $ca->date_fin->format('d/m/Y') }})</small></li>
                        @endforeach
                    </ul>
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
                @if(!($readOnly ?? false))
                <a href="{{ route('admin.campagnes.index') }}" class="btn btn-sm btn-light mt-2">Voir les campagnes →</a>
                @else
                <a href="{{ route('direction.campagnes.index') }}" class="btn btn-sm btn-light mt-2 me-1">Détail des campagnes →</a>
                <a href="{{ route('rapports.index') }}" class="btn btn-sm btn-outline-light mt-2">Rapports →</a>
                @endif
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header"><strong>Liens rapides</strong></div>
            <div class="card-body">
                @unless($readOnly ?? false)
                <a href="{{ route('admin.campagnes.index') }}" class="btn btn-outline-primary me-2 mb-2">Campagnes</a>
                <a href="{{ url('/admin/stocks') }}" class="btn btn-outline-primary me-2 mb-2">Stocks</a>
                @else
                <a href="{{ route('direction.campagnes.index') }}" class="btn btn-outline-primary me-2 mb-2">Campagnes (détail)</a>
                <a href="{{ route('direction.types-cartes.index') }}" class="btn btn-outline-primary me-2 mb-2">Types de cartes</a>
                @endunless
                <a href="{{ route('rapports.index') }}" class="btn btn-outline-primary me-2 mb-2">Rapports</a>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-primary me-2 mb-2">Clients</a>
                <a href="{{ route('ventes.index') }}" class="btn btn-outline-primary me-2 mb-2">Historique des ventes</a>
                <a href="{{ url('/performances') }}" class="btn btn-outline-primary me-2 mb-2">Performances</a>
            </div>
        </div>
    </div>
</div>
@endsection
