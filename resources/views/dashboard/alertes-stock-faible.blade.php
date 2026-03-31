@extends('layouts.app')

@section('title', 'Stocks faibles')

@section('content')
<h4 class="mb-4">Alertes stock faible (toutes les agences)</h4>
<p class="text-muted small mb-3">Seuil d’alerte : stock ≤ 10 unités.</p>

@if($alertes->isEmpty())
    <div class="alert alert-success mb-0">Aucun stock faible pour le moment.</div>
@else
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Agence</th>
                        <th>Type de carte</th>
                        <th class="text-end">Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alertes as $a)
                        <tr>
                            <td>{{ $a->agence?->nom ?? '—' }}</td>
                            <td>{{ $a->typeCarte?->code ?? '?' }}</td>
                            <td class="text-end">
                                <span class="badge {{ $a->quantite <= 10 ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill">{{ $a->quantite }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer text-muted small">
            {{ $alertes->count() }} ligne(s) affichée(s).
        </div>
    </div>
@endif

<div class="mt-3">
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">← Retour au tableau de bord</a>
</div>
@endsection
