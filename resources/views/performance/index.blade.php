@extends('layouts.app')

@section('title', 'Performances')

@section('content')
@php
    $libelleRangPerf = static function (?int $r): string {
        if ($r === null || $r < 1) {
            return '—';
        }

        return $r === 1 ? '1ᵉʳ' : $r.'ᵉ';
    };
    $perfExcelQuery = array_filter([
        'du' => request('du'),
        'au' => request('au'),
        'agence' => request('agence'),
        'campagne_id' => request('campagne_id'),
        'compare' => request('compare'),
    ], fn ($v) => $v !== null && $v !== '');
@endphp
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="mb-0">Tableau de bord des performances</h4>
    <div class="d-flex flex-wrap gap-2">
        @unless($vueCommerciale)
        <a href="{{ route('performances.export-excel', $perfExcelQuery) }}" class="btn btn-success btn-sm" target="_blank" title="Résumé, classements commerciaux / agences / types, volumes par semaine, ventes détaillées — mêmes filtres que l’écran.">Exporter Excel — export global</a>
        @endunless
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">Retour au Dashboard</a>
    </div>
</div>
@if($vueChef)
<p class="text-muted small mb-3">Statistiques de votre agence uniquement.</p>
@endif

<div class="card border-primary shadow-sm mb-3">
    <div class="card-header bg-primary text-white"><strong>À retenir pour la réunion</strong></div>
    <div class="card-body small">
        <ul class="mb-0 ps-3">
            <li><strong>Période :</strong> {{ $libellePeriode ?? '—' }}</li>
            @if(!$vueCommerciale)
            <li><strong>Volume :</strong> {{ number_format($stats['total_ventes'] ?? 0) }} vente(s)
                @if($compareEnabled && $compareDelta && $compareDelta['ventes_pct'] !== null)
                    <span class="text-muted">({{ $compareDelta['ventes_pct'] >= 0 ? '+' : '' }}{{ number_format($compareDelta['ventes_pct'], 1, ',', ' ') }} % vs période précédente)</span>
                @elseif($compareEnabled && $compareDelta && $compareDelta['ventes_pct'] === null && ($stats['total_ventes'] ?? 0) > 0)
                    <span class="text-muted">(nouvelle activité vs 0 sur la période précédente)</span>
                @endif
            </li>
            @else
            <li><strong>Mes ventes :</strong> {{ number_format($stats['mes_ventes'] ?? 0) }}</li>
            @if(isset($stats['mon_rang']) && $stats['mon_rang'])
            <li><strong>Mon rang :</strong> {{ $libelleRangPerf((int) $stats['mon_rang']) }}</li>
            @endif
            @endif
            @if($compareEnabled && $compareDelta)
            <li class="text-muted mb-0">{{ $compareDelta['libelle'] }}</li>
            @endif
        </ul>
    </div>
</div>

<p class="small text-muted mb-2">
    <strong>Période affichée :</strong> {{ $libellePeriode ?? '—' }}
</p>
<form method="GET" class="card shadow-sm mb-3">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-3 col-lg-2">
            <label class="form-label small mb-0">Du</label>
            <input type="date" name="du" class="form-control form-control-sm" value="{{ old('du', $filtreIntervalle ? ($du ?? '') : '') }}">
        </div>
        <div class="col-md-3 col-lg-2">
            <label class="form-label small mb-0">Au</label>
            <input type="date" name="au" class="form-control form-control-sm" value="{{ old('au', $filtreIntervalle ? ($au ?? '') : '') }}">
        </div>
        @if(auth()->user()?->isAdmin() || auth()->user()?->isDirection())
        <div class="col-md-4 col-lg-3">
            <label class="form-label small mb-0">Agence</label>
            <select name="agence" class="form-select form-select-sm">
                <option value="">Toutes</option>
                @foreach(\App\Models\Agence::orderBy('nom')->get() as $a)
                <option value="{{ $a->id }}" @selected((string) $agenceId === (string) $a->id)>{{ $a->nom }}</option>
                @endforeach
            </select>
        </div>
        @endif
        @if($campagnesSelect->isNotEmpty())
        <div class="col-md-4 col-lg-3">
            <label class="form-label small mb-0">Campagne</label>
            <select name="campagne_id" class="form-select form-select-sm">
                <option value="">— Par défaut (réf. performances) —</option>
                @foreach($campagnesSelect as $c)
                <option value="{{ $c->id }}" @selected((string) $campagneIdSelected === (string) $c->id)>
                    {{ $c->nom }} ({{ $c->date_debut->format('d/m/y') }}–{{ $c->date_fin->format('d/m/y') }})
                </option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-6 col-md-3 col-lg-2">
            <div class="form-check mt-3">
                <input type="checkbox" name="compare" value="1" class="form-check-input" id="perf_compare" @checked($compareEnabled)>
                <label class="form-check-label small" for="perf_compare">Comparer à la période précédente</label>
            </div>
        </div>
        <div class="col-12 col-lg-auto d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-primary btn-sm">Appliquer</button>
            <a href="{{ route('performances.index', array_filter(['agence' => $agenceId])) }}" class="btn btn-outline-secondary btn-sm">Réinit. filtres</a>
        </div>
    </div>
    <div class="card-footer small text-muted py-2">
        Sans dates : période = campagne choisie ou campagne de référence pour votre agence. Avec <strong>Du</strong> et <strong>Au</strong>, la plage personnalisée prime sur les dates de campagne ; la campagne sélectionnée filtre toujours les ventes sur son <code>campagne_id</code>.
    </div>
</form>

@php
    $performancesDetailQuery = array_filter([
        'du' => ! empty($filtreIntervalle) ? ($du ?? null) : null,
        'au' => ! empty($filtreIntervalle) ? ($au ?? null) : null,
        'agence' => $agenceId ?? null,
        'campagne_id' => $campagneIdSelected,
        'compare' => $compareEnabled ? '1' : null,
    ], fn ($v) => $v !== null && $v !== '');
@endphp

@if($vueCommerciale)
<div class="mb-3">
    <a href="{{ route('performances.commercial.show', array_merge(['user' => $user->id], $performancesDetailQuery)) }}" class="btn btn-outline-primary btn-sm">Voir mon détail (ventes, clients, cartes)</a>
</div>
<div class="row mb-4 g-2">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Mes ventes</h6>
                <h3>{{ $stats['mes_ventes'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6>Mon rang</h6>
                <h3>{{ isset($stats['mon_rang']) && $stats['mon_rang'] ? $libelleRangPerf((int) $stats['mon_rang']) : '—' }}</h3>
            </div>
        </div>
    </div>
</div>
@else
<div class="row mb-3 g-2">
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <h6 class="small">Total ventes</h6>
                <h3 class="mb-0">{{ number_format($stats['total_ventes'] ?? 0) }}</h3>
                @if($compareEnabled && $statsPrev)
                <p class="small mb-0 opacity-75">Avant : {{ number_format($statsPrev['total_ventes']) }}</p>
                @endif
            </div>
        </div>
    </div>
    @foreach($typesCartes as $tc)
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="small">{{ $tc->code }}</h6>
                <h4 class="mb-0">{{ $stats['par_type'][$tc->id] ?? 0 }}</h4>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if(($stats['total_ventes'] ?? 0) > 0)
@php
    $sumVentesTypesPerf = collect($stats['par_type'] ?? [])->sum();
    $sumVentesAgencesPerf = $ventesParAgenceChart->sum('ventes');
    $totalVentesPerf = (int) ($stats['total_ventes'] ?? 0);
    $perfPeriodSlug = \Illuminate\Support\Str::slug(\Illuminate\Support\Str::ascii(\Illuminate\Support\Str::limit($libellePeriode ?? 'periode', 48, '')), '-');
    $perfExportConfig = [
        'fileBase' => 'graphiques-performances-'.($campagneIdSelected ?: 'ref').'-'.($perfPeriodSlug !== '' ? $perfPeriodSlug : 'export-'.now()->format('Ymd-His')),
        'docTitle' => 'Performances — '.($libellePeriode ?? '—'),
        'items' => [
            ['id' => 'chartPerfTopCommerciaux', 'title' => 'Top commercial — ventes'],
            ['id' => 'chartPerfAgences', 'title' => 'Répartition — part des agences (% ventes)'],
            ['id' => 'chartPerfTypes', 'title' => 'Répartition — ventes par type de carte'],
        ],
    ];
@endphp
<div class="d-flex justify-content-end mb-2 flex-wrap gap-2" data-gda-export='@json($perfExportConfig)'>
    <button type="button" class="btn btn-sm btn-outline-primary gda-export-word">Exporter les graphiques (Word)</button>
</div>
<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header small"><strong>Top commercial — ventes</strong></div>
            <div class="card-body" style="min-height:280px;">
                <canvas id="chartPerfTopCommerciaux"></canvas>
            </div>
            <div class="card-footer small text-muted py-2">
                <strong>Top 5</strong> du classement — mêmes ventes que le tableau et les totaux (filtre agence appliqué sur les ventes si vous en choisissez une). Total général période : <strong>{{ number_format($totalVentesPerf) }}</strong> vente(s).
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header small"><strong>Répartition — part des agences (% ventes)</strong></div>
            <div class="card-body" style="min-height:280px;">
                <canvas id="chartPerfAgences"></canvas>
            </div>
            <div class="card-footer small text-muted py-2">
                Chaque part = <strong>nombre de ventes</strong> de l’agence / total des ventes du périmètre (filtres du formulaire). Total : <strong>{{ number_format($sumVentesAgencesPerf) }}</strong> vente(s).
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header small"><strong>Répartition — ventes par type de carte</strong></div>
            <div class="card-body" style="min-height:280px;">
                <canvas id="chartPerfTypes"></canvas>
            </div>
            <div class="card-footer small text-muted py-2">
                Chaque barre = <strong>ventes</strong> pour le code type affiché. Total (tous types) : <strong>{{ number_format($sumVentesTypesPerf) }}</strong> vente(s) — cohérent avec le total général si la même période s’applique.
            </div>
        </div>
    </div>
</div>
@endif
@endif

<div class="card shadow-sm">
    <div class="card-header">
        <strong>@if($vueCommerciale)1re place de la campagne et ma position @else Classement des commerciaux @endif</strong>
        @if($vueCommerciale && !empty($campagneRef))
            <span class="text-muted small fw-normal">— {{ $campagneRef->nom }}</span>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Commercial</th>
                    <th class="text-end">Nombre de ventes</th>
                    @unless($vueCommerciale)
                    <th class="text-end">Part % volume</th>
                    @endunless
                    <th>Prime (estimée)</th>
                    @if(!$vueCommerciale)
                    <th class="text-end">Détail</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $campagnePrime = $campagneRef ?? \App\Models\Campagne::getActiveForAgence($agenceId);
                    $totalVentesPerfClassement = !$vueCommerciale
                        ? (int) ($stats['total_pourcent_volume'] ?? $stats['total_ventes'] ?? 0)
                        : 0;
                    $userEstPremier = $vueCommerciale && $classementLigneTop1 && (int) $user->id === (int) $classementLigneTop1['user_id'];
                @endphp
                @if($vueCommerciale)
                    @if($classementLigneTop1)
                        @php $c = $classementLigneTop1; @endphp
                    <tr>
                        <td><span class="badge bg-warning text-dark">Top 1</span></td>
                        <td>
                            {{ $c['user_name'] }}
                            @if($userEstPremier)
                                <span class="badge bg-secondary ms-1">vous</span>
                            @endif
                        </td>
                        <td class="text-end">{{ number_format($c['total_ventes']) }}</td>
                        <td>
                            @if($campagnePrime){{ number_format($campagnePrime->prime_meilleur_vendeur) }} F
                            @else - @endif
                        </td>
                    </tr>
                    @endif
                    @if($ligneCommercialConnecte && ! $userEstPremier)
                    <tr class="table-secondary"><td colspan="4" class="small fw-bold">Ma position</td></tr>
                    <tr class="table-info">
                        <td><span class="badge bg-dark">{{ $libelleRangPerf((int) $ligneCommercialConnecte['rang']) }}</span></td>
                        <td>
                            {{ $ligneCommercialConnecte['user_name'] }}
                            <span class="badge bg-secondary ms-1">vous</span>
                        </td>
                        <td class="text-end">{{ number_format($ligneCommercialConnecte['total_ventes']) }}</td>
                        <td>-</td>
                    </tr>
                    @endif
                    @if(!$classementLigneTop1 && !$ligneCommercialConnecte)
                    <tr><td colspan="4" class="text-center py-4">Aucun classement à afficher pour cette période.</td></tr>
                    @endif
                @else
                    @foreach($classement as $c)
                    <tr>
                        <td>
                            @if($c['rang'] == 1)<span class="badge bg-warning text-dark">Top 1</span>
                            @elseif($c['rang'] == 2)<span class="badge bg-secondary">Top 2</span>
                            @else{{ $c['rang'] }}@endif
                        </td>
                        <td>{{ $c['user_name'] }}</td>
                        <td class="text-end">{{ number_format($c['total_ventes']) }}</td>
                        <td class="text-end">
                            @if($totalVentesPerfClassement > 0)
                                {{ number_format($c['total_ventes'] / $totalVentesPerfClassement * 100, 1, ',', ' ') }} %
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($campagnePrime && $c['rang'] == 1){{ number_format($campagnePrime->prime_meilleur_vendeur) }} F
                            @else - @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('performances.commercial.show', array_merge(['user' => $c['user_id']], $performancesDetailQuery)) }}" class="btn btn-sm btn-outline-primary">Détail</a>
                        </td>
                    </tr>
                    @endforeach
                    @if($classement->isEmpty())
                    <tr><td colspan="6" class="text-center py-4">Aucun commercial à afficher.</td></tr>
                    @endif
                @endif
            </tbody>
        </table>
    </div>
</div>

@if(!$vueCommerciale)
<div class="card shadow-sm mt-3">
    <div class="card-header"><strong>Classement des agences</strong></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Rang</th>
                    <th>Agence</th>
                    <th class="text-end">Nombre de ventes</th>
                    <th class="text-end">Part % volume</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classementAgences as $a)
                <tr>
                    <td>
                        @if($a['rang'] === 1)<span class="badge bg-warning text-dark">Top 1</span>
                        @elseif($a['rang'] === 2)<span class="badge bg-secondary">Top 2</span>
                        @else{{ $a['rang'] }}@endif
                    </td>
                    <td>{{ $a['agence_nom'] }}</td>
                    <td class="text-end">{{ number_format($a['total_ventes']) }}</td>
                    <td class="text-end">{{ number_format($a['pct_volume'], 1, ',', ' ') }} %</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Aucune vente sur la période et les filtres choisis.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm mt-3">
    <div class="card-header"><strong>Classement des types de cartes</strong></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Rang</th>
                    <th>Type de carte</th>
                    <th class="text-end">Nombre de ventes</th>
                    <th class="text-end">Part % volume</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classementTypesCartes as $t)
                <tr>
                    <td>
                        @if($t['rang'] === 1)<span class="badge bg-warning text-dark">Top 1</span>
                        @elseif($t['rang'] === 2)<span class="badge bg-secondary">Top 2</span>
                        @else{{ $t['rang'] }}@endif
                    </td>
                    <td><code class="small">{{ $t['code'] }}</code></td>
                    <td class="text-end">{{ number_format($t['total_ventes']) }}</td>
                    <td class="text-end">{{ number_format($t['pct_volume'], 1, ',', ' ') }} %</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Aucune vente sur la période et les filtres choisis.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

@unless($vueCommerciale)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="{{ asset('js/gda-chart-export.js') }}"></script>
<script>
(function () {
    var nf = new Intl.NumberFormat('fr-FR');
    /** Valeur numérique du point (barres verticales : axe y ; barres horizontales indexAxis 'y' : axe x). */
    function valeurTooltipBarre(ctx) {
        if (typeof ctx.raw === 'number' && !isNaN(ctx.raw)) {
            return ctx.raw;
        }
        if (ctx.parsed && ctx.chart && ctx.chart.options && ctx.chart.options.indexAxis === 'y') {
            return typeof ctx.parsed.x === 'number' ? ctx.parsed.x : 0;
        }
        if (ctx.parsed && typeof ctx.parsed.y === 'number') {
            return ctx.parsed.y;
        }
        return 0;
    }
    function tooltipVentes(ctx) {
        var val = valeurTooltipBarre(ctx);
        var suffix = val <= 1 ? ' vente' : ' ventes';
        var prefix = (ctx.dataset && ctx.dataset.label) ? (ctx.dataset.label + ' : ') : '';
        return prefix + nf.format(val) + suffix;
    }
    var dataTopCommerciaux = @json($topCommerciauxChart ?? []);
    var dataAgences = @json($ventesParAgenceChart ?? []);
    var totalVentesPeriode = {{ (int) ($stats['total_ventes'] ?? 0) }};
    var typesLabels = @json($typesCartes->pluck('code')->values());
    var typesIds = @json($typesCartes->pluck('id')->values());
    var parType = @json($stats['par_type'] ?? []);
    var valsTypes = typesIds.map(function (id) { return parseInt(parType[id] || parType[String(id)] || 0, 10); });
    var palette = ['#0d6efd','#4d8ef7','#6610f2','#6f42c1','#d63384','#fd7e14','#198754','#20c997','#ffc107','#FF6A3A'];
    function tooltipPartAgences(ctx) {
        var val = typeof ctx.raw === 'number' ? ctx.raw : 0;
        var sum = dataAgences.reduce(function (s, r) { return s + (r.ventes || 0); }, 0) || totalVentesPeriode || 1;
        var pct = sum > 0 ? (val / sum) * 100 : 0;
        var lab = ctx.label ? ctx.label + ' : ' : '';
        return lab + nf.format(val) + ' ventes (' + pct.toLocaleString('fr-FR', { maximumFractionDigits: 1 }) + ' %)';
    }
    if (dataTopCommerciaux.length && document.getElementById('chartPerfTopCommerciaux')) {
        var topLabels = dataTopCommerciaux.map(function (r) { return r.label; });
        new Chart(document.getElementById('chartPerfTopCommerciaux'), {
            type: 'bar',
            data: {
                labels: topLabels,
                datasets: [{ label: 'Ventes', data: dataTopCommerciaux.map(function (r) { return r.ventes; }), backgroundColor: 'rgba(255,106,58,0.85)', borderColor: '#FF6A3A', borderWidth: 1 }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Nombre de ventes par commercial (top 5)', font: { size: 14, weight: '600' }, padding: { bottom: 4 } },
                    subtitle: { display: true, text: 'Période et filtres = ceux du formulaire ci-dessus.', color: '#6c757d', font: { size: 11 }, padding: { bottom: 8 } },
                    legend: { display: false },
                    tooltip: { callbacks: { label: tooltipVentes } },
                },
                scales: { x: { beginAtZero: true, ticks: { callback: function (v) { return nf.format(v); } } } }
            }
        });
    }
    if (dataAgences.length && document.getElementById('chartPerfAgences')) {
        var sumAg = dataAgences.reduce(function (s, r) { return s + (r.ventes || 0); }, 0);
        new Chart(document.getElementById('chartPerfAgences'), {
            type: 'doughnut',
            data: {
                labels: dataAgences.map(function (r) { return r.label; }),
                datasets: [{
                    data: dataAgences.map(function (r) { return r.ventes; }),
                    backgroundColor: dataAgences.map(function (_r, i) { return palette[i % palette.length]; }),
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Part de chaque agence (volume de ventes)', font: { size: 14, weight: '600' }, padding: { bottom: 4 } },
                    subtitle: { display: true, text: 'Pourcentages calculés sur le total des ventes du périmètre (' + nf.format(sumAg) + ').', color: '#6c757d', font: { size: 11 }, padding: { bottom: 8 } },
                    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } },
                    tooltip: { callbacks: { label: tooltipPartAgences } },
                }
            }
        });
    }
    if (valsTypes.some(function (v) { return v > 0; }) && document.getElementById('chartPerfTypes')) {
        new Chart(document.getElementById('chartPerfTypes'), {
            type: 'bar',
            data: {
                labels: typesLabels,
                datasets: [{ label: 'Ventes par type', data: valsTypes, backgroundColor: typesLabels.map(function (_c, i) { return palette[i % palette.length]; }) }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Ventes par code type de carte', font: { size: 14, weight: '600' }, padding: { bottom: 4 } },
                    subtitle: { display: true, text: 'Hauteur = nombre de ventes enregistrées pour chaque type (même périmètre que le tableau).', color: '#6c757d', font: { size: 11 }, padding: { bottom: 8 } },
                    legend: { position: 'bottom', labels: { boxWidth: 12 } },
                    tooltip: { callbacks: { label: tooltipVentes } },
                },
                scales: { y: { beginAtZero: true, ticks: { callback: function (v) { return nf.format(v); } } } }
            }
        });
    }
    if (typeof gdaInitChartExports === 'function') {
        gdaInitChartExports();
    }
})();
</script>
@endpush
@endunless
@endsection
