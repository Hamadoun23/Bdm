<div class="tab-pane fade @if(($activeTab ?? '') === 'contrat') show active @endif" id="tab-contrat" role="tabpanel">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center gap-2">
            <strong>Contrat de prestation</strong>
            @unless($isDirectionDetail ?? false)
            <form method="POST" action="{{ route('admin.campagnes.republier-contrat', $campagne) }}" class="d-inline" onsubmit="return confirm('Republier le contrat ? Toutes les réponses repasseront en attente avec un nouveau délai de 5 jours.');">
                @csrf
                <button type="submit" class="btn btn-sm btn-warning">Republier le contrat</button>
            </form>
            @endunless
        </div>
        <div class="card-body">
            <div class="row small mb-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Émolument :</strong> {{ number_format($campagne->contrat_emolument_forfait) }} F</p>
                    <p class="mb-1"><strong>Communication :</strong> {{ number_format($campagne->contrat_forfait_communication) }} F</p>
                    <p class="mb-1"><strong>Déplacement :</strong> {{ number_format($campagne->contrat_forfait_deplacement) }} F</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Représentant :</strong> {{ $campagne->contrat_representant_nom }}</p>
                    <p class="mb-1"><strong>Lieu :</strong> {{ $campagne->contrat_lieu_signature }}</p>
                    @if($campagne->contrat_publie_at)
                        <p class="mb-1 text-muted">Publié le {{ $campagne->contrat_publie_at->format('d/m/Y H:i') }} — délai 5 jours</p>
                    @endif
                </div>
            </div>
            @if($campagne->contrat_clause_libre)
                <p class="small border-start border-2 ps-2 text-muted">{{ $campagne->contrat_clause_libre }}</p>
            @endif

            <h6 class="mt-3">Réponses des commerciaux</h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Commercial</th><th>Statut</th><th>Répondu le</th>@unless($isDirectionDetail ?? false)<th></th>@endunless</tr></thead>
                    <tbody>
                        @forelse($campagne->contratReponses as $rep)
                        @php $verrou = $campagne->contratDelaiExpire() && $rep->statut === 'en_attente'; @endphp
                        <tr>
                            <td>{{ $rep->user?->prenom ? trim($rep->user->prenom.' '.$rep->user->name) : $rep->user?->name }}</td>
                            <td>
                                @if($rep->statut === 'accepte')<span class="badge bg-success">Accepté</span>
                                @elseif($rep->statut === 'rejete')<span class="badge bg-danger">Refusé</span>
                                @else<span class="badge bg-secondary">En attente</span>@endif
                                @if($verrou)<span class="text-muted small"> (délai expiré)</span>@endif
                            </td>
                            <td>{{ $rep->repondu_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            @unless($isDirectionDetail ?? false)
                            <td>
                                @if($rep->statut !== 'en_attente')
                                <form method="POST" action="{{ route('admin.campagnes.contrat-reponses.reset', [$campagne, $rep]) }}" class="d-inline" onsubmit="return confirm('Réinitialiser cette réponse ?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">Réinitialiser</button>
                                </form>
                                @endif
                            </td>
                            @endunless
                        </tr>
                        @empty
                        <tr><td colspan="{{ ($isDirectionDetail ?? false) ? 3 : 4 }}" class="text-muted">Aucune réponse — enregistrez des signataires dans l’onglet Commerciaux.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(session('success_article'))
        <div class="alert alert-success py-2 small">{{ session('success_article') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-light"><strong>Articles du contrat ({{ $campagne->contratArticles->count() }})</strong></div>
        <div class="card-body">
            @if($isDirectionDetail ?? false)
                @if($campagne->contratArticles->isEmpty())
                    <p class="text-muted small mb-0">Aucun article.</p>
                @else
                <div class="accordion accordion-flush" id="directionContratArticles">
                    @foreach($campagne->contratArticles->sortBy('sort_order') as $art)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 small" type="button" data-bs-toggle="collapse" data-bs-target="#dirArticle{{ $art->id }}">{{ $art->titre }}</button>
                        </h2>
                        <div id="dirArticle{{ $art->id }}" class="accordion-collapse collapse" data-bs-parent="#directionContratArticles">
                            <div class="accordion-body small text-body-secondary">{!! nl2br(e($art->contenu)) !!}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            @else
                <p class="small text-muted">Modifiez le texte affiché aux commerciaux sans passer par la base de données.</p>
                @foreach($campagne->contratArticles as $article)
                <div class="border rounded p-3 mb-3 bg-light">
                    <form method="POST" action="{{ route('admin.campagnes.contrat-articles.update', [$campagne, $article]) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-2">
                            <label class="form-label small mb-0">Titre</label>
                            <input type="text" name="titre" class="form-control form-control-sm" value="{{ $article->titre }}" required maxlength="255">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small mb-0">Contenu</label>
                            <textarea name="contenu" class="form-control form-control-sm" rows="4" required maxlength="50000">{{ $article->contenu }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                    </form>
                    <form method="POST" action="{{ route('admin.campagnes.contrat-articles.destroy', [$campagne, $article]) }}" class="d-inline mt-2" onsubmit="return confirm('Supprimer cet article ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
                @endforeach
                <div class="border rounded p-3 border-primary border-2">
                    <h6 class="small text-uppercase text-muted mb-2">Nouvel article</h6>
                    <form method="POST" action="{{ route('admin.campagnes.contrat-articles.store', $campagne) }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small mb-0">Titre</label>
                            <input type="text" name="titre" class="form-control form-control-sm" required maxlength="255">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small mb-0">Contenu</label>
                            <textarea name="contenu" class="form-control form-control-sm" rows="4" required maxlength="50000"></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-success">Ajouter</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
