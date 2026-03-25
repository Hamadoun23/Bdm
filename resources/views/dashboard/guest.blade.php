@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="text-center py-5">
    <h2>Bienvenue sur BDM</h2>
    <p class="text-muted">Système de gestion des ventes de cartes</p>
    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Se connecter</a>
</div>
@endsection
