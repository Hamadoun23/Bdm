@php
    $periode_debut = $periode_debut ?? $campagne->date_debut;
    $periode_fin = $periode_fin ?? $campagne->date_fin;
    $preset = $preset ?? 'campagne';
    $periodeLib = match ($preset) {
        'semaine' => 'Semaine en cours',
        'mois' => 'Mois civil en cours',
        'perso' => 'Intervalle personnalisé',
        default => 'Toute la durée de la campagne',
    };
    $tc = $telephoniqueCampagne ?? [
        'nb_fiches' => 0, 'appels_emis' => 0, 'appels_joignables' => 0, 'appels_non_joignables' => 0,
        'clients_interesses' => 0, 'clients_deja_servis' => 0,
    ];
    $qTelCampagne = [
        'campagne' => $campagne,
        'date_debut' => $periode_debut->format('Y-m-d'),
        'date_fin' => $periode_fin->format('Y-m-d'),
    ];
@endphp
<div class="tab-pane fade @if(($activeTab ?? '') === 'performances') show active @endif" id="tab-performances" role="tabpanel">
    <div class="card shadow-sm mb-4 border-primary border-opacity-25">
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center gap-2">
            <strong>Période d’analyse</strong>
            <span class="badge bg-secondary">{{ $periode_debut->format('d/m/Y') }} → {{ $periode_fin->format('d/m/Y') }}</span>
        </div>
        <div class="card-body py-3">
            <form method="get" id="form-periode-campagne" class="row g-2 align-items-end mb-2">
                <input type="hidden" name="tab" value="performances">
                <div class="col-12 col-md-3">
                    <label class="form-label small mb-0">Préréglage</label>
                    <select name="periode" id="select-periode-preset" class="form-select form-select-sm">
                        <option value="campagne" @selected($preset === 'campagne')>Toute la campagne</option>
                        <option value="semaine" @selected($preset === 'semaine')>Semaine en cours</option>
                        <option value="mois" @selected($preset === 'mois')>Mois en cours</option>
                        <option value="perso" @selected($preset === 'perso')>Dates au choix</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-0">Du</label>
                    <input type="date" name="date_debut" class="form-control form-control-sm" value="{{ request('date_debut', $periode_debut->format('Y-m-d')) }}" @if($preset !== 'perso') disabled @endif>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-0">Au</label>
                    <input type="date" name="date_fin" class="form-control form-control-sm" value="{{ request('date_fin', $periode_fin->format('Y-m-d')) }}" @if($preset !== 'perso') disabled @endif>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary">Appliquer</button>
                </div>
            </form>
            <p class="small text-muted mb-0"><strong>{{ $periodeLib }}</strong></p>
        </div>
    </div>

    <div class="card shadow-sm mb-4 border-info border-opacity-25">
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center gap-2">
            <strong>Reporting téléphonique</strong>
            <a href="{{ route('rapports.campagnes.reporting-telephonique', $qTelCampagne) }}" class="btn btn-sm btn-outline-primary">Liste des fiches</a>
        </div>
        <div class="card-body py-3">
            <div class="row g-2 small">
                <div class="col-6 col-md-4">Fiches : <strong>{{ number_format($tc['nb_fiches']) }}</strong></div>
                <div class="col-6 col-md-4">Appels émis : <strong>{{ number_format($tc['appels_emis']) }}</strong></div>
                <div class="col-6 col-md-4">Joignables : <strong>{{ number_format($tc['appels_joignables']) }}</strong></div>
                <div class="col-6 col-md-4">Non joignables : <strong>{{ number_format($tc['appels_non_joignables']) }}</strong></div>
                <div class="col-6 col-md-4">Clients intéressés : <strong>{{ number_format($tc['clients_interesses']) }}</strong></div>
                <div class="col-6 col-md-4">Déjà servis : <strong>{{ number_format($tc['clients_deja_servis']) }}</strong></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header"><strong>Performances commerciales</strong></div>
        <div class="card-body">
            <div class="row mb-4 g-3">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body py-3">
                            <h6 class="mb-0">Total ventes</h6>
                            <h3 class="mb-0">{{ $stats['total_ventes'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6>Ventes par type de carte</h6>
                    <table class="table table-sm table-striped">
                        <thead class="table-light"><tr><th>Type</th><th class="text-end">Nombre</th></tr></thead>
                        <tbody>
                            @foreach($typesCartes as $typeCarte)
                            @php $p = $stats['par_type'][$typeCarte->id] ?? null; @endphp
                            <tr><td>{{ $typeCarte->code }}</td><td class="text-end">{{ $p?->nb ?? 0 }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Ventes par agence</h6>
                    <table class="table table-sm table-striped">
                        <thead class="table-light"><tr><th>Agence</th><th class="text-end">Nombre</th></tr></thead>
                        <tbody>
                            @foreach($stats['par_agence'] as $pa)
                            <tr><td>{{ $pa->agence_nom }}</td><td class="text-end">{{ $pa->nb }}</td></tr>
                            @endforeach
                            @if($stats['par_agence']->isEmpty())
                            <tr><td colspan="2" class="text-muted text-center">Aucune vente</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <h6>Classement des commerciaux</h6>
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light"><tr><th>Rang</th><th>Commercial</th><th class="text-end">Ventes</th></tr></thead>
                    <tbody>
                        @foreach($classement as $c)
                        <tr>
                            <td>@if($c['rang']==1)<span class="badge bg-warning text-dark">Top 1</span>@elseif($c['rang']==2)<span class="badge bg-secondary">Top 2</span>@else{{ $c['rang'] }}@endif</td>
                            <td>{{ $c['user_name'] }}</td>
                            <td class="text-end">{{ $c['total_ventes'] }}</td>
                        </tr>
                        @endforeach
                        @if($classement->isEmpty())
                        <tr><td colspan="3" class="text-muted text-center">Aucune vente sur la période</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($primes->isNotEmpty())
    <div class="card shadow-sm">
        <div class="card-header"><strong>Primes versées</strong></div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th>Période</th><th>Commercial</th><th>Rang</th><th class="text-end">Montant</th></tr></thead>
                <tbody>
                    @foreach($primes as $p)
                    <tr>
                        <td>{{ $p->periode }}</td>
                        <td>{{ $p->user?->prenom ? trim($p->user->prenom . ' ' . $p->user->name) : $p->user?->name }}</td>
                        <td>{{ $p->rang }}</td>
                        <td class="text-end">{{ number_format($p->montant) }} F</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
