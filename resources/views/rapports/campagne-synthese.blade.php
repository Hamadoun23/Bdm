@extends('layouts.app')

@section('title', 'Synthèse — '.$campagne->nom)

@section('content')
@php
    $qExp = array_filter([
        'du' => request('du'),
        'au' => request('au'),
        'agence_id' => request('agence_id'),
        'user_id' => request('user_id'),
    ], fn ($v) => $v !== null && $v !== '');
@endphp
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <h4 class="mb-0">Synthèse campagne : {{ $campagne->nom }}</h4>
        <p class="small text-muted mb-0">
            Période affichée : <strong>{{ $dateDebut->format('d/m/Y') }}</strong> → <strong>{{ $dateFin->format('d/m/Y') }}</strong>
            (limitée aux dates de la campagne)
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('rapports.campagnes.ventes', array_merge(['campagne' => $campagne], $qExp)) }}" class="btn btn-outline-secondary btn-sm">Liste ventes (mêmes filtres)</a>
        <a href="{{ route('rapports.index') }}" class="btn btn-outline-secondary btn-sm">← Rapports</a>
    </div>
</div>

<form method="GET" class="card shadow-sm mb-4">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-2">
            <label class="form-label small mb-0">Du</label>
            <input type="date" name="du" class="form-control form-control-sm" value="{{ request('du', $dateDebut->format('Y-m-d')) }}" min="{{ $campagne->date_debut->format('Y-m-d') }}" max="{{ $campagne->date_fin->format('Y-m-d') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-0">Au</label>
            <input type="date" name="au" class="form-control form-control-sm" value="{{ request('au', $dateFin->format('Y-m-d')) }}" min="{{ $campagne->date_debut->format('Y-m-d') }}" max="{{ $campagne->date_fin->format('Y-m-d') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-0">Agence</label>
            <select name="agence_id" class="form-select form-select-sm">
                <option value="">— Toutes (périmètre campagne) —</option>
                @foreach($agencesChoix as $a)
                    <option value="{{ $a->id }}" @selected((string) $filtreAgenceId === (string) $a->id)>{{ $a->nom }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-0">Commercial</label>
            <select name="user_id" class="form-select form-select-sm">
                <option value="">— Tous —</option>
                @foreach($commerciauxChoix as $u)
                    <option value="{{ $u->id }}" @selected((string) $filtreUserId === (string) $u->id)>
                        {{ $u->prenom ? trim($u->prenom.' '.$u->name) : $u->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">Appliquer</button>
        </div>
    </div>
</form>

@php $qXlsx = array_merge($qExp, ['format' => 'xlsx']); @endphp
<div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
    <span class="small text-muted me-2">Excel (.xlsx) :</span>
    <a class="btn btn-sm btn-success" target="_blank" href="{{ route('rapports.campagnes.export', array_merge(['campagne' => $campagne->id, 'section' => 'all'], $qXlsx)) }}"><strong>Classeur complet</strong> (toutes feuilles)</a>
    <a class="btn btn-sm btn-outline-success" target="_blank" href="{{ route('rapports.campagnes.export', array_merge(['campagne' => $campagne->id, 'section' => 'ventes'], $qXlsx)) }}">Ventes détaillées</a>
    <a class="btn btn-sm btn-outline-success" target="_blank" href="{{ route('rapports.campagnes.export', array_merge(['campagne' => $campagne->id, 'section' => 'commerciaux'], $qXlsx)) }}">Commerciaux</a>
    <a class="btn btn-sm btn-outline-success" target="_blank" href="{{ route('rapports.campagnes.export', array_merge(['campagne' => $campagne->id, 'section' => 'agences'], $qXlsx)) }}">Agences</a>
    <a class="btn btn-sm btn-outline-success" target="_blank" href="{{ route('rapports.campagnes.export', array_merge(['campagne' => $campagne->id, 'section' => 'types'], $qXlsx)) }}">Types de carte</a>
    <a class="btn btn-sm btn-outline-success" target="_blank" href="{{ route('rapports.campagnes.export', array_merge(['campagne' => $campagne->id, 'section' => 'semaines'], $qXlsx)) }}">Semaines</a>
    <a class="btn btn-sm btn-outline-success" target="_blank" href="{{ route('rapports.campagnes.export', array_merge(['campagne' => $campagne->id, 'section' => 'mois'], $qXlsx)) }}">Mois</a>
</div>

<div class="row g-3 mb-4">
    @php $r = $synthese['resume']; @endphp
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100 border-primary">
            <div class="card-body py-3">
                <h6 class="small text-muted mb-1">Total ventes</h6>
                <h4 class="mb-0">{{ number_format($r['total_ventes']) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100">
            <div class="card-body py-3">
                <h6 class="small text-muted mb-1">Commerciaux (périmètre)</h6>
                <h4 class="mb-0">{{ $r['nb_commerciaux_perimetre'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100">
            <div class="card-body py-3">
                <h6 class="small text-muted mb-1">Avec ventes</h6>
                <h4 class="mb-0 text-success">{{ $r['nb_avec_ventes'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100">
            <div class="card-body py-3">
                <h6 class="small text-muted mb-1">À 0 vente</h6>
                <h4 class="mb-0 text-warning">{{ $r['nb_zero_vente'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100">
            <div class="card-body py-3">
                <h6 class="small text-muted mb-1">Agences actives</h6>
                <h4 class="mb-0">{{ $r['nb_agences_avec_ventes'] }}</h4>
            </div>
        </div>
    </div>
</div>

@php
    $totalVentesTypesGraph = collect($synthese['par_type_carte'])->sum('total_ventes');
    $topNCommGraph = 5;
    $topNAgGraph = 10;
    $totalVentesCampagne = (int) ($synthese['resume']['total_ventes'] ?? 0);
    $commAvecVentes = $synthese['commerciaux']->filter(fn ($l) => $l['total_ventes'] > 0)->sortByDesc('total_ventes')->values();
    $chartCommerciauxRows = collect();
    $denomPart = $totalVentesCampagne > 0 ? $totalVentesCampagne : 1;
    foreach ($commAvecVentes->take($topNCommGraph) as $l) {
        $chartCommerciauxRows->push([
            'label' => $l['user_name'],
            'total_ventes' => $l['total_ventes'],
            'pct_part' => round(100 * $l['total_ventes'] / $denomPart, 2),
        ]);
    }
    $tailComm = $commAvecVentes->slice($topNCommGraph);
    if ($tailComm->isNotEmpty()) {
        $vAutres = (int) $tailComm->sum('total_ventes');
        $chartCommerciauxRows->push([
            'label' => 'Autres commerciaux ('.$tailComm->count().')',
            'total_ventes' => $vAutres,
            'pct_part' => round(100 * $vAutres / $denomPart, 2),
        ]);
    }
    $agAvecVentes = $synthese['agences']->filter(fn ($l) => $l['total_ventes'] > 0)->sortByDesc('total_ventes')->values();
    $chartAgencesRows = collect();
    foreach ($agAvecVentes->take($topNAgGraph) as $l) {
        $chartAgencesRows->push(['label' => $l['agence_nom'], 'total_ventes' => $l['total_ventes']]);
    }
    $tailAg = $agAvecVentes->slice($topNAgGraph);
    if ($tailAg->isNotEmpty()) {
        $chartAgencesRows->push([
            'label' => 'Autres agences ('.$tailAg->count().')',
            'total_ventes' => (int) $tailAg->sum('total_ventes'),
        ]);
    }
    $totalVentesAgGraph = (int) $chartAgencesRows->sum('total_ventes');
@endphp
<div class="d-flex justify-content-end mb-2 flex-wrap gap-2">
    <a href="{{ route('rapports.campagnes.synthese.export-graphiques-excel', array_merge(['campagne' => $campagne->id], $qExp)) }}" class="btn btn-sm btn-success" target="_blank" title="Classeur .xlsx : graphiques modifiables dans Excel (données sur feuilles séparées)">Export graphiques (Excel)</a>
    <a href="{{ route('rapports.campagnes.synthese.export-graphiques-word', array_merge(['campagne' => $campagne->id], $qExp)) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="Document Word : graphiques natifs modifiables (sans tableau de données)">Export graphiques (Word)</a>
</div>
<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header small py-2"><strong>Mix des ventes par type de carte</strong></div>
            <div class="card-body" style="min-height:220px;"><canvas id="chartSyntheseTypes"></canvas></div>
            <div class="card-footer small text-muted py-2">
                Chaque portion du graphique correspond au <strong>nombre de ventes</strong> pour le type de carte (code). Total sur le graphique : <strong>{{ number_format($totalVentesTypesGraph) }}</strong> vente(s).
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header small py-2"><strong>Top {{ $topNCommGraph }} vendeurs — part du total</strong></div>
            <div class="card-body" style="min-height:280px;"><canvas id="chartSyntheseCommerciaux"></canvas></div>
            <div class="card-footer small text-muted py-2">
                Barres = <strong>% des ventes campagne</strong> (période et filtres actuels). Le n<sup>o</sup>&nbsp;1 en haut ; le reste des vendeurs sous « Autres » si besoin. Total campagne : <strong>{{ number_format($totalVentesCampagne) }}</strong> vente(s).
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header small py-2"><strong>Part des agences (plus de ventes)</strong></div>
            <div class="card-body" style="min-height:220px;"><canvas id="chartSyntheseAgences"></canvas></div>
            <div class="card-footer small text-muted py-2">
                Répartition du <strong>volume de ventes</strong> entre les agences (top {{ $topNAgGraph }}, « Autres agences » si plusieurs restantes). Total : <strong>{{ number_format($totalVentesAgGraph) }}</strong> vente(s).
            </div>
        </div>
    </div>
</div>

@php
    $qTelSynthese = array_merge(
        ['campagne' => $campagne],
        array_filter([
            'date_debut' => request('du', $dateDebut->format('Y-m-d')),
            'date_fin' => request('au', $dateFin->format('Y-m-d')),
            'user_id' => request('user_id'),
            'agence_id' => request('agence_id'),
        ], fn ($v) => $v !== null && $v !== '')
    );
@endphp
<div class="card shadow-sm mb-4">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <strong>Reporting téléphonique (période et filtres comme ci-dessus)</strong>
        <a href="{{ route('rapports.campagnes.reporting-telephonique', $qTelSynthese) }}" class="btn btn-sm btn-primary">Liste des fiches</a>
    </div>
    <div class="card-body">
        <div class="row g-2 small">
            <div class="col-6 col-md-4">Fiches : <strong>{{ number_format($telephonique['nb_fiches']) }}</strong></div>
            <div class="col-6 col-md-4">Appels émis (cumul) : <strong>{{ number_format($telephonique['appels_emis']) }}</strong></div>
            <div class="col-6 col-md-4">Joignables : <strong>{{ number_format($telephonique['appels_joignables']) }}</strong></div>
            <div class="col-6 col-md-4">Non joignables : <strong>{{ number_format($telephonique['appels_non_joignables']) }}</strong></div>
            <div class="col-6 col-md-4">Clients intéressés (cumul) : <strong>{{ number_format($telephonique['clients_interesses']) }}</strong></div>
            <div class="col-6 col-md-4">Déjà servis (cumul) : <strong>{{ number_format($telephonique['clients_deja_servis']) }}</strong></div>
        </div>
        <p class="small text-muted mb-0 mt-2">Comprend les fiches avec <code>campagne_id</code> = cette campagne et, le cas échéant, les fiches sans rattachement mais avec date dans la fenêtre et téléopératrice dans le périmètre agences de la campagne.</p>
    </div>
</div>

<ul class="nav nav-tabs mb-3 flex-nowrap overflow-x-auto gap-1" role="tablist">
    <li class="nav-item flex-shrink-0" role="presentation"><button class="nav-link active text-nowrap" data-bs-toggle="tab" data-bs-target="#tab-commerciaux" type="button">Commerciaux</button></li>
    <li class="nav-item flex-shrink-0" role="presentation"><button class="nav-link text-nowrap" data-bs-toggle="tab" data-bs-target="#tab-agences" type="button">Agences</button></li>
    <li class="nav-item flex-shrink-0" role="presentation"><button class="nav-link text-nowrap" data-bs-toggle="tab" data-bs-target="#tab-types" type="button">Types de carte</button></li>
    <li class="nav-item flex-shrink-0" role="presentation"><button class="nav-link text-nowrap" data-bs-toggle="tab" data-bs-target="#tab-temps" type="button">Semaines / Mois</button></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade show active" id="tab-commerciaux">
        <div class="table-responsive card shadow-sm">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light"><tr><th>Rang</th><th>Commercial</th><th>Agence</th><th class="text-end">Ventes</th><th class="text-end text-nowrap">Détail</th></tr></thead>
                <tbody>
                    @foreach($synthese['commerciaux'] as $l)
                    <tr @class(['table-warning' => $l['total_ventes'] === 0])>
                        <td>{{ $l['rang'] }}ᵉ</td>
                        <td>{{ $l['user_name'] }}</td>
                        <td>{{ $l['agence_nom'] ?? '—' }}</td>
                        <td class="text-end">{{ number_format($l['total_ventes']) }}</td>
                        <td class="text-end">
                            <a href="{{ route('rapports.campagnes.ventes', array_merge(['campagne' => $campagne->id], array_merge($qExp, ['user_id' => $l['user_id']]))) }}" class="btn btn-sm btn-outline-primary" title="Liste des ventes pour ce commercial (même période et filtres du formulaire)">Détail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="tab-agences">
        <div class="table-responsive card shadow-sm">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light"><tr><th>Agence</th><th class="text-end">Ventes</th><th class="text-end">Part %</th><th class="text-end">Nb commerciaux</th><th class="text-end text-nowrap">Détail</th></tr></thead>
                <tbody>
                    @forelse($synthese['agences'] as $l)
                    <tr>
                        <td>{{ $l['agence_nom'] }}</td>
                        <td class="text-end">{{ number_format($l['total_ventes']) }}</td>
                        <td class="text-end">{{ number_format($l['pct_volume'], 2, ',', ' ') }} %</td>
                        <td class="text-end">{{ $l['nb_commerciaux'] }}</td>
                        <td class="text-end">
                            <a href="{{ route('rapports.campagnes.ventes', array_merge(['campagne' => $campagne->id], array_merge($qExp, ['agence_id' => $l['agence_id']]))) }}" class="btn btn-sm btn-outline-primary" title="Liste des ventes pour cette agence (même période et filtres du formulaire)">Détail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucune vente sur cette période.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="tab-types">
        <div class="table-responsive card shadow-sm">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light"><tr><th>Type</th><th class="text-end">Ventes</th><th class="text-end">Part %</th><th class="text-end text-nowrap">Détail</th></tr></thead>
                <tbody>
                    @forelse($synthese['par_type_carte'] as $l)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $l['code'] }}</span></td>
                        <td class="text-end">{{ number_format($l['total_ventes']) }}</td>
                        <td class="text-end">{{ number_format($l['pct_volume'], 2, ',', ' ') }} %</td>
                        <td class="text-end">
                            @if($l['type_carte_id'] !== null)
                                <a href="{{ route('rapports.campagnes.ventes', array_merge(['campagne' => $campagne->id], array_merge($qExp, ['type_carte_id' => $l['type_carte_id']]))) }}" class="btn btn-sm btn-outline-primary" title="Liste des ventes pour ce type de carte (même période et filtres du formulaire)">Détail</a>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Aucune vente.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="tab-temps">
        <div class="row g-3 align-items-stretch">
            <div class="col-12 col-lg-6">
                <div class="table-responsive card shadow-sm h-100">
                    <table class="table table-hover table-sm mb-0 w-100"><thead class="table-light"><tr><th>Période</th><th class="text-end">Ventes</th></tr></thead>
                    <tbody>
                        @forelse($synthese['par_semaine'] as $l)
                        <tr><td class="text-wrap text-break">{{ $l['libelle'] }}</td><td class="text-end text-nowrap">{{ $l['total_ventes'] }}</td></tr>
                        @empty
                        <tr><td colspan="2" class="text-muted">—</td></tr>
                        @endforelse
                    </tbody></table>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="table-responsive card shadow-sm h-100">
                    <table class="table table-hover table-sm mb-0 w-100"><thead class="table-light"><tr><th>Mois</th><th class="text-end">Ventes</th></tr></thead>
                    <tbody>
                        @forelse($synthese['par_mois'] as $l)
                        <tr><td class="text-wrap">{{ $l['libelle'] }}</td><td class="text-end text-nowrap">{{ $l['total_ventes'] }}</td></tr>
                        @empty
                        <tr><td colspan="2" class="text-muted">—</td></tr>
                        @endforelse
                    </tbody></table>
                </div>
            </div>
        </div>
    </div>
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
    var parTypes = @json($synthese['par_type_carte']->values());
    var chartCommerciaux = @json($chartCommerciauxRows->values());
    var chartAgences = @json($chartAgencesRows->values());
    var palette = ['#0d6efd','#4d8ef7','#6610f2','#6f42c1','#d63384','#fd7e14','#198754','#20c997','#ffc107','#FF6A3A'];
    if (parTypes.length && document.getElementById('chartSyntheseTypes')) {
        new Chart(document.getElementById('chartSyntheseTypes'), {
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
                    subtitle: { display: true, text: 'Chaque segment = nombre de ventes pour un code type (période affichée).', color: '#6c757d', font: { size: 11 }, padding: { bottom: 8 } },
                    legend: { position: 'bottom', labels: { boxWidth: 12 } },
                    tooltip: { callbacks: { label: tooltipPartVentes } },
                },
            }
        });
    }
    if (chartCommerciaux.length && document.getElementById('chartSyntheseCommerciaux')) {
        var commPaletteTop = ['#0d6efd', '#6610f2', '#d63384', '#fd7e14', '#198754'];
        new Chart(document.getElementById('chartSyntheseCommerciaux'), {
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
                    title: { display: true, text: 'Part de chaque vendeur sur toutes les ventes', font: { size: 14, weight: '600' }, padding: { bottom: 4 } },
                    subtitle: { display: true, text: 'Lecture horizontale : % = part du volume total de la campagne (écran + filtres).', color: '#6c757d', font: { size: 11 }, padding: { bottom: 6 } },
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
    if (chartAgences.length && document.getElementById('chartSyntheseAgences')) {
        new Chart(document.getElementById('chartSyntheseAgences'), {
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
                    subtitle: { display: true, text: 'Disque plein (camembert) : chaque part = ventes de l’agence sur la période.', color: '#6c757d', font: { size: 11 }, padding: { bottom: 8 } },
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
