@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<h4 class="mb-4">Génération de rapports</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ url('/admin/rapports/export') }}" target="_blank" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Type de rapport</label>
                <select name="type" class="form-select">
                    <option value="mensuel">Mensuel</option>
                    <option value="hebdomadaire">Hebdomadaire</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Période</label>
                <input type="month" name="date" id="date-input" class="form-control" value="{{ now()->format('Y-m') }}">
                <small class="text-muted">Pour hebdomadaire : utilise la semaine du 1er du mois.</small>
            </div>
            <div class="col-md-4">
                <label class="form-label">Agence</label>
                <select name="agence" class="form-select">
                    <option value="">Toutes</option>
                    @foreach(\App\Models\Agence::all() as $a)
                    <option value="{{ $a->id }}">{{ $a->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Télécharger le rapport (CSV)</button>
            </div>
        </form>
    </div>
</div>

<p class="text-muted mt-3">
    Les rapports incluent toutes les ventes pour la période sélectionnée, avec détails client, type de carte, montant, commercial et agence.
</p>
@endsection
