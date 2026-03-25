@extends('layouts.app')

@section('title', 'Dashboard Chef d\'agence')

@section('content')
<h4 class="mb-4">Dashboard Agence</h4>
@include('dashboard._user_context', ['user' => $user])
<p class="text-muted small">Indicateurs et classement limités à votre agence.</p>

@if($alertes->isNotEmpty())
<div class="alert alert-warning">
    <strong>Stock faible :</strong>
    @foreach($alertes as $a)
        {{ $a->typeCarte?->code ?? '?' }} : {{ $a->quantite }} restant(s)
        @if(!$loop->last) | @endif
    @endforeach
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Ventes du mois</h6>
                <h3>{{ $ventesAgence }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6>Stocks agence</h6>
                <ul class="mb-0">
                    @foreach($stocks as $s)
                    <li>{{ $s->typeCarte?->code ?? '?' }} : <strong>{{ $s->quantite }}</strong></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header"><strong>Performances des commerciaux (votre agence)</strong></div>
    <ul class="list-group list-group-flush">
        @foreach($classement->take(10) as $c)
        <li class="list-group-item d-flex justify-content-between">
            <span>{{ $c['rang'] }}. {{ $c['user_name'] }}</span>
            <span class="badge bg-primary">{{ $c['total_ventes'] }} ventes</span>
        </li>
        @endforeach
    </ul>
</div>

<div class="mt-3">
    <a href="{{ url('/agence/stocks') }}" class="btn btn-primary">Détail stocks</a>
    <a href="{{ route('ventes.index') }}" class="btn btn-outline-primary">Voir les ventes de l'agence</a>
</div>
@endsection
