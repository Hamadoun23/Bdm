@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="mb-0">Rapports</h4>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
</div>

<div class="alert alert-light border mb-3 small">
    <p class="mb-2"><strong>Usage direction / pilotage :</strong> le bouton <span class="badge bg-success">Export complet</span> sur chaque campagne génère un classeur Excel (ventes, clients, commerciaux, agences, types de carte, semaines, mois, fiches téléphonique + synthèse appels). Utilisez <span class="badge bg-success">Synthèse</span> pour les graphiques et des exports filtrés, et <span class="badge bg-primary">Liste ventes</span> pour le détail des ventes avec filtres.</p>
    <p class="mb-2 text-muted">Les ventes sont enregistrées avec le <code>campagne_id</code> de la campagne active au moment de la saisie.</p>
    @if($user->isAdmin())
    <p class="mb-0"><a href="{{ route('admin.telephonique-rapports.index') }}" class="alert-link">Reporting téléphonique (toutes campagnes)</a> — vue globale. Depuis chaque campagne : bouton <strong>Reporting téléphonique</strong> ou entrée <strong>Synthèse</strong> pour le périmètre campagne.</p>
    @endif
</div>

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
                    <th class="text-end" style="min-width: 320px;">Actions</th>
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
                        <div class="d-inline-flex flex-wrap gap-1 justify-content-end">
                            <a href="{{ route('rapports.campagnes.export', ['campagne' => $c, 'section' => 'all', 'format' => 'xlsx']) }}" class="btn btn-sm btn-success" target="_blank" title="Classeur : ventes, clients, commerciaux, agences, types, semaines, mois, téléphonique">Export complet</a>
                            <a href="{{ route('rapports.campagnes.synthese', $c) }}" class="btn btn-sm btn-outline-success">Synthèse</a>
                            <a href="{{ route('rapports.campagnes.ventes', $c) }}" class="btn btn-sm btn-primary">Ventes</a>
                            <a href="{{ route('rapports.campagnes.clients', $c) }}" class="btn btn-sm btn-outline-primary">Clients</a>
                            <a href="{{ route('rapports.campagnes.reporting-telephonique', $c) }}" class="btn btn-sm btn-outline-info">Tél.</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4">Aucune campagne à afficher.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
