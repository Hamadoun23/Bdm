@extends('layouts.app')

@section('title', 'Reporting téléphonique (toutes)')

@section('content')
<h4 class="mb-3">Reporting téléphonique — vue direction</h4>

<form method="GET" class="row g-2 align-items-end mb-3">
    <div class="col-md-3">
        <label class="form-label small mb-0">Téléopératrice</label>
        <select name="user_id" class="form-select form-select-sm">
            <option value="">— Toutes —</option>
            @foreach($telephoniques as $t)
            <option value="{{ $t->id }}" @selected((string) request('user_id') === (string) $t->id)>
                {{ $t->prenom ? trim($t->prenom.' '.$t->name) : $t->name }} — {{ $t->agence?->nom }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label small mb-0">Campagne</label>
        <select name="campagne_id" class="form-select form-select-sm">
            <option value="">— Toutes —</option>
            @foreach($campagnes as $c)
            <option value="{{ $c->id }}" @selected((string) request('campagne_id') === (string) $c->id)>
                {{ $c->nom }} ({{ $c->date_debut->format('d/m/y') }}–{{ $c->date_fin->format('d/m/y') }})
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label small mb-0">Du</label>
        <input type="date" name="date_debut" class="form-control form-control-sm" value="{{ request('date_debut') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label small mb-0">Au</label>
        <input type="date" name="date_fin" class="form-control form-control-sm" value="{{ request('date_fin') }}">
    </div>
    <div class="col-md-2 d-flex flex-wrap gap-1 align-items-end">
        <button type="submit" class="btn btn-sm btn-secondary">Filtrer</button>
        <a href="{{ route('admin.telephonique-rapports.index') }}" class="btn btn-sm btn-outline-secondary">Réinitialiser</a>
    </div>
</form>

@php
    $qCsv = array_filter([
        'user_id' => request('user_id'),
        'campagne_id' => request('campagne_id'),
        'date_debut' => request('date_debut'),
        'date_fin' => request('date_fin'),
    ], fn ($v) => $v !== null && $v !== '');
    $qXlsx = array_merge($qCsv, ['format' => 'xlsx']);
@endphp
<div class="mb-3 d-flex flex-wrap gap-2">
    <a href="{{ route('admin.telephonique-rapports.export', $qXlsx) }}" class="btn btn-sm btn-success" target="_blank">Exporter Excel (.xlsx)</a>
    <a href="{{ route('admin.telephonique-rapports.export', $qCsv) }}" class="btn btn-sm btn-outline-primary" target="_blank">Exporter CSV</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-sm table-striped table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Campagne</th>
                    <th>Collaborateur</th>
                    <th>Agence</th>
                    <th class="text-end">Émis</th>
                    <th class="text-end">Joign.</th>
                    <th class="text-end">Non j.</th>
                    <th class="text-end">Intéressés</th>
                    <th>Déjà servis</th>
                    <th>Cartes proposées</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($rapports as $r)
                <tr>
                    <td>{{ $r->date_rapport->format('d/m/Y') }}</td>
                    <td class="small">{{ $r->campagne?->nom ?? '—' }}</td>
                    <td>{{ $r->user?->prenom ? trim($r->user->prenom.' '.$r->user->name) : $r->user?->name }}</td>
                    <td class="small">{{ $r->user?->agence?->nom ?? '—' }}</td>
                    <td class="text-end">{{ $r->appels_emis }}</td>
                    <td class="text-end">{{ $r->appels_joignables }}</td>
                    <td class="text-end">{{ $r->appels_non_joignables }}</td>
                    <td class="text-end">{{ $r->clients_interesses_nombre }}</td>
                    <td class="text-end">{{ $r->clients_deja_servis_nombre }}</td>
                    <td class="small">{{ $r->resumeCartesProposees() }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.telephonique-rapports.show', $r) }}" class="btn btn-sm btn-outline-primary">Détail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="11" class="text-center text-muted py-4">Aucune fiche.</td></tr>
                @endforelse
            </tbody>
            @if(($totauxListe['nb_fiches'] ?? 0) > 0)
            <tfoot class="table-secondary">
                <tr class="fw-bold small">
                    <td colspan="4" class="text-end">Total ({{ number_format($totauxListe['nb_fiches']) }} fiche(s), filtre actuel)</td>
                    <td class="text-end">{{ number_format($totauxListe['appels_emis']) }}</td>
                    <td class="text-end">{{ number_format($totauxListe['appels_joignables']) }}</td>
                    <td class="text-end">{{ number_format($totauxListe['appels_non_joignables']) }}</td>
                    <td class="text-end">{{ number_format($totauxListe['clients_interesses']) }}</td>
                    <td class="text-end">{{ number_format($totauxListe['clients_deja_servis']) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    @if($rapports->hasPages())
    <div class="card-footer">{{ $rapports->links() }}</div>
    @endif
</div>
@endsection
