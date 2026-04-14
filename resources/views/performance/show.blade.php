@extends('layouts.app')

@section('title', 'Détail performances — '.$displayName)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-0">Détail — {{ $displayName }}</h4>
        <p class="small text-muted mb-0 mt-1"><strong>Période :</strong> {{ $libellePeriode }}</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('performances.commercial.export-excel', array_merge(['user' => $commercial], $queryParams)) }}" class="btn btn-success btn-sm" target="_blank">Exporter Excel (.xlsx)</a>
        <a href="{{ route('performances.index', $queryParams) }}" class="btn btn-outline-secondary btn-sm">Retour aux performances</a>
    </div>
</div>

@if($commercial->agence)
<p class="small text-muted mb-3">Agence : <strong>{{ $commercial->agence->nom }}</strong></p>
@endif

<div class="row mb-4 g-2">
    <div class="col-6 col-md-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <h6 class="small mb-1">Ventes (période)</h6>
                <h3 class="mb-0">{{ $ventes->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="small mb-1">Clients touchés</h6>
                <h3 class="mb-0">{{ $clients->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header"><strong>Cartes vendues (volume)</strong></div>
    <div class="card-body">
        @if($parType->isEmpty())
            <p class="text-muted mb-0">Aucune vente sur cette période.</p>
        @else
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Type de carte</th>
                            <th class="text-end">Quantité</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($typesCartes as $tc)
                            @php $row = $parType->get($tc->id); @endphp
                            @if($row && (int) $row->total > 0)
                                <tr>
                                    <td>{{ $tc->code }}</td>
                                    <td class="text-end">{{ $row->total }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header"><strong>Clients ({{ $clients->count() }})</strong></div>
    <div class="table-responsive">
        @if($clients->isEmpty())
            <div class="card-body"><p class="text-muted mb-0">Aucun client sur cette période.</p></div>
        @else
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Ville</th>
                        <th>Carte (réf. client)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $cl)
                        <tr>
                            <td>{{ trim($cl->prenom.' '.$cl->nom) }}</td>
                            <td>{{ $cl->telephone }}</td>
                            <td>{{ $cl->ville ?? '—' }}</td>
                            <td>{{ $cl->typeCarte?->code ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header"><strong>Ventes ({{ $ventes->count() }})</strong></div>
    <div class="table-responsive">
        @if($ventes->isEmpty())
            <div class="card-body"><p class="text-muted mb-0">Aucune vente enregistrée sur cette période.</p></div>
        @else
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Carte vendue</th>
                        <th>Agence</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventes as $v)
                        <tr>
                            <td>{{ $v->created_at?->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($v->client)
                                    {{ trim($v->client->prenom.' '.$v->client->nom) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $v->typeCarte?->code ?? '—' }}</td>
                            <td>{{ $v->agence?->nom ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
