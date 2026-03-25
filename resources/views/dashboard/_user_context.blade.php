@php
    $displayName = trim(($user->prenom ?? '') . ' ' . ($user->name ?? ''));
    if ($displayName === '') {
        $displayName = $user->name ?? '—';
    }
@endphp
<div class="card mb-4 gda-user-strip shadow-sm">
    <div class="card-body py-3">
        <span class="text-muted">Connecté :</span>
        <strong>{{ $displayName }}</strong>
        <span class="text-muted mx-2">|</span>
        <span class="text-muted">Agence :</span>
        @if($user->isAdmin())
            <strong>Administration globale</strong>
        @elseif($user->agence)
            <strong>{{ $user->agence->nom }}</strong>
        @else
            <span class="text-warning">Non assignée</span>
        @endif
    </div>
</div>
