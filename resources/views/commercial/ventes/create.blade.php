@extends('layouts.app')

@section('title', 'Nouvelle vente')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Enregistrer une vente</h5>
            </div>
            <div class="card-body">
                @if($typesCartes->isEmpty())
                <div class="alert alert-warning">Aucun type de carte actif. Contactez l'administrateur.</div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Retour au Dashboard</a>
                @else
                <form id="form-vente">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Type de carte *</label>
                        <div class="d-flex flex-column gap-2">
                            @foreach($typesCartes as $tc)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_carte_id" id="tc{{ $tc->id }}" value="{{ $tc->id }}" {{ $loop->first ? 'required' : '' }}>
                                <label class="form-check-label" for="tc{{ $tc->id }}">{{ $tc->code }} — {{ number_format($tc->prix) }} F</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prénom *</label>
                            <input type="text" class="form-control form-control-lg" name="prenom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom *</label>
                            <input type="text" class="form-control form-control-lg" name="nom" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" class="form-control form-control-lg" name="telephone">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ville</label>
                            <input type="text" class="form-control" name="ville">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quartier</label>
                            <input type="text" class="form-control" name="quartier">
                        </div>
                    </div>
                    <div class="mt-4 d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" id="btn-submit">
                            Valider la vente
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Retour au Dashboard</a>
                    </div>
                </form>
                @endif
                <div id="alert-zone"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    const csrf = $('meta[name="csrf-token"]').attr('content');

    $('#form-vente').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('#btn-submit');
        $btn.prop('disabled', true).text('En cours...');
        $('#alert-zone').empty();

        const typeId = $('[name="type_carte_id"]:checked').val();
        const data = {
            _token: csrf,
            prenom: $('[name="prenom"]').val(),
            nom: $('[name="nom"]').val(),
            telephone: $('[name="telephone"]').val(),
            ville: $('[name="ville"]').val(),
            quartier: $('[name="quartier"]').val(),
            type_carte_id: typeId
        };

        $.ajax({
            url: '{{ url("/api/ventes") }}',
            method: 'POST',
            data: data,
            success: function(res) {
                window.location.href = @json(route('dashboard'));
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                let msg = res?.message || 'Erreur lors de l\'enregistrement.';
                if (res?.errors) {
                    msg += '<ul class="mb-0 mt-1">';
                    $.each(res.errors, function(_, arr) { msg += '<li>' + arr[0] + '</li>'; });
                    msg += '</ul>';
                }
                $('#alert-zone').html('<div class="alert alert-danger mt-3">' + msg + '</div>');
            },
            complete: function() {
                $btn.prop('disabled', false).text('Valider la vente');
            }
        });
    });
});
</script>
@endpush
