@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-0">Clients</h4>
        <p class="text-muted small mb-0 mt-1">Liste de tous les clients enregistrés.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Ville</th>
                    <th>Type carte</th>
                    <th>Commercial</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $c)
                <tr>
                    <td>{{ $c->prenom }} {{ $c->nom }}</td>
                    <td>{{ $c->telephone ?? '—' }}</td>
                    <td>{{ $c->ville ?? '—' }}</td>
                    <td><span class="badge bg-info">{{ $c->typeCarte?->code ?? '?' }}</span></td>
                    <td>{{ $c->user->name ?? '—' }}</td>
                    <td><span class="badge bg-secondary">{{ $c->statut_carte }}</span></td>
                    <td>
                        <a href="{{ route('clients.show', $c) }}" class="btn btn-sm btn-primary">Détail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4">Aucun client.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clients->hasPages())
    <div class="card-footer">
        {{ $clients->links() }}
    </div>
    @endif
</div>
@endsection
