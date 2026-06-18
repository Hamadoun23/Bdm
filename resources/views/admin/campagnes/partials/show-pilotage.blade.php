@php
    $statut = $campagne->statut_effectif;
    $peutPiloter = in_array($statut, ['programmee', 'en_cours'], true);
@endphp
<div class="tab-pane fade @if(($activeTab ?? 'pilotage') === 'pilotage') show active @endif" id="tab-pilotage" role="tabpanel">
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <strong>Informations générales</strong>
                    <span class="badge fs-6
                        @if($statut === 'en_cours') bg-success
                        @elseif($statut === 'programmee') bg-info
                        @elseif($statut === 'arretee') bg-warning text-dark
                        @elseif($statut === 'annulee') bg-danger
                        @else bg-secondary @endif
                    ">
                        @if($statut === 'en_cours') En cours
                        @elseif($statut === 'programmee') Programmée
                        @elseif($statut === 'arretee') Arrêtée
                        @elseif($statut === 'annulee') Annulée
                        @else Terminée @endif
                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th class="text-muted" style="width:160px">Nom</th><td>{{ $campagne->nom }}</td></tr>
                        <tr><th class="text-muted">Période</th><td>{{ $campagne->date_debut->format('d/m/Y') }} → {{ $campagne->date_fin->format('d/m/Y') }}</td></tr>
                        <tr><th class="text-muted">Agences</th><td>{{ $campagne->toutes_agences ? 'Toutes les agences' : $campagne->agences->pluck('nom')->join(', ') }}</td></tr>
                        <tr><th class="text-muted">Prime 1<sup>er</sup></th><td>{{ number_format($campagne->prime_meilleur_vendeur) }} FCFA</td></tr>
                        <tr><th class="text-muted">Aide hebdo.</th><td>
                            @if($campagne->aide_hebdo_active)
                                {{ number_format($campagne->aide_hebdo_montant) }} F / semaine
                            @else
                                Non activée
                            @endif
                        </td></tr>
                        <tr><th class="text-muted">Remise</th><td>
                            @if($campagne->remise_pourcentage)
                                {{ $campagne->remise_pourcentage }} %
                                @if($campagne->remise_tous_types_cartes)
                                    — tous types
                                @else
                                    — {{ $campagne->typesCartesRemise->pluck('code')->join(', ') ?: 'types non définis' }}
                                @endif
                            @else
                                Aucune
                            @endif
                        </td></tr>
                        <tr><th class="text-muted">Créée le</th><td>{{ $campagne->created_at->format('d/m/Y H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        @unless($isDirectionDetail ?? false)
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-primary border-opacity-25">
                <div class="card-header bg-light"><strong>Actions rapides</strong></div>
                <div class="card-body d-flex flex-column gap-2">
                    <a href="{{ route('admin.campagnes.edit', $campagne) }}" class="btn btn-primary btn-sm">Modifier tous les paramètres</a>
                    <form method="POST" action="{{ route('admin.campagnes.sync-commerciaux', $campagne) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">Resynchroniser les comptes commerciaux</button>
                    </form>
                    @if($peutPiloter)
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalReprogrammer">Reprogrammer (avec justification)</button>
                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalArreter">Arrêter la campagne</button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalAnnuler">Annuler la campagne</button>
                    @endif
                    <hr class="my-1">
                    <form action="{{ route('admin.campagnes.destroy', $campagne) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cette campagne ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">Supprimer la campagne</button>
                    </form>
                </div>
            </div>
        </div>
        @endunless
    </div>

    @unless($isDirectionDetail ?? false)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light"><strong>Modifier les dates</strong></div>
        <div class="card-body">
            <p class="small text-muted mb-3">Met à jour la période, recalcule le statut (programmée / en cours / terminée) et réactive ou désactive automatiquement les comptes des commerciaux engagés.</p>
            <form method="POST" action="{{ route('admin.campagnes.dates.update', $campagne) }}" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-3">
                    <label class="form-label small mb-0">Date début</label>
                    <input type="date" name="date_debut" class="form-control form-control-sm" value="{{ old('date_debut', $campagne->date_debut->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-0">Date fin</label>
                    <input type="date" name="date_fin" class="form-control form-control-sm" value="{{ old('date_fin', $campagne->date_fin->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-sm btn-success">Enregistrer les dates</button>
                </div>
            </form>
        </div>
    </div>
    @endunless

    <div class="alert alert-light border small mb-0">
        <strong>Rapports :</strong>
        <a href="{{ route('rapports.campagnes.synthese', $campagne) }}">Synthèse</a> ·
        <a href="{{ route('rapports.campagnes.ventes', $campagne) }}">Ventes</a> ·
        <a href="{{ route('rapports.campagnes.clients', $campagne) }}">Clients</a> ·
        <a href="{{ route('rapports.campagnes.reporting-telephonique', $campagne) }}">Téléphonique</a>
    </div>
</div>
