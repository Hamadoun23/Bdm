@php
    $aideTous = $campagne->contrat_tous_commerciaux;
    $benefIds = old('aide_beneficiaires', $campagne->signatairesContrat->pluck('id')->toArray());
@endphp
<div class="tab-pane fade @if(($activeTab ?? '') === 'commerciaux') show active @endif" id="tab-commerciaux" role="tabpanel">
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body py-3">
                    <h6 class="mb-0">Comptes actifs</h6>
                    <h3 class="mb-0">{{ $nbCommerciauxActifs ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-secondary text-white">
                <div class="card-body py-3">
                    <h6 class="mb-0">Comptes inactifs</h6>
                    <h3 class="mb-0">{{ $nbCommerciauxInactifs ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body py-3">
                    <h6 class="mb-0">Engagés sur la campagne</h6>
                    <h3 class="mb-0">{{ $commerciauxPerimetre->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    @unless($isDirectionDetail ?? false)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light"><strong>Gérer les commerciaux engagés</strong></div>
        <div class="card-body">
            <p class="small text-muted">Liste utilisée pour le contrat, l’accès aux ventes et l’aide hebdomadaire. Après enregistrement, les comptes sont resynchronisés automatiquement.</p>
            <form method="POST" action="{{ route('admin.campagnes.signataires.update', $campagne) }}">
                @csrf
                <input type="hidden" name="aide_hebdo_tous_commerciaux" value="0">
                <div class="form-check mb-3">
                    <input type="checkbox" name="aide_hebdo_tous_commerciaux" value="1" class="form-check-input" id="detail_aide_tous" {{ $aideTous ? 'checked' : '' }} onchange="toggleDetailBeneficiaires(this)">
                    <label class="form-check-label" for="detail_aide_tous">Tous les commerciaux des agences concernées</label>
                </div>
                <div id="detail-wrap-beneficiaires" style="display: {{ $aideTous ? 'none' : 'block' }}">
                    <label class="form-label">Sélection</label>
                    <div class="border rounded p-2 mb-3" style="max-height: 240px; overflow-y: auto;">
                        @foreach($commerciauxCandidats as $c)
                        <div class="form-check">
                            <input type="checkbox" name="aide_beneficiaires[]" value="{{ $c->id }}" class="form-check-input" id="detail_cb{{ $c->id }}" {{ in_array($c->id, (array) $benefIds) ? 'checked' : '' }}>
                            <label class="form-check-label" for="detail_cb{{ $c->id }}">{{ $c->prenom ? trim($c->prenom.' '.$c->name) : $c->name }} — {{ $c->agence->nom ?? '?' }}</label>
                        </div>
                        @endforeach
                    </div>
                    @error('aide_beneficiaires')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Enregistrer les commerciaux</button>
            </form>
        </div>
    </div>
    @endunless

    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <strong>Liste des commerciaux engagés</strong>
            @unless($isDirectionDetail ?? false)
            <form method="POST" action="{{ route('admin.campagnes.sync-commerciaux', $campagne) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">Resynchroniser les comptes</button>
            </form>
            @endunless
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Commercial</th>
                        <th>Agence</th>
                        <th>Téléphone</th>
                        <th>Compte</th>
                        <th>Contrat</th>
                        @unless($isDirectionDetail ?? false)<th></th>@endunless
                    </tr>
                </thead>
                <tbody>
                    @forelse($commerciauxPerimetre as $u)
                    @php $rep = $reponsesParUser->get($u->id); @endphp
                    <tr>
                        <td>{{ $u->prenom ? trim($u->prenom.' '.$u->name) : $u->name }}</td>
                        <td>{{ $u->agence->nom ?? '—' }}</td>
                        <td>{{ $u->telephone ?? '—' }}</td>
                        <td>
                            @if($u->actif)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </td>
                        <td>
                            @if(!$rep)
                                <span class="badge bg-light text-dark border">Non initié</span>
                            @elseif($rep->statut === 'accepte')
                                <span class="badge bg-success">Accepté</span>
                            @elseif($rep->statut === 'rejete')
                                <span class="badge bg-danger">Refusé</span>
                            @else
                                <span class="badge bg-warning text-dark">En attente</span>
                            @endif
                        </td>
                        @unless($isDirectionDetail ?? false)
                        <td>
                            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-secondary">Fiche</a>
                        </td>
                        @endunless
                    </tr>
                    @empty
                    <tr><td colspan="{{ ($isDirectionDetail ?? false) ? 5 : 6 }}" class="text-muted text-center py-3">Aucun commercial engagé — ajoutez-en via le formulaire ci-dessus ou la modification complète.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
