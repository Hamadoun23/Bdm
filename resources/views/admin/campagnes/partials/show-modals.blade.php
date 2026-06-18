@php $peutPiloter = in_array($campagne->statut_effectif, ['programmee', 'en_cours'], true); @endphp
@if($peutPiloter)
<div class="modal fade" id="modalArreter" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.campagnes.arreter', $campagne) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Arrêter « {{ $campagne->nom }} »</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Interrompt la campagne avant sa date de fin.</p>
                    <label class="form-label">Justification * (min. 10 car.)</label>
                    <textarea name="description" class="form-control" rows="3" required minlength="10"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Arrêter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalAnnuler" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.campagnes.annuler', $campagne) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Annuler « {{ $campagne->nom }} »</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Annulation définitive de la campagne.</p>
                    <label class="form-label">Justification * (min. 10 car.)</label>
                    <textarea name="description" class="form-control" rows="3" required minlength="10"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-danger">Annuler la campagne</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalReprogrammer" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.campagnes.reprogrammer', $campagne) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reprogrammer « {{ $campagne->nom }} »</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Modification des dates avec trace dans l’historique (justification obligatoire).</p>
                    <div class="row mb-2">
                        <div class="col-6">
                            <label class="form-label">Date début *</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ $campagne->date_debut->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Date fin *</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ $campagne->date_fin->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <label class="form-label">Justification * (min. 10 car.)</label>
                    <textarea name="description" class="form-control" rows="3" required minlength="10"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Reprogrammer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
