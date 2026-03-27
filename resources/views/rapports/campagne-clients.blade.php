@extends('layouts.app')

@section('title', 'Clients — '.$campagne->nom)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-0">Clients — {{ $campagne->nom }}</h4>
        <p class="text-muted small mb-0 mt-1">Clients ayant au moins une vente sur cette campagne @if(auth()->user()?->isChefAgence()) (votre agence) @endif</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('rapports.campagnes.ventes', $campagne) }}" class="btn btn-outline-secondary">Liste des ventes</a>
        <a href="{{ route('rapports.index') }}" class="btn btn-outline-secondary">Rapports</a>
    </div>
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
                    <th class="text-end">Actions</th>
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
                    <td class="text-end">
                        <a href="{{ route('clients.show', $c) }}" class="btn btn-sm btn-outline-primary">Fiche</a>
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#exportModal{{ $c->id }}">Exporter</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4">Aucun client.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@foreach($clients as $c)
<div class="modal fade" id="exportModal{{ $c->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exporter — {{ $c->prenom }} {{ $c->nom }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('clients.export', ['client' => $c, 'format' => 'pdf']) }}" class="btn btn-outline-danger">PDF</a>
                    <a href="{{ route('clients.export', ['client' => $c, 'format' => 'excel']) }}" class="btn btn-outline-success">Excel (CSV)</a>
                    <a href="{{ route('clients.export', ['client' => $c, 'format' => 'word']) }}" class="btn btn-outline-primary">Word (.doc)</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
