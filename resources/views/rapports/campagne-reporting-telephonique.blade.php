@extends('layouts.app')

@section('title', 'Reporting téléphonique — '.$campagne->nom)

@section('content')
@php
    $qShowBase = array_filter([
        'date_debut' => request('date_debut', $dateDebut->format('Y-m-d')),
        'date_fin' => request('date_fin', $dateFin->format('Y-m-d')),
        'user_id' => request('user_id'),
        'agence_id' => request('agence_id'),
    ], fn ($v) => $v !== null && $v !== '');
@endphp
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <h4 class="mb-0">Reporting téléphonique — {{ $campagne->nom }}</h4>
        <p class="small text-muted mb-0">Période affichée : <strong>{{ $dateDebut->format('d/m/Y') }}</strong> → <strong>{{ $dateFin->format('d/m/Y') }}</strong> (limitée aux dates de campagne).</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('rapports.campagnes.synthese', $campagne) }}" class="btn btn-outline-secondary btn-sm">Synthèse campagne</a>
        <a href="{{ route('rapports.index') }}" class="btn btn-outline-secondary btn-sm">← Rapports</a>
    </div>
</div>

<div class="row g-3 mb-3 small">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100 border-secondary"><div class="card-body py-2">
            <div class="text-muted">Fiches</div><strong>{{ number_format($agregats['nb_fiches']) }}</strong>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100 border-secondary"><div class="card-body py-2">
            <div class="text-muted">Appels émis</div><strong>{{ number_format($agregats['appels_emis']) }}</strong>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100 border-secondary"><div class="card-body py-2">
            <div class="text-muted">Joignables</div><strong>{{ number_format($agregats['appels_joignables']) }}</strong>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100 border-secondary"><div class="card-body py-2">
            <div class="text-muted">Non joignables</div><strong>{{ number_format($agregats['appels_non_joignables']) }}</strong>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100 border-secondary"><div class="card-body py-2">
            <div class="text-muted">Intéressés</div><strong>{{ number_format($agregats['clients_interesses']) }}</strong>
        </div></div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100 border-secondary"><div class="card-body py-2">
            <div class="text-muted">Déjà servis</div><strong>{{ number_format($agregats['clients_deja_servis']) }}</strong>
        </div></div>
    </div>
</div>

<p class="small text-muted">Les fiches sans <code>campagne_id</code> sont incluses si la date tombe dans la fenêtre ci-dessus et que la téléopératrice est rattachée à une agence du périmètre de la campagne (aligné sur la synthèse).</p>

<form method="GET" class="card shadow-sm mb-3">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-2">
            <label class="form-label small mb-0">Du</label>
            <input type="date" name="date_debut" class="form-control form-control-sm" value="{{ request('date_debut', $dateDebut->format('Y-m-d')) }}" min="{{ $campagne->date_debut->format('Y-m-d') }}" max="{{ $campagne->date_fin->format('Y-m-d') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-0">Au</label>
            <input type="date" name="date_fin" class="form-control form-control-sm" value="{{ request('date_fin', $dateFin->format('Y-m-d')) }}" min="{{ $campagne->date_debut->format('Y-m-d') }}" max="{{ $campagne->date_fin->format('Y-m-d') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-0">Téléopératrice</label>
            <select name="user_id" class="form-select form-select-sm">
                <option value="">— Toutes —</option>
                @foreach($telephoniques as $t)
                <option value="{{ $t->id }}" @selected((string) request('user_id') === (string) $t->id)>
                    {{ $t->prenom ? trim($t->prenom.' '.$t->name) : $t->name }} — {{ $t->agence?->nom }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-0">Agence</label>
            <select name="agence_id" class="form-select form-select-sm">
                <option value="">— Toutes —</option>
                @foreach($agencesChoix as $a)
                <option value="{{ $a->id }}" @selected((string) request('agence_id') === (string) $a->id)>{{ $a->nom }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">Filtrer</button>
        </div>
    </div>
</form>

@if(auth()->user()?->isAdmin())
@php
    $qAdminExport = array_filter([
        'campagne_id' => $campagne->id,
        'user_id' => request('user_id'),
        'date_debut' => request('date_debut', $dateDebut->format('Y-m-d')),
        'date_fin' => request('date_fin', $dateFin->format('Y-m-d')),
    ], fn ($v) => $v !== null && $v !== '');
@endphp
<div class="mb-3 d-flex flex-wrap gap-2">
    <a href="{{ route('admin.telephonique-rapports.export', array_merge($qAdminExport, ['format' => 'xlsx'])) }}" class="btn btn-sm btn-success" target="_blank">Exporter Excel (.xlsx)</a>
    <a href="{{ route('admin.telephonique-rapports.export', $qAdminExport) }}" class="btn btn-sm btn-outline-primary" target="_blank">Exporter CSV</a>
</div>
@endif

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-sm table-striped table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Campagne (fiche)</th>
                    <th>Collaborateur</th>
                    <th>Agence</th>
                    <th class="text-end">Émis</th>
                    <th class="text-end">Joign.</th>
                    <th class="text-end">Non j.</th>
                    <th class="text-end">Intéressés</th>
                    <th>Déjà servis</th>
                    <th>Cartes proposées</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($rapports as $r)
                <tr>
                    <td>{{ $r->date_rapport->format('d/m/Y') }}</td>
                    <td class="small">{{ $r->campagne?->nom ?? '—' }}</td>
                    <td>{{ $r->user?->prenom ? trim($r->user->prenom.' '.$r->user->name) : $r->user?->name }}</td>
                    <td class="small">{{ $r->user?->agence?->nom ?? '—' }}</td>
                    <td class="text-end">{{ $r->appels_emis }}</td>
                    <td class="text-end">{{ $r->appels_joignables }}</td>
                    <td class="text-end">{{ $r->appels_non_joignables }}</td>
                    <td class="text-end">{{ $r->clients_interesses_nombre }}</td>
                    <td class="text-end">{{ $r->clients_deja_servis_nombre }}</td>
                    <td class="small">{{ $r->resumeCartesProposees() }}</td>
                    <td class="text-end">
                        <a href="{{ route('rapports.campagnes.reporting-telephonique.show', array_merge(['campagne' => $campagne, 'telephoniqueRapport' => $r], $qShowBase)) }}" class="btn btn-sm btn-outline-primary">Détail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="11" class="text-center text-muted py-4">Aucune fiche sur cette période et ces filtres.</td></tr>
                @endforelse
            </tbody>
            @if(($agregats['nb_fiches'] ?? 0) > 0)
            <tfoot class="table-secondary">
                <tr class="fw-bold small">
                    <td colspan="4" class="text-end">Total ({{ number_format($agregats['nb_fiches']) }} fiche(s), tous les filtres)</td>
                    <td class="text-end">{{ number_format($agregats['appels_emis']) }}</td>
                    <td class="text-end">{{ number_format($agregats['appels_joignables']) }}</td>
                    <td class="text-end">{{ number_format($agregats['appels_non_joignables']) }}</td>
                    <td class="text-end">{{ number_format($agregats['clients_interesses']) }}</td>
                    <td class="text-end">{{ number_format($agregats['clients_deja_servis']) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    @if($rapports->hasPages())
    <div class="card-footer">{{ $rapports->links() }}</div>
    @endif
</div>
@endsection
