@extends('layouts.app')

@section('title', 'Dashboard Commercial')

@section('content')
<h4 class="mb-4">Mon Dashboard</h4>
@include('dashboard._user_context', ['user' => $user])

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Mes ventes ce mois</h6>
                <h3>{{ $mesVentes }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6>Mon classement</h6>
                <h3>
                    @if($monRang)
                        Top {{ $monRang }}
                    @else
                        -
                    @endif
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6>Stocks disponibles</h6>
                <ul class="mb-0 small">
                    @foreach($stocks as $s)
                    <li>{{ $s->typeCarte?->code ?? '?' }} : {{ $s->quantite }}</li>
                    @endforeach
                    @if($stocks->isEmpty())
                    <li>-</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <a href="{{ url('/ventes/create') }}" class="btn btn-primary btn-lg">Nouvelle vente</a>
    <a href="{{ url('/ventes') }}" class="btn btn-outline-primary">Historique</a>
    <a href="{{ url('/performances') }}" class="btn btn-outline-secondary">Performances</a>
</div>
@endsection
