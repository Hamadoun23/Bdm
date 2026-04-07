@extends('layouts.app')

@section('title', 'Fiche reporting téléphonique')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="mb-0">Détail fiche — {{ $telephoniqueRapport->user?->prenom ? trim($telephoniqueRapport->user->prenom.' '.$telephoniqueRapport->user->name) : $telephoniqueRapport->user?->name }}</h4>
    <a href="{{ $retourListeCampagne ?? route('admin.telephonique-rapports.index') }}" class="btn btn-outline-secondary btn-sm">← Liste</a>
</div>

@if(!$telephoniqueRapport->njAnalyseCoherente())
<div class="alert alert-warning small">Incohérence : la somme des motifs non-joignables ({{ $telephoniqueRapport->sommeNjMotifs() }}) dépasse le non joignable enregistré ({{ $telephoniqueRapport->appels_non_joignables }}).</div>
@endif

<div class="card shadow-sm mb-3">
    <div class="card-header"><strong>1. Identification</strong></div>
    <div class="card-body row small g-2">
        <div class="col-md-4"><span class="text-muted">Date rapport</span><br><strong>{{ $telephoniqueRapport->date_rapport->format('d/m/Y') }}</strong></div>
        <div class="col-md-4"><span class="text-muted">Campagne (lien)</span><br><strong>{{ $telephoniqueRapport->campagne?->nom ?? '— (non rattachée)' }}</strong></div>
        <div class="col-md-4"><span class="text-muted">Agence</span><br>{{ $telephoniqueRapport->user?->agence?->nom ?? '—' }}</div>
        <div class="col-md-4"><span class="text-muted">Créé le</span><br>{{ $telephoniqueRapport->created_at?->format('d/m/Y H:i') }}</div>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-header"><strong>2. Activité journalière</strong></div>
    <div class="card-body row small g-2">
        <div class="col-6 col-md-3">Appels émis<br><strong>{{ $telephoniqueRapport->appels_emis }}</strong></div>
        <div class="col-6 col-md-3">Joignables<br><strong>{{ $telephoniqueRapport->appels_joignables }}</strong></div>
        <div class="col-6 col-md-3">Non joignables<br><strong>{{ $telephoniqueRapport->appels_non_joignables }}</strong> <span class="text-muted">(calculé : émis − joignables = {{ max(0, $telephoniqueRapport->appels_emis - $telephoniqueRapport->appels_joignables) }})</span></div>
        <div class="col-6 col-md-3">Taux joignabilité %<br>
            <strong>@if($telephoniqueRapport->taux_joignabilite !== null){{ number_format((float) $telephoniqueRapport->taux_joignabilite, 2, ',', ' ') }} %@elseif($telephoniqueRapport->appels_emis > 0){{ number_format($telephoniqueRapport->appels_joignables / $telephoniqueRapport->appels_emis * 100, 2, ',', ' ') }} % (recalculé)@else—@endif</strong>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-header"><strong>3. Résultats des appels</strong></div>
    <div class="card-body row small g-2">
        <div class="col-md-6">Clients intéressés (nombre)<br><strong>{{ $telephoniqueRapport->clients_interesses_nombre }}</strong></div>
        <div class="col-md-6">Clients intéressés %<br>
            <strong>
                @if($telephoniqueRapport->clients_interesses_pct !== null)
                    {{ number_format((float) $telephoniqueRapport->clients_interesses_pct, 2, ',', ' ') }} % (base)
                @elseif($telephoniqueRapport->pctInteressesCalcule() !== null)
                    {{ number_format($telephoniqueRapport->pctInteressesCalcule(), 2, ',', ' ') }} % (sur appels émis)
                @else
                    —
                @endif
            </strong>
        </div>
        <div class="col-md-6">Clients déjà servis (nombre)<br><strong>{{ $telephoniqueRapport->clients_deja_servis_nombre }}</strong></div>
        <div class="col-md-6">Clients déjà servis %<br>
            <strong>
                @if($telephoniqueRapport->clients_deja_servis_pct !== null)
                    {{ number_format((float) $telephoniqueRapport->clients_deja_servis_pct, 2, ',', ' ') }} % (base)
                @elseif($telephoniqueRapport->pctDejaServisCalcule() !== null)
                    {{ number_format($telephoniqueRapport->pctDejaServisCalcule(), 2, ',', ' ') }} % (sur appels émis)
                @else
                    —
                @endif
            </strong>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-header"><strong>4. Types de carte proposées (détail)</strong></div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead><tr><th>Type</th><th class="text-end">Quantité</th></tr></thead>
            <tbody>
                @php $arr = $telephoniqueRapport->cartes_proposees; @endphp
                @if(! is_array($arr) || $arr === [])
                    <tr><td colspan="2" class="text-muted small px-3 py-2">Aucune donnée</td></tr>
                @else
                    @foreach($arr as $id => $n)
                        @if((int) $n > 0)
                        <tr>
                            <td class="px-3">{{ $typesCartes->get((int) $id)?->code ?? '#'.$id }}</td>
                            <td class="text-end px-3">{{ $n }}</td>
                        </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
        <p class="small text-muted mb-0 px-3 py-2 border-top">Résumé : {{ $telephoniqueRapport->resumeCartesProposees() }}</p>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-header"><strong>5. Non joignables — analyse</strong></div>
    <div class="card-body row small g-2">
        <div class="col-6 col-md-3">Répondeur<br><strong>{{ $telephoniqueRapport->nj_repondeur }}</strong></div>
        <div class="col-6 col-md-3">N° erroné<br><strong>{{ $telephoniqueRapport->nj_numero_errone }}</strong></div>
        <div class="col-6 col-md-3">Hors réseau<br><strong>{{ $telephoniqueRapport->nj_hors_reseau }}</strong></div>
        <div class="col-6 col-md-3">Autres (nb)<br><strong>{{ $telephoniqueRapport->nj_autres_nombre }}</strong></div>
        <div class="col-12">Autres — précision<br><strong>{{ $telephoniqueRapport->nj_autres_precision ?? '—' }}</strong></div>
        <div class="col-12 text-muted">Somme motifs : <strong>{{ $telephoniqueRapport->sommeNjMotifs() }}</strong> / plafond non-joignables <strong>{{ $telephoniqueRapport->appels_non_joignables }}</strong></div>
    </div>
</div>
@endsection
