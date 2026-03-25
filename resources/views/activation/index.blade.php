@extends('layouts.app')

@section('title', 'Suivi des cartes')

@section('content')
<h4 class="mb-4">Cartes en attente d'activation</h4>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Type carte</th>
                    <th>Statut</th>
                    @if(auth()->user()?->isAdmin() || auth()->user()?->isChefAgence())
                    <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($ventes as $v)
                <tr>
                    <td>{{ $v->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $v->client->prenom }} {{ $v->client->nom }}</td>
                    <td>{{ $v->type_carte }}</td>
                    <td>
                        <span class="badge bg-{{ $v->statut_activation === 'en_erreur' ? 'danger' : 'secondary' }}">
                            {{ $v->statut_activation }}
                        </span>
                    </td>
                    @if(auth()->user()?->isAdmin() || auth()->user()?->isChefAgence())
                    <td>
                        @if($v->statut_activation !== 'activée')
                        <div class="btn-group btn-group-sm">
                            <form action="{{ url('/activation/' . $v->id . '/statut') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="statut" value="activée">
                                <button type="submit" class="btn btn-success btn-sm">Marquer activée</button>
                            </form>
                            <form action="{{ url('/activation/' . $v->id . '/statut') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="statut" value="en_erreur">
                                <button type="submit" class="btn btn-danger btn-sm">Marquer erreur</button>
                            </form>
                        </div>
                        @endif
                    </td>
                    @endif
                </tr>
                @endforeach
                @if($ventes->isEmpty())
                <tr><td colspan="5" class="text-center py-4">Aucune carte en attente.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    @if($ventes->hasPages())
    <div class="card-footer">{{ $ventes->links() }}</div>
    @endif
</div>

<a href="{{ url('/activation/erreurs') }}" class="btn btn-outline-warning mt-3">Voir les cartes en erreur</a>
@endsection
