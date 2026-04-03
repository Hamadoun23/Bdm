@extends('layouts.app')

@section('title', 'Journal des connexions')

@section('content')
<div class="mb-4">
    <h4 class="mb-1">Journal des connexions</h4>
    <p class="text-muted small mb-0">Chaque ligne correspond à une authentification réussie (tous rôles).</p>
</div>

<form method="GET" class="row g-2 align-items-end mb-3">
    <div class="col-md-4">
        <label class="form-label small mb-0">Utilisateur</label>
        <select name="user_id" class="form-select form-select-sm">
            <option value="">— Tous —</option>
            @foreach($utilisateurs as $c)
            <option value="{{ $c->id }}" @selected((string) request('user_id') === (string) $c->id)>
                {{ $c->prenom ? trim($c->prenom.' '.$c->name) : $c->name }} — {{ $c->role }} @if($c->telephone)({{ $c->telephone }})@endif
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
        <a href="{{ route('admin.login-logs.index') }}" class="btn btn-sm btn-outline-secondary">Réinitialiser</a>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date / heure</th>
                    <th>Utilisateur</th>
                    <th>Rôle</th>
                    <th>IP</th>
                    <th>Navigateur (extrait)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="text-nowrap">{{ $log->logged_in_at->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $log->user?->prenom ? trim($log->user->prenom.' '.$log->user->name) : $log->user?->name }}</td>
                    <td><span class="badge bg-secondary">{{ $log->user?->role }}</span></td>
                    <td class="small">{{ $log->ip_address ?? '—' }}</td>
                    <td class="small text-truncate" style="max-width: 280px" title="{{ $log->user_agent }}">{{ Str::limit($log->user_agent, 80) }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Aucune entrée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="card-footer">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
