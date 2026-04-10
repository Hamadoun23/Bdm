@extends('layouts.app')

@section('title', 'Gestion des campagnes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Campagnes</h4>
    <a href="{{ route('admin.campagnes.create') }}" class="btn btn-primary">Nouvelle campagne</a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Période</th>
                    <th>Prime 1<sup>er</sup></th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campagnes as $c)
                <tr>
                    <td>{{ $c->nom }}</td>
                    <td>{{ $c->date_debut->format('d/m/Y') }} - {{ $c->date_fin->format('d/m/Y') }}</td>
                    <td>{{ number_format($c->prime_meilleur_vendeur) }} F</td>
                    <td>
                        @php $statut = $c->statut_effectif; @endphp
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
                    <td>
                        @if(in_array($statut, ['programmee', 'en_cours']))
                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalArreter{{ $c->id }}">Arrêter</button>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalAnnuler{{ $c->id }}">Annuler</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalReprogrammer{{ $c->id }}">Reprogrammer</button>
                        @endif
                        <a href="{{ route('admin.campagnes.show', $c) }}" class="btn btn-sm btn-outline-info">Détail</a>
                        <a href="{{ route('admin.campagnes.edit', $c) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                        <form action="{{ route('admin.campagnes.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette campagne ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
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

@foreach($campagnes as $c)
@if(in_array($c->statut_effectif, ['programmee', 'en_cours']))
{{-- Modal Arrêter --}}
<div class="modal fade" id="modalArreter{{ $c->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.campagnes.arreter', $c) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Arrêter la campagne « {{ $c->nom }} »</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">La campagne sera interrompue avant sa date de fin. Justifiez cette décision.</p>
                    <label class="form-label">Description * (min. 10 caractères)</label>
                    <textarea name="description" class="form-control" rows="3" required minlength="10" placeholder="Ex: Fin des stocks, changement de stratégie...">{{ old('description') }}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Arrêter la campagne</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Modal Annuler --}}
<div class="modal fade" id="modalAnnuler{{ $c->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.campagnes.annuler', $c) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Annuler la campagne « {{ $c->nom }} »</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">La campagne sera annulée définitivement. Justifiez cette décision.</p>
                    <label class="form-label">Description * (min. 10 caractères)</label>
                    <textarea name="description" class="form-control" rows="3" required minlength="10" placeholder="Ex: Annulation pour raison interne...">{{ old('description') }}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Annuler la campagne</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Modal Reprogrammer --}}
<div class="modal fade" id="modalReprogrammer{{ $c->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.campagnes.reprogrammer', $c) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reprogrammer « {{ $c->nom }} »</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Modifiez les dates de la campagne. La justification est obligatoire.</p>
                    <div class="row mb-2">
                        <div class="col-6">
                            <label class="form-label">Nouvelle date début *</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ $c->date_debut->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Nouvelle date fin *</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ $c->date_fin->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <label class="form-label">Description * (min. 10 caractères)</label>
                    <textarea name="description" class="form-control" rows="3" required minlength="10" placeholder="Ex: Report pour cause de...">{{ old('description') }}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Reprogrammer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection
