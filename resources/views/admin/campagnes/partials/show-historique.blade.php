<div class="tab-pane fade @if(($activeTab ?? '') === 'historique') show active @endif" id="tab-historique" role="tabpanel">
    <div class="card shadow-sm">
        <div class="card-header bg-light"><strong>Historique des actions</strong></div>
        <div class="card-body">
            <p class="text-muted small">Arrêts, annulations et reprogrammations documentés avec justification.</p>
            @if($campagne->actions->isEmpty())
            <p class="text-muted mb-0">Aucune action enregistrée.</p>
            @else
            <div class="list-group list-group-flush">
                @foreach($campagne->actions as $action)
                <div class="list-group-item px-0">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge
                            @if($action->action === 'arreter') bg-warning text-dark
                            @elseif($action->action === 'annuler') bg-danger
                            @else bg-secondary @endif
                        ">
                            @if($action->action === 'arreter') Arrêt
                            @elseif($action->action === 'annuler') Annulation
                            @else Reprogrammation @endif
                        </span>
                        <small class="text-muted">{{ $action->created_at->format('d/m/Y H:i') }}</small>
                        @if($action->user)
                        <small class="text-muted">par {{ $action->user->name }}</small>
                        @endif
                    </div>
                    <p class="mb-2 ps-2 border-start border-2 border-light">{{ $action->description }}</p>
                    @if($action->action === 'reprogrammer' && $action->donnees_avant && $action->donnees_apres)
                    @php $av = $action->donnees_avant; $ap = $action->donnees_apres; @endphp
                    <div class="row small text-muted">
                        <div class="col-md-6">Avant : {{ isset($av['date_debut']) ? \Carbon\Carbon::parse($av['date_debut'])->format('d/m/Y') : '—' }} → {{ isset($av['date_fin']) ? \Carbon\Carbon::parse($av['date_fin'])->format('d/m/Y') : '—' }}</div>
                        <div class="col-md-6">Après : {{ isset($ap['date_debut']) ? \Carbon\Carbon::parse($ap['date_debut'])->format('d/m/Y') : '—' }} → {{ isset($ap['date_fin']) ? \Carbon\Carbon::parse($ap['date_fin'])->format('d/m/Y') : '—' }}</div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
