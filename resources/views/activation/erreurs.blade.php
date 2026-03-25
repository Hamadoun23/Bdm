@extends('layouts.app')

@section('title', 'Cartes en erreur')

@section('content')
<h4 class="mb-4">Cartes en erreur d'activation</h4>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th>Type carte</th>
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
                    <td>{{ $v->client->telephone ?? '-' }}</td>
                    <td>{{ $v->type_carte }}</td>
                    @if(auth()->user()?->isAdmin() || auth()->user()?->isChefAgence())
                    <td>
                        <form action="{{ url('/activation/' . $v->id . '/statut') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="statut" value="activée">
                            <button type="submit" class="btn btn-success btn-sm">Résolu - Marquer activée</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
                @if($ventes->isEmpty())
                <tr><td colspan="5" class="text-center py-4">Aucune carte en erreur.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    @if($ventes->hasPages())
    <div class="card-footer">{{ $ventes->links() }}</div>
    @endif
</div>

<a href="{{ url('/activation') }}" class="btn btn-outline-secondary mt-3">Retour au suivi</a>
@endsection
