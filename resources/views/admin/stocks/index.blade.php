@extends('layouts.app')

@section('title', 'Gestion des stocks')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="mb-0">Stocks par agence</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalApproAdmin">
        + Approvisionner (admin)
    </button>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($alertes->isNotEmpty())
<div class="alert alert-warning mb-4">
    <strong>Alertes stock faible :</strong>
    @foreach($alertes as $a)
        {{ $a->agence->nom }} - {{ $a->typeCarte?->code ?? '?' }} : {{ $a->quantite }} restant(s)
        @if(!$loop->last) | @endif
    @endforeach
</div>
@endif

<div class="row">
    @foreach($agences as $agence)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>{{ $agence->nom }}</strong>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @foreach($agence->stocks as $stock)
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-1">
                        <span>{{ $stock->typeCarte?->code ?? '?' }}</span>
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge {{ $stock->quantite <= 10 ? 'bg-danger' : 'bg-success' }} rounded-pill">{{ $stock->quantite }}</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalAjust-{{ $stock->id }}">Ajuster</button>
                        </span>
                    </li>
                    <div class="modal fade" id="modalAjust-{{ $stock->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.stocks.ajuster') }}">
                                    @csrf
                                    <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ $agence->nom }} — {{ $stock->typeCarte?->code }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="small text-muted mb-2">Actuel : <strong>{{ $stock->quantite }}</strong></p>
                                        <label class="form-label">Quantité (+ ajout / − retrait)</label>
                                        <input type="number" name="quantite" class="form-control" required placeholder="Ex: 50 ou -10">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Valider</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </ul>
                <a href="{{ url('/admin/stocks/mouvements/' . $agence->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                    Historique
                </a>
                <p class="text-muted small mt-2 mb-0">Approvisionnement et ajustements : compte administrateur (pas de chef d'agence requis).</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<a href="{{ url('/admin/stocks/mouvements') }}" class="btn btn-secondary">Tous les mouvements</a>

<div class="modal fade" id="modalApproAdmin" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.stocks.approvisionner') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Approvisionner une agence</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Agence</label>
                        <select name="agence_id" class="form-select" required>
                            @foreach($agences as $a)
                            <option value="{{ $a->id }}">{{ $a->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Type de carte</label>
                        <select name="type_carte_id" class="form-select" required>
                            @foreach($typesCartes as $tc)
                            <option value="{{ $tc->id }}">{{ $tc->code }} — {{ number_format($tc->prix) }} F</option>
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
@endsection
