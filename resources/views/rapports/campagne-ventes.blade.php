@extends('layouts.app')

@section('title', 'Ventes — '.$campagne->nom)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-0">Ventes — {{ $campagne->nom }}</h4>
        <p class="text-muted small mb-0 mt-1">{{ $campagne->date_debut->format('d/m/Y') }} → {{ $campagne->date_fin->format('d/m/Y') }}</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('rapports.campagnes.clients', $campagne) }}" class="btn btn-primary">Détail clients (export)</a>
        <a href="{{ route('rapports.index') }}" class="btn btn-outline-secondary">Rapports</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Type carte</th>
                    <th>Montant</th>
                    <th>Commercial</th>
                    <th>Agence</th>
                    <th>Activation</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventes as $v)
                <tr>
                    <td>{{ $v->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $v->client->prenom }} {{ $v->client->nom }}</td>
                    <td><span class="badge bg-info">{{ $v->typeCarte?->code ?? '?' }}</span></td>
                    <td>{{ $v->montant !== null ? number_format($v->montant) . ' F' : '—' }}</td>
                    <td>{{ $v->user->name ?? '—' }}</td>
                    <td>{{ $v->agence->nom ?? '—' }}</td>
                    <td><span class="badge bg-light text-dark">{{ $v->statut_activation }}</span></td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4">Aucune vente enregistrée sur cette campagne@if(auth()->user()?->isChefAgence()) pour votre agence@endif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ventes->hasPages())
    <div class="card-footer">{{ $ventes->links() }}</div>
    @endif
</div>
@endsection
