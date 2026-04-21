@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="mb-0">Rapports</h4>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
</div>

<div class="alert alert-light border mb-3 small">
    <p class="mb-2"><strong>Cumul multi-campagnes :</strong> cochez une ou plusieurs lignes dans le tableau ci-dessous, puis <span class="badge bg-dark">Voir le cumul</span> pour une vue agrégée (ventes, commerciaux, agences, types de carte, clients). Même accès depuis <a href="{{ route('performances.index') }}">Performances</a> (lien « Cumul »).</p>
    <p class="mb-2"><strong>Usage direction / pilotage :</strong> le bouton <span class="badge bg-success">Export complet</span> sur chaque campagne génère un classeur Excel (ventes, clients, commerciaux, agences, types de carte, semaines, mois, fiches téléphonique + synthèse appels). Utilisez <span class="badge bg-success">Synthèse</span> pour les graphiques et des exports filtrés, et <span class="badge bg-primary">Liste ventes</span> pour le détail des ventes avec filtres.</p>
    <p class="mb-2 text-muted">Les ventes sont enregistrées avec le <code>campagne_id</code> de la campagne active au moment de la saisie.</p>
    @if($user->isAdmin())
    <p class="mb-0"><a href="{{ route('admin.telephonique-rapports.index') }}" class="alert-link">Reporting téléphonique (toutes campagnes)</a> — vue globale. Depuis chaque campagne : bouton <strong>Reporting téléphonique</strong> ou entrée <strong>Synthèse</strong> pour le périmètre campagne.</p>
    @endif
</div>

<div class="card shadow-sm mb-4" id="cumul-campagnes">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <strong>Campagnes</strong>
        <form method="get" action="{{ route('rapports.cumul') }}" class="d-flex flex-wrap align-items-center gap-2" id="form-cumul-campagnes">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-cumul-tout">Tout sélectionner</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-cumul-rien">Tout désélectionner</button>
            <button type="submit" class="btn btn-sm btn-dark" id="btn-cumul-voir" disabled>Voir le cumul</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 2.5rem;" title="Sélection pour cumul">
                        <span class="visually-hidden">Cumul</span>
                        <input type="checkbox" class="form-check-input" id="cumul-check-master" aria-label="Tout sélectionner pour cumul">
                    </th>
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
                    <td class="text-center">
                        <input type="checkbox" class="form-check-input cumul-cb" name="campagne_ids[]" value="{{ $c->id }}" form="form-cumul-campagnes" aria-label="Inclure {{ $c->nom }} dans le cumul">
                    </td>
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
                <tr><td colspan="6" class="text-center py-4">Aucune campagne à afficher.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
(function () {
    var form = document.getElementById('form-cumul-campagnes');
    var boxes = document.querySelectorAll('.cumul-cb');
    var btnVoir = document.getElementById('btn-cumul-voir');
    var master = document.getElementById('cumul-check-master');
    function sync() {
        var n = 0;
        boxes.forEach(function (b) { if (b.checked) n++; });
        if (btnVoir) btnVoir.disabled = n === 0;
        if (master && boxes.length) {
            master.indeterminate = n > 0 && n < boxes.length;
            master.checked = n === boxes.length && boxes.length > 0;
        }
    }
    boxes.forEach(function (b) { b.addEventListener('change', sync); });
    if (master) {
        master.addEventListener('change', function () {
            var on = master.checked;
            boxes.forEach(function (b) { b.checked = on; });
            sync();
        });
    }
    document.getElementById('btn-cumul-tout')?.addEventListener('click', function () {
        boxes.forEach(function (b) { b.checked = true; });
        sync();
    });
    document.getElementById('btn-cumul-rien')?.addEventListener('click', function () {
        boxes.forEach(function (b) { b.checked = false; });
        sync();
    });
    sync();
})();
</script>
@endpush
@endsection
