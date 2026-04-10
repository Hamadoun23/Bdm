@extends('layouts.app')

@section('title', 'Dashboard Commercial')

@section('content')
<h4 class="mb-4">Mon Dashboard</h4>
@include('dashboard._user_context', ['user' => $user])

@if(!$peutVendre)
<div class="alert alert-warning">
    <strong>Ventes bloquées :</strong> aucune campagne ouverte pour votre agence en ce moment, ou les campagnes concernées sont terminées / arrêtées.
    Les ventes ne sont possibles que pendant au moins une campagne ouverte.
</div>
@else
<div class="alert alert-success py-2 mb-3">
    @if(($campagnesOuvertes ?? collect())->count() > 1)
        <strong>{{ $campagnesOuvertes->count() }} campagnes ouvertes</strong> — à l’enregistrement d’une vente, choisissez celle concernée.
        <ul class="mb-0 mt-2 small ps-3">
            @foreach($campagnesOuvertes as $c)
            <li><strong>{{ $c->nom }}</strong> (jusqu’au {{ $c->date_fin->format('d/m/Y') }})</li>
            @endforeach
        </ul>
    @else
        Campagne en cours : <strong>{{ $campagneActive->nom }}</strong> (jusqu’au {{ $campagneActive->date_fin->format('d/m/Y') }}).
    @endif
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Mes ventes @if($campagneActive)<span class="small opacity-75">(@if(($campagnesOuvertes ?? collect())->count() > 1)campagnes ouvertes @else campagne en cours @endif)</span>@else<span class="small opacity-75">(mois en cours)</span>@endif</h6>
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

<div class="d-flex gap-2 flex-wrap">
    @if($peutVendre)
    <a href="{{ url('/ventes/create') }}" class="btn btn-primary btn-lg">Nouvelle vente</a>
    @else
    <button type="button" class="btn btn-primary btn-lg" disabled title="Aucune campagne active">Nouvelle vente</button>
    @endif
    <a href="{{ url('/ventes') }}" class="btn btn-outline-primary">Historique</a>
    <a href="{{ route('commercial.contrat') }}" class="btn btn-outline-secondary">Mon contrat</a>
    <a href="{{ url('/performances') }}" class="btn btn-outline-secondary">Performances</a>
</div>
@endsection
