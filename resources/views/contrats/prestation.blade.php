{{-- Contrat de prestation — variables : $campagne, $commercial, $lundi_effectif, $date_signature_affichee --}}
@php
    $nomPresta = $commercial->prenom ? trim($commercial->prenom.' '.$commercial->name) : $commercial->name;
    $contactPresta = $commercial->telephone ?: '—';
    $adresse = $commercial->adresse_contrat ?: '………………………';
    $pieceId = $commercial->piece_identite_ref ?: '………………………';
    $articles = $campagne->relationLoaded('contratArticles')
        ? $campagne->contratArticles
        : $campagne->contratArticles()->orderBy('sort_order')->get();
@endphp
<div class="contrat-prestation-doc small lh-lg">
    <p class="text-center fw-bold text-uppercase mb-4">CONTRAT DE PRESTATION DE SERVICES COMMERCIAUX</p>
    <p><strong>Entre les soussignés :</strong></p>
    <p>Le Groupe GDA,<br>
        Société spécialisée en prestations commerciales et marketing opérationnel,<br>
        Représentée par {{ $campagne->contrat_representant_nom }}, dûment habilité(e) à l’effet des présentes,<br>
        Ci-après dénommée « <strong>GDA</strong> »,</p>
    <p><strong>Et :</strong><br>
        <strong>{{ $nomPresta }}</strong><br>
        Demeurant à : {{ $adresse }}<br>
        Contact : {{ $contactPresta }}<br>
        Pièce d’identité : {{ $pieceId }}<br>
        Ci-après dénommé(e) « <strong>la Prestataire</strong> »,</p>
    <p class="fw-bold mt-4">IL A ÉTÉ CONVENU ET ARRÊTÉ CE QUI SUIT :</p>

    <p class="small text-muted border-start border-3 ps-2 mb-3">
        Période indicative : du {{ $lundi_effectif->format('d/m/Y') }} au {{ $campagne->date_fin->format('d/m/Y') }} — campagne « {{ $campagne->nom }} ».
    </p>

    @forelse($articles as $article)
        <div class="mb-3">
            <p class="mb-1"><strong>{{ $article->titre }}</strong></p>
            <div class="mb-0">{!! nl2br(e($article->contenu)) !!}</div>
        </div>
    @empty
        <p class="text-muted fst-italic">Aucun article de contrat n’a encore été défini par l’administration pour cette campagne.</p>
    @endforelse

    @include('contrats.prestation_emoluments_annexes', ['campagne' => $campagne, 'nomPresta' => $nomPresta, 'date_signature_affichee' => $date_signature_affichee])
</div>
