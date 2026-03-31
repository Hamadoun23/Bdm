@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h4 class="mb-0">Commerciaux &amp; Direction</h4>
        <p class="text-muted small mb-0 mt-1">Connexion commerciaux : <strong>téléphone</strong> uniquement. Direction : <strong>téléphone</strong>, e-mail optionnel. Consultation et exports seulement (pas d’administration opérationnelle).</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Nouvel utilisateur</a>
</div>

<form method="GET" class="row g-2 align-items-end mb-3">
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <label class="form-label small text-muted mb-0">Recherche (nom, prénom, téléphone)</label>
        <input type="search" name="q" class="form-control" value="{{ request('q') }}" placeholder="Ex. THERA, 7408…" autocomplete="off">
    </div>
    <div class="col-12 col-sm-6 col-md-3 col-lg-2">
        <label class="form-label small text-muted mb-0">Rôle</label>
        <select name="role" class="form-select">
            <option value="">Tous</option>
            <option value="commercial" {{ request('role') === 'commercial' ? 'selected' : '' }}>Commerciaux</option>
            <option value="direction" {{ request('role') === 'direction' ? 'selected' : '' }}>Direction</option>
        </select>
    </div>
    <div class="col-auto d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-secondary">Filtrer</button>
        @if(request()->hasAny(['q', 'role']))
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
        @endif
    </div>
</form>

<div class="card shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Téléphone / e-mail</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Agence</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>{{ $u->prenom ? trim($u->prenom . ' ' . $u->name) : $u->name }}</td>
                    <td>
                        @if($u->telephone)<span class="text-nowrap">{{ $u->telephone }}</span>@endif
                        @if($u->telephone && $u->email)<br>@endif
                        @if($u->email)<span class="text-muted small">{{ $u->email }}</span>@endif
                        @if(!$u->telephone && !$u->email)-@endif
                    </td>
                    <td>
                        @if($u->role === 'direction')
                        <span class="badge bg-dark">direction</span>
                        @else
                        <span class="badge bg-success">commercial</span>
                        @endif
                    </td>
                    <td>
                        @if($u->actif)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Désactivé</span>
                        @endif
                    </td>
                    <td>{{ $u->agence->nom ?? '—' }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                        @if($u->id != auth()->id())
                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer d-flex flex-wrap justify-content-center justify-content-sm-between align-items-center gap-2 py-3">
        <div class="small text-muted order-2 order-sm-1 text-center text-sm-start">{{ $users->firstItem() }}–{{ $users->lastItem() }} sur {{ $users->total() }}</div>
        <div class="order-1 order-sm-2 overflow-auto w-100 w-sm-auto d-flex justify-content-center">{{ $users->links() }}</div>
    </div>
    @endif
</div>
@endsection
