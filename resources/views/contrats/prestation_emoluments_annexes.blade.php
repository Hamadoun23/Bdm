{{-- Suite du contrat : montants paramétrés campagne + clause libre + signatures — variables : $campagne, $nomPresta, $date_signature_affichee --}}
<p><strong>Rémunération, forfaits et aides</strong></p>
<p>
    En contrepartie des prestations fournies, la Prestataire percevra de GDA un émolument forfaitaire de <strong>{{ number_format($campagne->contrat_emolument_forfait, 0, ',', ' ') }} FCFA</strong> TTC pour la durée totale de la mission.
</p>
<ul class="list-unstyled ms-3">
    <li>— Forfait communication : <strong>{{ number_format($campagne->contrat_forfait_communication, 0, ',', ' ') }} FCFA</strong></li>
    <li>— Forfait déplacement : <strong>{{ number_format($campagne->contrat_forfait_deplacement, 0, ',', ' ') }} FCFA</strong></li>
    <li>— Une prime de performance hebdomadaire de <strong>{{ number_format($campagne->prime_meilleur_vendeur, 0, ',', ' ') }} FCFA</strong> sera attribuée au meilleur vendeur de la semaine, sur la base des rapports et résultats transmis.</li>
</ul>
@if($campagne->aide_hebdo_active)
    <p>Outre ce forfait, une aide hebdomadaire de <strong>{{ number_format($campagne->aide_hebdo_montant, 0, ',', ' ') }} FCFA</strong> par semaine de campagne est prévue (dont carburant {{ number_format($campagne->aide_hebdo_carburant, 0, ',', ' ') }} FCFA et crédit téléphonique {{ number_format($campagne->aide_hebdo_credit_tel, 0, ',', ' ') }} FCFA), sous réserve des versements effectifs et de leur accusé de réception par la Prestataire.</p>
@endif
<p>Le paiement interviendra en une seule fois à la fin de la campagne, après validation du rapport final et contrôle des résultats (sauf disposition contraire de GDA).</p>

@if($campagne->contrat_clause_libre)
    <div class="mt-3 p-3 bg-light rounded border">
        <strong>Dispositions complémentaires :</strong>
        <div class="mt-2">{!! nl2br(e($campagne->contrat_clause_libre)) !!}</div>
    </div>
@endif

<p class="mt-4">Fait à {{ $campagne->contrat_lieu_signature }}, le {{ $date_signature_affichee }}<br>
    En deux exemplaires originaux, dont un remis à chaque partie.</p>

<div class="row mt-5 pt-3 border-top">
    <div class="col-md-6">
        <p class="mb-0"><strong>La Prestataire</strong></p>
        <p class="mb-0">{{ $nomPresta }}</p>
    </div>
    <div class="col-md-6 text-md-end">
        <p class="mb-0"><strong>Le Représentant de GDA</strong></p>
        <p class="mb-0">{{ $campagne->contrat_representant_nom }}</p>
    </div>
</div>
