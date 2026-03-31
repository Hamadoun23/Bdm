@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="mb-0">Rapports</h4>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
</div>

<p class="text-muted small mb-3">
    Les ventes sont liées à la campagne en cours au moment de l’enregistrement. Sélectionnez une campagne pour voir ses ventes, puis la fiche des clients (export PDF / Excel / Word).
</p>

<div class="card shadow-sm mb-4">
    <div class="card-header"><strong>Campagnes</strong></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Période</th>
                    <th>Statut</th>
                    <th class="text-end">Ventes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($campagnes as $c)
                <tr>
                    <td>{{ $c->nom }}</td>
                    <td>{{ $c->date_debut->format('d/m/Y') }} → {{ $c->date_fin->format('d/m/Y') }}</td>
                    <td><span class="badge bg-secondary">{{ $c->statut_effectif }}</span></td>
                    <td class="text-end">{{ $c->nb_ventes_rapport }}</td>
                    <td class="text-end">
                        <a href="{{ route('rapports.campagnes.ventes', $c) }}" class="btn btn-sm btn-primary">Ventes</a>
                        <a href="{{ route('rapports.campagnes.clients', $c) }}" class="btn btn-sm btn-outline-primary">Détail clients</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4">Aucune campagne à afficher.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">Export par période (CSV)</div>
    <div class="card-body">
        <form method="GET" action="{{ route('rapports.export') }}" target="_blank" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Type de rapport</label>
                <select name="type" class="form-select">
                    <option value="mensuel">Mensuel</option>
                    <option value="hebdomadaire">Hebdomadaire</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Période</label>
                <input type="month" name="date" class="form-control" value="{{ now()->format('Y-m') }}">
                <small class="text-muted">Hebdomadaire : semaine contenant le 1er du mois choisi.</small>
            </div>
            @if($user->isAdmin() || $user->isDirection())
            <div class="col-md-4">
                <label class="form-label">Agence</label>
                <select name="agence" class="form-select">
                    <option value="">Toutes</option>
                    @foreach(\App\Models\Agence::orderBy('nom')->get() as $a)
                    <option value="{{ $a->id }}">{{ $a->nom }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-12">
                <button type="submit" class="btn btn-outline-primary">Télécharger le CSV</button>
            </div>
        </form>
    </div>
</div>
@endsection
