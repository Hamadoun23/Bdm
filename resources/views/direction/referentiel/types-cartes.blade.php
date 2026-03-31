@extends('layouts.app')

@section('title', 'Types de cartes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Types de cartes <span class="text-muted small fw-normal">(référentiel — lecture seule)</span></h4>
    <a href="{{ route('direction.campagnes.index') }}" class="btn btn-outline-secondary">← Campagnes</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Code</th>
                    <th class="text-end">Prix catalogue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($typesCartes as $tc)
                <tr>
                    <td><strong>{{ $tc->code }}</strong></td>
                    <td class="text-end">{{ number_format($tc->prix) }} FCFA</td>
                </tr>
                @empty
                <tr><td colspan="2" class="text-muted">Aucun type enregistré.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
