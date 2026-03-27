@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-0">Commerciaux & Chefs d'agence</h4>
        <p class="text-muted small mb-0 mt-1">Connexion : <strong>numéro de téléphone</strong> (e-mail optionnel). Les administrateurs se connectent avec leur <strong>nom</strong>.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Nouvel utilisateur</a>
</div>

<form method="GET" class="mb-3">
    <select name="role" class="form-select d-inline-block" style="max-width: 200px;">
        <option value="">Tous</option>
        <option value="commercial" {{ request('role') === 'commercial' ? 'selected' : '' }}>Commerciaux</option>
        <option value="chef_agence" {{ request('role') === 'chef_agence' ? 'selected' : '' }}>Chefs d'agence</option>
    </select>
    <button type="submit" class="btn btn-secondary">Filtrer</button>
</form>

<div class="card shadow-sm">
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
                    <td><span class="badge bg-{{ $u->role === 'chef_agence' ? 'info' : 'success' }}">{{ $u->role }}</span></td>
                    <td>
                        @if($u->actif)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Désactivé</span>
                        @endif
                    </td>
                    <td>{{ $u->agence->nom ?? '-' }}</td>
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
    <div class="card-footer">{{ $users->links() }}</div>
    @endif
</div>
@endsection
