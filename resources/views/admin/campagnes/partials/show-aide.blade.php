<div class="tab-pane fade @if(($activeTab ?? '') === 'aide') show active @endif" id="tab-aide" role="tabpanel">
    @if($campagne->aide_hebdo_active)
    <div class="card shadow-sm">
        <div class="card-header bg-light"><strong>Versements aide hebdo — accusés de réception</strong></div>
        <div class="card-body">
            <p class="small text-muted mb-3">
                Montants par défaut : carburant {{ number_format($campagne->aide_hebdo_carburant) }} F + crédit {{ number_format($campagne->aide_hebdo_credit_tel) }} F
                = {{ number_format($campagne->aide_hebdo_montant) }} F / semaine.
            </p>
            @unless($isDirectionDetail ?? false)
            <form method="POST" action="{{ route('admin.campagnes.versements.store', $campagne) }}" class="row g-2 align-items-end mb-4">
                @csrf
                <div class="col-md-3">
                    <label class="form-label small mb-0">Commercial</label>
                    <select name="user_id" class="form-select form-select-sm" required>
                        <option value="">—</option>
                        @foreach($campagne->signatairesContrat as $u)
                            <option value="{{ $u->id }}">{{ $u->prenom ? trim($u->prenom.' '.$u->name) : $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-0">Semaine (lundi)</label>
                    <input type="date" name="semaine_debut" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-0">Carburant (F)</label>
                    <input type="number" name="montant_carburant" class="form-control form-control-sm" value="{{ $campagne->aide_hebdo_carburant }}" min="0" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-0">Crédit tel. (F)</label>
                    <input type="number" name="montant_credit_tel" class="form-control form-control-sm" value="{{ $campagne->aide_hebdo_credit_tel }}" min="0" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100">Enregistrer</button>
                </div>
            </form>
            @else
            <p class="small text-muted mb-3">Lecture seule.</p>
            @endunless
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Semaine</th><th>Commercial</th><th>Carburant</th><th>Crédit</th><th>Accusé</th>@unless($isDirectionDetail ?? false)<th></th>@endunless</tr></thead>
                    <tbody>
                        @forelse($campagne->aideVersements->sortByDesc('semaine_debut') as $v)
                        <tr>
                            <td>{{ $v->semaine_debut->format('d/m/Y') }}</td>
                            <td>{{ $v->user?->prenom ? trim($v->user->prenom.' '.$v->user->name) : $v->user?->name }}</td>
                            <td>{{ number_format($v->montant_carburant) }}</td>
                            <td>{{ number_format($v->montant_credit_tel) }}</td>
                            <td>@if($v->accuse_at)<span class="badge bg-success">{{ $v->accuse_at->format('d/m/Y H:i') }}</span>@else<span class="badge bg-warning text-dark">En attente</span>@endif</td>
                            @unless($isDirectionDetail ?? false)
                            <td>
                                @if(!$v->accuse_at)
                                <form method="POST" action="{{ route('admin.campagnes.versements.destroy', [$campagne, $v]) }}" class="d-inline" onsubmit="return confirm('Supprimer ce versement ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Suppr.</button>
                                </form>
                                @endif
                            </td>
                            @endunless
                        </tr>
                        @empty
                        <tr><td colspan="{{ ($isDirectionDetail ?? false) ? 5 : 6 }}" class="text-muted">Aucun versement.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info mb-0">
        L’aide hebdomadaire n’est pas activée pour cette campagne.
        @unless($isDirectionDetail ?? false)
        <a href="{{ route('admin.campagnes.edit', $campagne) }}">Activer dans les paramètres</a>.
        @endunless
    </div>
    @endif
</div>
