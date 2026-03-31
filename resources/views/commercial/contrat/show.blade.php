@extends('layouts.app')

@section('title', 'Mon contrat')

@section('content')
<h4 class="mb-2">Mon contrat de prestation</h4>
<p class="text-muted small mb-3">Campagne : <strong>{{ $campagne->nom }}</strong> · Période {{ $campagne->date_debut->format('d/m/Y') }} → {{ $campagne->date_fin->format('d/m/Y') }}</p>

@if(!$campagne->contrat_publie_at)
    <div class="alert alert-warning">Le contrat n’a pas encore été publié. Revenez plus tard ou contactez l’administration.</div>
@elseif($echeance)
    <p class="small text-muted mb-3">Date limite pour accepter ou refuser : <strong>{{ $echeance->format('d/m/Y H:i') }}</strong> (5 jours après publication).</p>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif

<div class="mb-3 p-3 rounded border bg-light">
    <p class="small mb-2 fw-semibold">Vos informations pour le contrat (complétez avec l’admin si besoin)</p>
    <ul class="small mb-0">
        <li>Domicile / adresse : {{ $user->adresse_contrat ?: '— (à renseigner avec l’admin)' }}</li>
        <li>Pièce d’identité (réf.) : {{ $user->piece_identite_ref ?: '—' }}</li>
    </ul>
</div>

<div class="card shadow-sm mb-4 @if($verrou5j && $reponse->statut === \App\Models\ContratPrestationReponse::STATUT_EN_ATTENTE) opacity-50 @endif">
    <div class="card-body contrat-scroll">
        @include('contrats.prestation', $donneesContrat)
    </div>
</div>

<div class="mb-4">
    @if($reponse->statut === \App\Models\ContratPrestationReponse::STATUT_ACCEPTE)
        <p class="text-success fw-semibold mb-2">Vous avez accepté ce contrat le {{ $reponse->repondu_at?->format('d/m/Y H:i') }}.</p>
    @elseif($reponse->statut === \App\Models\ContratPrestationReponse::STATUT_REJETE)
        <p class="text-danger fw-semibold mb-2">Vous avez refusé ce contrat le {{ $reponse->repondu_at?->format('d/m/Y H:i') }}.</p>
    @elseif($verrou5j)
        <p class="text-muted fw-semibold mb-2">Délai de 5 jours dépassé — vous ne pouvez plus accepter ou refuser en ligne.</p>
    @endif

    @if($peutRepondre)
        <form method="POST" action="{{ route('commercial.contrat.accepter') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success me-2">J’accepte le contrat</button>
        </form>
        <form method="POST" action="{{ route('commercial.contrat.rejeter') }}" class="d-inline" onsubmit="return confirm('Confirmer le refus du contrat ?');">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Je refuse</button>
        </form>
    @elseif($campagne->contrat_publie_at && $reponse->statut === \App\Models\ContratPrestationReponse::STATUT_EN_ATTENTE && !$verrou5j)
        {{-- en théorie couvert par peutRepondre --}}
    @endif
</div>

@if($campagne->aide_hebdo_active && $versements->isNotEmpty())
    <h5 class="mt-4 mb-3">Mes versements aide (carburant / crédit téléphonique)</h5>
    <p class="small text-muted">Confirmez la réception après chaque versement enregistré par l’administration.</p>
    <div class="table-responsive">
        <table class="table table-sm align-middle">
            <thead class="table-light"><tr><th>Semaine</th><th>Carburant</th><th>Crédit tel.</th><th>Statut</th><th></th></tr></thead>
            <tbody>
                @foreach($versements as $v)
                    <tr class="@if($v->accuse_at) table-success @endif">
                        <td>{{ $v->semaine_debut->format('d/m/Y') }}</td>
                        <td>{{ number_format($v->montant_carburant) }} F</td>
                        <td>{{ number_format($v->montant_credit_tel) }} F</td>
                        <td>
                            @if($v->accuse_at)
                                <span class="badge bg-success">Reçu le {{ $v->accuse_at->format('d/m/Y H:i') }}</span>
                            @else
                                <span class="badge bg-warning text-dark">En attente de confirmation</span>
                            @endif
                        </td>
                        <td>
                            @if(!$v->accuse_at)
                                <form method="POST" action="{{ route('commercial.aides.accuser', $v) }}" class="d-flex flex-column gap-1">
                                    @csrf
                                    <input type="text" name="accuse_commentaire" class="form-control form-control-sm" placeholder="Commentaire (facultatif)">
                                    <button type="submit" class="btn btn-sm btn-primary">J’atteste avoir reçu</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
