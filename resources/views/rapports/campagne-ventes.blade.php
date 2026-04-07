@extends('layouts.app')

@section('title', 'Ventes — '.$campagne->nom)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-0">Ventes — {{ $campagne->nom }}</h4>
        <p class="text-muted small mb-0 mt-1">
            Campagne : {{ $campagne->date_debut->format('d/m/Y') }} → {{ $campagne->date_fin->format('d/m/Y') }}
            — <strong>Filtre affiché :</strong> {{ $dateDebut->format('d/m/Y') }} → {{ $dateFin->format('d/m/Y') }}
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('rapports.campagnes.synthese', $campagne) }}" class="btn btn-success btn-sm">Synthèse & graphiques</a>
        <a href="{{ route('rapports.campagnes.clients', $campagne) }}" class="btn btn-primary btn-sm">Détail clients</a>
        <a href="{{ route('rapports.campagnes.reporting-telephonique', $campagne) }}" class="btn btn-outline-info btn-sm">Reporting téléphonique</a>
        <a href="{{ route('rapports.index') }}" class="btn btn-outline-secondary btn-sm">Rapports</a>
    </div>
</div>

@php
    $qListe = array_filter([
        'du' => request('du'),
        'au' => request('au'),
        'agence_id' => request('agence_id'),
        'user_id' => request('user_id'),
        'type_carte_id' => request('type_carte_id'),
    ], fn ($v) => $v !== null && $v !== '');
@endphp

<form method="GET" class="card shadow-sm mb-3">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-2">
            <label class="form-label small mb-0">Du</label>
            <input type="date" name="du" class="form-control form-control-sm" value="{{ request('du', $dateDebut->format('Y-m-d')) }}" min="{{ $campagne->date_debut->format('Y-m-d') }}" max="{{ $campagne->date_fin->format('Y-m-d') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-0">Au</label>
            <input type="date" name="au" class="form-control form-control-sm" value="{{ request('au', $dateFin->format('Y-m-d')) }}" min="{{ $campagne->date_debut->format('Y-m-d') }}" max="{{ $campagne->date_fin->format('Y-m-d') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-0">Agence</label>
            <select name="agence_id" class="form-select form-select-sm">
                <option value="">— Toutes —</option>
                @foreach($agencesChoix as $a)
                <option value="{{ $a->id }}" @selected((string) $filtreAgenceId === (string) $a->id)>{{ $a->nom }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-0">Commercial</label>
            <select name="user_id" class="form-select form-select-sm">
                <option value="">— Tous —</option>
                @foreach($commerciauxChoix as $u)
                <option value="{{ $u->id }}" @selected((string) $filtreUserId === (string) $u->id)>
                    {{ $u->prenom ? trim($u->prenom.' '.$u->name) : $u->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-0">Type carte</label>
            <select name="type_carte_id" class="form-select form-select-sm">
                <option value="">— Tous —</option>
                @foreach($typesChoix as $tc)
                <option value="{{ $tc->id }}" @selected((string) $filtreTypeCarteId === (string) $tc->id)>{{ $tc->code }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary btn-sm w-100">Filtrer</button>
        </div>
    </div>
</form>

<div class="row g-2 mb-3">
    <div class="col-md-4">
        <div class="card border-primary h-100">
            <div class="card-body py-2">
                <h6 class="small text-muted mb-0">Lignes (filtre actuel)</h6>
                <strong class="fs-5">{{ number_format($resumeListe['count']) }}</strong>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body py-2">
                <h6 class="small text-muted mb-0">Somme des montants</h6>
                <strong class="fs-5">{{ number_format($resumeListe['montant'], 0, ',', ' ') }} F</strong>
            </div>
        </div>
    </div>
    <div class="col-md-4 d-flex align-items-center">
        <a href="{{ route('rapports.campagnes.export', array_merge(['campagne' => $campagne->id, 'section' => 'ventes', 'format' => 'xlsx'], $qListe)) }}" class="btn btn-success btn-sm" target="_blank">Excel (.xlsx)</a>
        <a href="{{ route('rapports.campagnes.export', array_merge(['campagne' => $campagne->id, 'section' => 'ventes'], $qListe)) }}" class="btn btn-outline-primary btn-sm" target="_blank">CSV</a>
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
                    <td>{{ $v->user ? ($v->user->prenom ? trim($v->user->prenom.' '.$v->user->name) : $v->user->name) : '—' }}</td>
                    <td>{{ $v->agence->nom ?? '—' }}</td>
                    <td><span class="badge bg-light text-dark">{{ $v->statut_activation }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        Aucune vente ne correspond aux critères.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ventes->hasPages())
    <div class="card-footer d-flex flex-wrap justify-content-center justify-content-sm-between align-items-center gap-2 py-3">
        <div class="small text-muted order-2 order-sm-1">{{ $ventes->firstItem() }}–{{ $ventes->lastItem() }} / {{ $ventes->total() }}</div>
        <div class="order-1 order-sm-2 overflow-auto w-100 w-sm-auto d-flex justify-content-center">{{ $ventes->links() }}</div>
    </div>
    @endif
</div>
@endsection
