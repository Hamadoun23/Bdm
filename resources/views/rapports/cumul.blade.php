@extends('layouts.app')

@section('title', 'Cumul multi-campagnes')

@section('content')
@php
    $libelleUser = static function ($u): string {
        if (! $u) {
            return '—';
        }

        return $u->prenom ? trim($u->prenom.' '.$u->name) : $u->name;
    };
    $qExport = ['campagne_ids' => $campagneIds];
    $totalVentesTypesGraph = (int) $typesCarteKpi->sum('total');
    $topNCommGraph = 5;
    $topNAgGraph = 10;
    $totalVentesCampagne = $totalVentes;
    $commAvecVentes = $parCommercial->filter(fn ($r) => (int) $r->total > 0)->sortByDesc('total')->values();
    $chartCommerciauxRows = collect();
    $denomPart = $totalVentesCampagne > 0 ? $totalVentesCampagne : 1;
    foreach ($commAvecVentes->take($topNCommGraph) as $row) {
        $u = $usersById->get($row->user_id);
        $name = $u ? ($u->prenom ? trim($u->prenom.' '.$u->name) : $u->name) : '—';
        $chartCommerciauxRows->push([
            'label' => $name,
            'total_ventes' => (int) $row->total,
            'pct_part' => round(100 * (int) $row->total / $denomPart, 2),
        ]);
    }
    $tailComm = $commAvecVentes->slice($topNCommGraph);
    if ($tailComm->isNotEmpty()) {
        $vAutres = (int) $tailComm->sum(fn ($r) => (int) $r->total);
        $chartCommerciauxRows->push([
            'label' => 'Autres commerciaux ('.$tailComm->count().')',
            'total_ventes' => $vAutres,
            'pct_part' => round(100 * $vAutres / $denomPart, 2),
        ]);
    }
    $agAvecVentes = $parAgence->filter(fn ($r) => (int) $r->total > 0)->sortByDesc('total')->values();
    $chartAgencesRows = collect();
    foreach ($agAvecVentes->take($topNAgGraph) as $row) {
        $label = $row->agence_id ? ($agencesById->get($row->agence_id)?->nom ?? '?') : '— Sans agence';
        $chartAgencesRows->push(['label' => $label, 'total_ventes' => (int) $row->total]);
    }
    $tailAg = $agAvecVentes->slice($topNAgGraph);
    if ($tailAg->isNotEmpty()) {
        $chartAgencesRows->push([
            'label' => 'Autres agences ('.$tailAg->count().')',
            'total_ventes' => (int) $tailAg->sum(fn ($r) => (int) $r->total),
        ]);
    }
    $totalVentesAgGraph = (int) $chartAgencesRows->sum('total_ventes');
    $parTypesForChart = $typesCarteKpi->map(fn ($t) => ['code' => $t['code'], 'total_ventes' => $t['total']])->values();
@endphp
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-0">Cumul multi-campagnes</h4>
        <p class="text-muted small mb-0 mt-1">
            {{ $campagnes->count() }} campagne(s) —
            période couverte : <strong>{{ $dateDebutGraph->format('d/m/Y') }}</strong> → <strong>{{ $dateFinGraph->format('d/m/Y') }}</strong>
            — <strong>{{ number_format($totalVentes) }}</strong> vente(s),
            <strong>{{ number_format($nbClientsDistincts) }}</strong> client(s) distinct(s).
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('rapports.index') }}#cumul-campagnes" class="btn btn-outline-secondary btn-sm">Autre sélection</a>
        <a href="{{ route('rapports.index') }}" class="btn btn-outline-primary btn-sm">Liste des rapports</a>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-header"><strong>Campagnes incluses</strong></div>
    <div class="card-body py-2">
        <ul class="mb-0 small">
            @foreach($campagnes as $c)
            <li>
                <strong>{{ $c->nom }}</strong>
                — {{ $c->date_debut->format('d/m/Y') }} → {{ $c->date_fin->format('d/m/Y') }}
                <span class="badge bg-secondary">{{ $c->statut_effectif }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</div>

<div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
    <span class="small text-muted me-1">Exports (.xlsx) :</span>
    <a class="btn btn-sm btn-success" target="_blank" href="{{ route('rapports.cumul.export', array_merge($qExport, ['section' => 'all', 'format' => 'xlsx'])) }}"><strong>Classeur complet</strong></a>
    <a class="btn btn-sm btn-outline-success" target="_blank" href="{{ route('rapports.cumul.export', array_merge($qExport, ['section' => 'ventes', 'format' => 'xlsx'])) }}">Ventes</a>
    <a class="btn btn-sm btn-outline-success" target="_blank" href="{{ route('rapports.cumul.export', array_merge($qExport, ['section' => 'clients', 'format' => 'xlsx'])) }}">Clients</a>
    <a class="btn btn-sm btn-outline-success" target="_blank" href="{{ route('rapports.cumul.export', array_merge($qExport, ['section' => 'commerciaux', 'format' => 'xlsx'])) }}">Commerciaux</a>
    <a class="btn btn-sm btn-outline-success" target="_blank" href="{{ route('rapports.cumul.export', array_merge($qExport, ['section' => 'agences', 'format' => 'xlsx'])) }}">Agences</a>
    <a class="btn btn-sm btn-outline-success" target="_blank" href="{{ route('rapports.cumul.export', array_merge($qExport, ['section' => 'types', 'format' => 'xlsx'])) }}">Types de carte</a>
    <a class="btn btn-sm btn-outline-success" target="_blank" href="{{ route('rapports.cumul.export', array_merge($qExport, ['section' => 'semaines', 'format' => 'xlsx'])) }}">Semaines</a>
    <a class="btn btn-sm btn-outline-success" target="_blank" href="{{ route('rapports.cumul.export', array_merge($qExport, ['section' => 'mois', 'format' => 'xlsx'])) }}">Mois</a>
    <span class="text-muted small mx-1">|</span>
    <a class="btn btn-sm btn-success" target="_blank" href="{{ route('rapports.cumul.export', array_merge($qExport, ['section' => 'graphiques-excel'])) }}" title="Classeur avec graphiques modifiables dans Excel">Excel — graphiques</a>
    <a class="btn btn-sm btn-outline-primary" target="_blank" href="{{ route('rapports.cumul.export', array_merge($qExport, ['section' => 'graphiques-word'])) }}" title="Document Word avec graphiques natifs">Word — graphiques</a>
</div>

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <div class="card border-primary h-100">
            <div class="card-body py-2">
                <h6 class="small text-muted mb-0">Ventes (lignes)</h6>
                <strong class="fs-5">{{ number_format($totalVentes) }}</strong>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-success h-100">
            <div class="card-body py-2">
                <h6 class="small text-muted mb-0">Commerciaux (avec ventes)</h6>
                <strong class="fs-5">{{ number_format($nbCommerciauxAvecVentes) }}</strong>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-info h-100">
            <div class="card-body py-2">
                <h6 class="small text-muted mb-0">Agences (avec ventes)</h6>
                <strong class="fs-5">{{ number_format($nbAgencesAvecVentes) }}</strong>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-warning h-100">
            <div class="card-body py-2">
                <h6 class="small text-muted mb-0">Clients distincts</h6>
                <strong class="fs-5">{{ number_format($nbClientsDistincts) }}</strong>
            </div>
        </div>
    </div>
</div>

<p class="small text-muted mb-2"><strong>Ventes par type de carte</strong> (sur le cumul sélectionné)</p>
<div class="row g-2 mb-4">
    @forelse($typesCarteKpi as $tc)
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card h-100 border-secondary">
            <div class="card-body py-2">
                <h6 class="small text-muted mb-0">{{ $tc['code'] }}</h6>
                <strong class="fs-5">{{ number_format($tc['total']) }}</strong>
                <span class="small text-muted">ventes ({{ number_format($tc['pct'], 1) }} %)</span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12"><p class="text-muted small mb-0">Aucune vente par type sur ce cumul.</p></div>
    @endforelse
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header small py-2"><strong>Mix des ventes par type de carte</strong></div>
            <div class="card-body" style="min-height:220px;"><canvas id="chartCumulTypes"></canvas></div>
            <div class="card-footer small text-muted py-2">
                Total sur le graphique : <strong>{{ number_format($totalVentesTypesGraph) }}</strong> vente(s).
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header small py-2"><strong>Top {{ $topNCommGraph }} vendeurs — part du total</strong></div>
            <div class="card-body" style="min-height:280px;"><canvas id="chartCumulCommerciaux"></canvas></div>
            <div class="card-footer small text-muted py-2">
                Barres = % des ventes cumulées. Total : <strong>{{ number_format($totalVentesCampagne) }}</strong> vente(s).
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header small py-2"><strong>Part des agences</strong></div>
            <div class="card-body" style="min-height:220px;"><canvas id="chartCumulAgences"></canvas></div>
            <div class="card-footer small text-muted py-2">
                Total affiché : <strong>{{ number_format($totalVentesAgGraph) }}</strong> vente(s) (top {{ $topNAgGraph }} + « Autres » si besoin).
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header"><strong>Commerciaux (volume cumulé)</strong></div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Commercial</th>
                            <th>Agence</th>
                            <th class="text-end">Ventes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parCommercial as $row)
                        @php $u = $usersById->get($row->user_id); @endphp
                        <tr>
                            <td>{{ $libelleUser($u) }}</td>
                            <td>{{ $u && $u->agence_id ? ($agencesById->get($u->agence_id)?->nom ?? '—') : '—' }}</td>
                            <td class="text-end">{{ number_format((int) $row->total) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Aucune vente.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header"><strong>Agences (volume cumulé)</strong></div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Agence</th>
                            <th class="text-end">Ventes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parAgence as $row)
                        <tr>
                            <td>{{ $row->agence_id ? ($agencesById->get($row->agence_id)?->nom ?? '?') : '— Sans agence' }}</td>
                            <td class="text-end">{{ number_format((int) $row->total) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted py-3">Aucune vente.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header"><strong>Types de carte (volume cumulé)</strong></div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Type</th>
                    <th class="text-end">Ventes</th>
                    <th class="text-end">Part %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($typesCarteKpi as $tc)
                <tr>
                    <td><span class="badge bg-info">{{ $tc['code'] }}</span></td>
                    <td class="text-end">{{ number_format($tc['total']) }}</td>
                    <td class="text-end">{{ number_format($tc['pct'], 1) }} %</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-3">Aucune vente.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <strong>Clients (au moins une vente sur l’ensemble des campagnes sélectionnées)</strong>
        <span class="small text-muted">{{ $clients->count() }} fiche(s)</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Ville</th>
                    <th>Type carte</th>
                    <th>Commercial (fiche)</th>
                    <th class="text-end">Ventes (cumul)</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $c)
                <tr>
                    <td>{{ $c->prenom }} {{ $c->nom }}</td>
                    <td>{{ $c->telephone ?? '—' }}</td>
                    <td>{{ $c->ville ?? '—' }}</td>
                    <td><span class="badge bg-info">{{ $c->typeCarte?->code ?? '?' }}</span></td>
                    <td>{{ $c->user ? $libelleUser($c->user) : '—' }}</td>
                    <td class="text-end">{{ number_format($c->nb_ventes_cumul) }}</td>
                    <td class="text-end">
                        <a href="{{ route('clients.show', $c) }}" class="btn btn-sm btn-outline-primary">Fiche</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4">Aucun client.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <strong>Détail des ventes (toutes campagnes sélectionnées)</strong>
        <span class="small text-muted">Tri par date décroissante</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Campagne</th>
                    <th>Client</th>
                    <th>Type carte</th>
                    <th>Commercial</th>
                    <th>Agence</th>
                    <th>Activation</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventes as $v)
                <tr>
                    <td>{{ $v->created_at->format('d/m/Y H:i') }}</td>
                    <td><span class="badge bg-light text-dark">{{ $v->campagne?->nom ?? '—' }}</span></td>
                    <td>{{ $v->client->prenom }} {{ $v->client->nom }}</td>
                    <td><span class="badge bg-info">{{ $v->typeCarte?->code ?? '?' }}</span></td>
                    <td>{{ $libelleUser($v->user) }}</td>
                    <td>{{ $v->agence->nom ?? '—' }}</td>
                    <td><span class="badge bg-light text-dark">{{ $v->statut_activation }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">Aucune vente.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ventes->hasPages())
    <div class="card-footer d-flex flex-wrap justify-content-center justify-content-sm-between align-items-center gap-2 py-3">
        <div class="small text-muted order-2 order-sm-1">{{ $ventes->firstItem() }}–{{ $ventes->lastItem() }} / {{ $ventes->total() }}</div>
        <div class="order-1 order-sm-2 overflow-auto w-100 w-sm-auto d-flex justify-content-center">{{ $ventes->links() }}</div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    var nf = new Intl.NumberFormat('fr-FR');
    function tooltipPartVentes(ctx) {
        var raw = ctx.raw;
        var v = typeof raw === 'number' ? raw : (ctx.parsed !== undefined ? ctx.parsed : 0);
        var total = ctx.dataset.data.reduce(function (a, b) { return a + b; }, 0);
        var pct = total > 0 ? (100 * v / total) : 0;
        return (ctx.label ? ctx.label + ' : ' : '') + nf.format(v) + ' ventes (' + nf.format(Math.round(pct * 10) / 10) + ' %)';
    }
    var parTypes = @json($parTypesForChart);
    var chartCommerciaux = @json($chartCommerciauxRows->values());
    var chartAgences = @json($chartAgencesRows->values());
    var palette = ['#0d6efd','#4d8ef7','#6610f2','#6f42c1','#d63384','#fd7e14','#198754','#20c997','#ffc107','#FF6A3A'];
    if (parTypes.length && document.getElementById('chartCumulTypes')) {
        new Chart(document.getElementById('chartCumulTypes'), {
            type: 'doughnut',
            data: {
                labels: parTypes.map(function (r) { return r.code; }),
                datasets: [{
                    label: 'Ventes par type',
                    data: parTypes.map(function (r) { return r.total_ventes; }),
                    backgroundColor: parTypes.map(function (_r, i) { return palette[i % palette.length]; }),
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Répartition des ventes', font: { size: 14, weight: '600' }, padding: { bottom: 4 } },
                    subtitle: { display: true, text: 'Chaque segment = nombre de ventes pour un code type (cumul sélectionné).', color: '#6c757d', font: { size: 11 }, padding: { bottom: 8 } },
                    legend: { position: 'bottom', labels: { boxWidth: 12 } },
                    tooltip: { callbacks: { label: tooltipPartVentes } },
                },
            }
        });
    }
    if (chartCommerciaux.length && document.getElementById('chartCumulCommerciaux')) {
        var commPaletteTop = ['#0d6efd', '#6610f2', '#d63384', '#fd7e14', '#198754'];
        new Chart(document.getElementById('chartCumulCommerciaux'), {
            type: 'bar',
            data: {
                labels: chartCommerciaux.map(function (r) { return r.label; }),
                datasets: [{
                    label: 'Part du total (%)',
                    data: chartCommerciaux.map(function (r) { return r.pct_part; }),
                    backgroundColor: chartCommerciaux.map(function (r, i) {
                        return (r.label && String(r.label).indexOf('Autres') === 0) ? 'rgba(108,117,125,0.75)' : (commPaletteTop[i] || palette[i % palette.length]);
                    }),
                    borderRadius: 4,
                    barThickness: 18,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Part de chaque vendeur sur toutes les ventes cumulées', font: { size: 14, weight: '600' }, padding: { bottom: 4 } },
                    subtitle: { display: true, text: 'Lecture horizontale : % du volume total du cumul.', color: '#6c757d', font: { size: 11 }, padding: { bottom: 6 } },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                var r = chartCommerciaux[ctx.dataIndex];
                                if (!r) return '';
                                return nf.format(r.total_ventes) + ' ventes — ' + nf.format(r.pct_part) + ' % du total';
                            }
                        }
                    },
                },
                scales: {
                    x: {
                        min: 0,
                        max: 100,
                        ticks: {
                            callback: function (v) { return v + ' %'; },
                        },
                        grid: { color: 'rgba(0,0,0,0.06)' },
                    },
                    y: {
                        reverse: true,
                        ticks: { autoSkip: false, font: { size: 11 } },
                        grid: { display: false },
                    },
                },
            }
        });
    }
    if (chartAgences.length && document.getElementById('chartCumulAgences')) {
        new Chart(document.getElementById('chartCumulAgences'), {
            type: 'pie',
            data: {
                labels: chartAgences.map(function (r) { return r.label; }),
                datasets: [{
                    label: 'Ventes',
                    data: chartAgences.map(function (r) { return r.total_ventes; }),
                    backgroundColor: chartAgences.map(function (_r, i) { return palette[(i + 3) % palette.length]; }),
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Part du volume par agence', font: { size: 14, weight: '600' }, padding: { bottom: 4 } },
                    subtitle: { display: true, text: 'Chaque part = ventes de l’agence sur le cumul.', color: '#6c757d', font: { size: 11 }, padding: { bottom: 8 } },
                    legend: { position: 'bottom', labels: { boxWidth: 12 } },
                    tooltip: { callbacks: { label: tooltipPartVentes } },
                },
            }
        });
    }
})();
</script>
@endpush
@endsection
