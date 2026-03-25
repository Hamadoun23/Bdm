@extends('layouts.app')

@section('title', 'Historique des mouvements de stock')

@section('content')
<h4 class="mb-4">Mouvements de stock</h4>

@if($agences->isNotEmpty())
<form method="GET" class="mb-3 d-flex gap-2">
    <select name="agence" class="form-select" style="max-width: 200px;">
        <option value="">Toutes les agences</option>
        @foreach($agences as $a)
        <option value="{{ $a->id }}" {{ request('agence') == $a->id ? 'selected' : '' }}>{{ $a->nom }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-primary">Filtrer</button>
</form>
@endif

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Agence</th>
                    <th>Type carte</th>
                    <th>Quantité</th>
                    <th>Type mouvement</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mouvements as $m)
                <tr>
                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $m->agence->nom ?? '-' }}</td>
                    <td>{{ $m->typeCarte?->code ?? '?' }}</td>
                    <td><span class="{{ $m->quantite < 0 ? 'text-danger' : 'text-success' }}">{{ $m->quantite }}</span></td>
                    <td>{{ $m->type_mouvement }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4">Aucun mouvement.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mouvements->hasPages())
    <div class="card-footer">{{ $mouvements->links() }}</div>
    @endif
</div>
@endsection
