@extends('layouts.app')

@section('title', 'Reporting téléphonique (toutes)')

@section('content')
<h4 class="mb-3">Reporting téléphonique — vue direction</h4>

<form method="GET" class="row g-2 align-items-end mb-3">
    <div class="col-md-4">
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
    <div class="col-md-2">
        <label class="form-label small mb-0">Du</label>
        <input type="date" name="date_debut" class="form-control form-control-sm" value="{{ request('date_debut') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label small mb-0">Au</label>
        <input type="date" name="date_fin" class="form-control form-control-sm" value="{{ request('date_fin') }}">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-sm btn-secondary">Filtrer</button>
        <a href="{{ route('admin.telephonique-rapports.index') }}" class="btn btn-sm btn-outline-secondary">Réinitialiser</a>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-sm table-striped table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Collaborateur</th>
                    <th>Agence</th>
                    <th class="text-end">Émis</th>
                    <th class="text-end">Joign.</th>
                    <th class="text-end">Non j.</th>
                    <th class="text-end">Intéressés</th>
                    <th>Cartes proposées</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rapports as $r)
                <tr>
                    <td>{{ $r->date_rapport->format('d/m/Y') }}</td>
                    <td>{{ $r->user?->prenom ? trim($r->user->prenom.' '.$r->user->name) : $r->user?->name }}</td>
                    <td class="small">{{ $r->user?->agence?->nom ?? '—' }}</td>
                    <td class="text-end">{{ $r->appels_emis }}</td>
                    <td class="text-end">{{ $r->appels_joignables }}</td>
                    <td class="text-end">{{ $r->appels_non_joignables }}</td>
                    <td class="text-end">{{ $r->clients_interesses_nombre }}</td>
                    <td class="small">{{ $r->resumeCartesProposees() }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Aucune fiche.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($rapports->hasPages())
    <div class="card-footer">{{ $rapports->links() }}</div>
    @endif
</div>
@endsection
