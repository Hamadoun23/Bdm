@extends('layouts.app')

@section('title', 'Historique des ventes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="mb-0">Historique des ventes</h4>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('ventes.export-excel') }}" class="btn btn-success" target="_blank">Exporter Excel (.xlsx)</a>
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
                        @if(auth()->user()?->isAdmin() || auth()->user()?->isDirection())
                        <th>Commercial</th>
                        <th>Agence</th>
                        @endif
                        @if(auth()->user()?->isCommercial())
                        <th class="text-end text-nowrap">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventes as $v)
                    <tr>
                        <td>{{ $v->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $v->client->prenom }} {{ $v->client->nom }}</td>
                        <td><span class="badge bg-info">{{ $v->typeCarte?->code ?? '?' }}</span></td>
                        @if(auth()->user()?->isAdmin() || auth()->user()?->isDirection())
                        <td>{{ $v->user->name ?? '-' }}</td>
                        <td>{{ $v->agence->nom ?? '-' }}</td>
                        @endif
                        @if(auth()->user()?->isCommercial())
                        <td class="text-end">
                            <div class="d-inline-flex flex-column flex-sm-row gap-1 justify-content-end">
                                @if($v->client->peutEtreModifieOuSupprimeParCommercial())
                                    <a href="{{ route('commercial.clients.edit', $v->client) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                @else
                                    <span class="btn btn-sm btn-outline-secondary disabled" tabindex="-1" title="Modification impossible après {{ \App\Models\Client::DELAI_SUPPRESSION_COMMERCIAL_HEURES }} h suivant la création de la fiche client.">Modifier</span>
                                @endif
                                @if($v->peutEtreSupprimeeParCommercial())
                                    <form method="POST" action="{{ route('ventes.destroy', $v) }}" class="d-inline" onsubmit="return confirm('Supprimer cette vente et la fiche client associée ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                @else
                                    <span class="btn btn-sm btn-outline-secondary disabled" tabindex="-1" title="Suppression impossible après {{ \App\Models\Vente::DELAI_SUPPRESSION_COMMERCIAL_HEURES }} h suivant la vente.">Supprimer</span>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="{{ auth()->user()?->isCommercial() ? 4 : 5 }}" class="text-center py-4">Aucune vente.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($ventes->hasPages())
    <div class="card-footer d-flex flex-wrap justify-content-center justify-content-sm-between align-items-center gap-2 py-3">
        <div class="small text-muted order-2 order-sm-1">{{ $ventes->firstItem() }}–{{ $ventes->lastItem() }} / {{ $ventes->total() }}</div>
        <div class="order-1 order-sm-2 overflow-auto w-100 w-sm-auto d-flex justify-content-center">{{ $ventes->links() }}</div>
    </div>
    @endif
</div>
@endsection
