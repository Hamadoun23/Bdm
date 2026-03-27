@extends('layouts.app')

@section('title', 'Fiche client')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-0">{{ $client->prenom }} {{ $client->nom }}</h4>
        <p class="text-muted small mb-0 mt-1">Client #{{ $client->id }}</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalExport">Exporter</button>
        <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">Liste des clients</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header">Coordonnées</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Téléphone</strong><br>{{ $client->telephone ?? '—' }}</li>
                <li class="list-group-item"><strong>Ville</strong><br>{{ $client->ville ?? '—' }}</li>
                <li class="list-group-item"><strong>Quartier</strong><br>{{ $client->quartier ?? '—' }}</li>
                <li class="list-group-item"><strong>Type de carte</strong><br><span class="badge bg-info">{{ $client->typeCarte?->code ?? '?' }}</span></li>
                <li class="list-group-item"><strong>Statut carte</strong><br><span class="badge bg-secondary">{{ $client->statut_carte }}</span></li>
                <li class="list-group-item"><strong>Commercial</strong><br>{{ $client->user->name ?? '—' }}</li>
                <li class="list-group-item"><strong>Agence</strong><br>{{ $client->user?->agence?->nom ?? '—' }}</li>
                <li class="list-group-item"><strong>Enregistré le</strong><br>{{ $client->created_at->format('d/m/Y H:i') }}</li>
            </ul>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Pièce d’identité</div>
            <div class="card-body">
                @if($client->carte_identite)
                    <a href="{{ asset('storage/'.$client->carte_identite) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">Voir le fichier</a>
                @else
                    <span class="text-muted">Aucun fichier.</span>
                @endif
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">Ventes associées</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Commercial</th>
                            <th>Agence</th>
                            <th>Activation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($client->ventes as $v)
                        <tr>
                            <td>{{ $v->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $v->typeCarte?->code ?? '?' }}</td>
                            <td>{{ $v->montant !== null ? number_format($v->montant) . ' F' : '—' }}</td>
                            <td>{{ $v->user->name ?? '—' }}</td>
                            <td>{{ $v->agence->nom ?? '—' }}</td>
                            <td><span class="badge bg-light text-dark">{{ $v->statut_activation }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-3 text-muted">Aucune vente.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExport" tabindex="-1" aria-labelledby="modalExportLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExportLabel">Exporter la fiche client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Choisissez le format du fichier à télécharger.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('clients.export', ['client' => $client, 'format' => 'pdf']) }}" class="btn btn-outline-danger">PDF</a>
                    <a href="{{ route('clients.export', ['client' => $client, 'format' => 'excel']) }}" class="btn btn-outline-success">Excel (CSV)</a>
                    <a href="{{ route('clients.export', ['client' => $client, 'format' => 'word']) }}" class="btn btn-outline-primary">Word (.doc)</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
