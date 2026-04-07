@extends('layouts.app')

@section('title', 'Reporting téléphonique')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <h4 class="mb-0">Mes fiches de reporting</h4>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('commercial.telephonique.export-excel') }}" class="btn btn-success" target="_blank">Exporter Excel (.xlsx)</a>
        <a href="{{ route('commercial.telephonique.create') }}" class="btn btn-primary">Nouvelle saisie / jour</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th class="text-end">Appels émis</th>
                    <th class="text-end">Joignables</th>
                    <th class="text-end">Non joignables</th>
                    <th class="text-end">Taux joign.</th>
                    <th class="text-end">Intéressés</th>
                    <th class="text-end text-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rapports as $r)
                <tr>
                    <td><a href="{{ route('commercial.telephonique.create', ['date' => $r->date_rapport->format('Y-m-d')]) }}">{{ $r->date_rapport->format('d/m/Y') }}</a></td>
                    <td class="text-end">{{ $r->appels_emis }}</td>
                    <td class="text-end">{{ $r->appels_joignables }}</td>
                    <td class="text-end">{{ $r->appels_non_joignables }}</td>
                    <td class="text-end">{{ $r->taux_joignabilite !== null ? number_format((float) $r->taux_joignabilite, 2, ',', ' ').' %' : '—' }}</td>
                    <td class="text-end">{{ $r->clients_interesses_nombre }}</td>
                    <td class="text-end">
                        <div class="d-inline-flex flex-column flex-sm-row gap-1 justify-content-end">
                            @if($r->peutEtreModifieOuSupprime())
                                <a href="{{ route('commercial.telephonique.create', ['date' => $r->date_rapport->format('Y-m-d')]) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                <form method="POST" action="{{ route('commercial.telephonique.destroy', $r) }}" class="d-inline" onsubmit="return confirm('Supprimer définitivement cette fiche ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            @else
                                <span class="btn btn-sm btn-outline-secondary disabled" tabindex="-1" title="Modification impossible après 48 h suivant l’enregistrement.">Modifier</span>
                                <span class="btn btn-sm btn-outline-secondary disabled" tabindex="-1" title="Suppression impossible après 48 h suivant l’enregistrement.">Supprimer</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-muted text-center py-4">Aucune fiche enregistrée.</td></tr>
                @endforelse
            </tbody>
            @if(($totauxListe['nb_fiches'] ?? 0) > 0)
            <tfoot class="table-secondary">
                <tr class="fw-bold small">
                    <td class="text-end">Total ({{ number_format($totauxListe['nb_fiches']) }} fiche(s))</td>
                    <td class="text-end">{{ number_format($totauxListe['appels_emis']) }}</td>
                    <td class="text-end">{{ number_format($totauxListe['appels_joignables']) }}</td>
                    <td class="text-end">{{ number_format($totauxListe['appels_non_joignables']) }}</td>
                    <td></td>
                    <td class="text-end">{{ number_format($totauxListe['clients_interesses']) }}</td>
                    <td></td>
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
