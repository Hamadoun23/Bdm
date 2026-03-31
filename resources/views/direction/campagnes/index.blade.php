@extends('layouts.app')

@section('title', 'Suivi des campagnes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Suivi des campagnes <span class="text-muted small fw-normal">(lecture seule)</span></h4>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Période</th>
                    <th>Agences</th>
                    <th>Prime 1<sup>er</sup></th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($campagnes as $c)
                @php $statut = $c->statut_effectif; @endphp
                <tr>
                    <td>{{ $c->nom }}</td>
                    <td>{{ $c->date_debut->format('d/m/Y') }} – {{ $c->date_fin->format('d/m/Y') }}</td>
                    <td>
                        @if($c->toutes_agences)
                        <span class="text-muted">Toutes</span>
                        @else
                        {{ $c->agences->pluck('nom')->join(', ') ?: '—' }}
                        @endif
                    </td>
                    <td>{{ number_format($c->prime_meilleur_vendeur) }} F</td>
                    <td>
                        @if($statut === 'en_cours')
                        <span class="badge bg-success">En cours</span>
                        @elseif($statut === 'programmee')
                        <span class="badge bg-info">Programmée</span>
                        @elseif($statut === 'arretee')
                        <span class="badge bg-warning text-dark">Arrêtée</span>
                        @elseif($statut === 'annulee')
                        <span class="badge bg-danger">Annulée</span>
                        @else
                        <span class="badge bg-secondary">Terminée</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('direction.campagnes.show', $c) }}" class="btn btn-sm btn-outline-primary">Détail complet</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($campagnes->hasPages())
    <div class="card-footer">{{ $campagnes->links() }}</div>
    @endif
</div>
@endsection
