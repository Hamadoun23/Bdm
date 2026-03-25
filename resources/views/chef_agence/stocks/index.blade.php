@extends('layouts.app')

@section('title', 'Stocks de l\'agence')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Gestion des stocks - {{ $agence?->nom ?? 'Mon agence' }}</h4>
    @if($agence)
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAppro">
        + Approvisionner
    </button>
    @endif
</div>

@if($alertes->isNotEmpty())
<div class="alert alert-warning mb-4">
    <strong>Attention :</strong> Stock faible pour :
    @foreach($alertes as $a)
        {{ $a->typeCarte?->code ?? '?' }} ({{ $a->quantite }} restant(s))
        @if(!$loop->last) | @endif
    @endforeach
</div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Type de carte</th>
                    <th>Quantité disponible</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $s)
                <tr>
                    <td>{{ $s->typeCarte?->code ?? '?' }}</td>
                    <td>
                        <span class="badge {{ $s->quantite <= 10 ? 'bg-danger' : 'bg-success' }} rounded-pill fs-6">
                            {{ $s->quantite }}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAjust-{{ $s->id }}">
                            Modifier
                        </button>
                    </td>
                </tr>
                <div class="modal fade" id="modalAjust-{{ $s->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('agence.stocks.ajuster') }}">
                                @csrf
                                <input type="hidden" name="stock_id" value="{{ $s->id }}">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ajuster stock {{ $s->typeCarte?->code }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-2">Quantité actuelle : <strong>{{ $s->quantite }}</strong></p>
                                    <div class="mb-2">
                                        <label class="form-label">Quantité à ajouter ou retirer</label>
                                        <input type="number" name="quantite" class="form-control" required placeholder="Ex: 50 pour ajouter, -10 pour retirer">
                                        <small class="text-muted">Positif = entrée, Négatif = sortie</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-primary">Valider</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr><td colspan="3" class="text-center">Aucun stock. Utilisez "Approvisionner" pour ajouter des cartes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($agence && $typesDisponibles->isNotEmpty())
<div class="modal fade" id="modalAppro" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('agence.stocks.approvisionner') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Approvisionner le stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Type de carte</label>
                        <select name="type_carte_id" class="form-select" required>
                            @foreach($typesDisponibles as $tc)
                            <option value="{{ $tc->id }}">{{ $tc->code }} (prix ref. {{ number_format($tc->prix) }} F)</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Quantité à ajouter</label>
                        <input type="number" name="quantite" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
