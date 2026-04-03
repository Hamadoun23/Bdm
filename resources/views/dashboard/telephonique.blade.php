@extends('layouts.app')

@section('title', 'Dashboard Téléphonique')

@section('content')
<h4 class="mb-4">Espace téléopératrice</h4>
@include('dashboard._user_context', ['user' => $user])

<div class="alert alert-info small">
    <strong>Rôle :</strong> vous saisissez le <strong>reporting d’appels</strong> (fiche journalière). Vous n’avez pas accès aux ventes terrain ni à la fiche client commerciale.
    Le <strong>contrat de prestation</strong> et les éventuelles <strong>aides</strong> restent disponibles si vous êtes signataire de la campagne en cours.
</div>

@if($campagneActive)
<div class="card shadow-sm mb-4 border-start border-4 border-primary">
    <div class="card-body">
        <h6 class="text-muted text-uppercase small mb-2">Campagne concernant votre agence</h6>
        <p class="mb-1"><strong>{{ $campagneActive->nom }}</strong></p>
        <p class="small text-muted mb-0">{{ $campagneActive->date_debut->format('d/m/Y') }} – {{ $campagneActive->date_fin->format('d/m/Y') }}</p>
        @if($signataire)
        <p class="small text-success mb-0 mt-2">Vous êtes signataire du contrat sur cette campagne.</p>
        @else
        <p class="small text-warning mb-0 mt-2">Vous n’êtes pas encore enregistrée comme signataire sur cette campagne (voir avec l’administrateur).</p>
        @endif
    </div>
</div>
@else
<div class="alert alert-warning">Aucune campagne active pour votre agence pour le moment.</div>
@endif

<div class="d-flex flex-wrap gap-2">
    <a href="{{ route('commercial.telephonique.create') }}" class="btn btn-primary btn-lg">Saisir / modifier la fiche du jour</a>
    <a href="{{ route('commercial.telephonique.index') }}" class="btn btn-outline-primary">Historique des fiches</a>
    <a href="{{ route('commercial.contrat') }}" class="btn btn-outline-secondary">Mon contrat</a>
    <a href="{{ route('performances.index') }}" class="btn btn-outline-secondary">Performances équipe</a>
</div>
@endsection
