@extends('layouts.app')

@section('title', 'Historique des ventes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="mb-0">Historique des ventes</h4>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Retour au Dashboard</a>
        @if(auth()->user()?->isCommercial())
        <a href="{{ route('ventes.create') }}" class="btn btn-primary">Nouvelle vente</a>
        @endif
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Type carte</th>
                        <th>Montant</th>
                        @if(auth()->user()?->isAdmin() || auth()->user()?->isChefAgence())
                        <th>Commercial</th>
                        <th>Agence</th>
                        @endif
                        @if(auth()->user()?->isCommercial())
                        <th class="text-end">Fiche client</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventes as $v)
                    <tr>
                        <td>{{ $v->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $v->client->prenom }} {{ $v->client->nom }}</td>
                        <td><span class="badge bg-info">{{ $v->typeCarte?->code ?? '?' }}</span></td>
                        <td>{{ $v->montant ? number_format($v->montant) . ' F' : '-' }}</td>
                        @if(auth()->user()?->isAdmin() || auth()->user()?->isChefAgence())
                        <td>{{ $v->user->name ?? '-' }}</td>
                        <td>{{ $v->agence->nom ?? '-' }}</td>
                        @endif
                        @if(auth()->user()?->isCommercial())
                        <td class="text-end">
                            <a href="{{ route('commercial.clients.edit', $v->client) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="{{ auth()->user()?->isCommercial() ? 5 : 6 }}" class="text-center py-4">Aucune vente.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($ventes->hasPages())
    <div class="card-footer">
        {{ $ventes->links() }}
    </div>
    @endif
</div>
@endsection
