<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11pt; color: #222; }
        h1 { font-size: 16pt; margin-bottom: 0.5rem; }
        .muted { color: #666; font-size: 9pt; }
        table.kv { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        table.kv td { padding: 4px 8px; border: 1px solid #ccc; vertical-align: top; }
        table.kv td:first-child { width: 32%; font-weight: bold; background: #f5f5f5; }
        h2 { font-size: 13pt; margin-top: 1.2rem; }
        .vente { font-size: 10pt; margin: 4px 0; }
        .page-break { page-break-after: always; height: 0; margin: 0; padding: 0; }
        .id-page h2 { margin-top: 0; }
        .id-img { max-width: 100%; max-height: 700px; display: block; margin-top: 8px; }
        .id-link { color: #0d47a1; word-break: break-all; font-size: 9pt; }
    </style>
</head>
<body>
    <h1>Fiche client</h1>
    <p class="muted">Généré le {{ now()->format('d/m/Y H:i') }} — ID #{{ $client->id }}</p>

    <table class="kv">
        <tr><td>Prénom</td><td>{{ $client->prenom }}</td></tr>
        <tr><td>Nom</td><td>{{ $client->nom }}</td></tr>
        <tr><td>Téléphone</td><td>{{ $client->telephone ?? '—' }}</td></tr>
        <tr><td>Ville</td><td>{{ $client->ville ?? '—' }}</td></tr>
        <tr><td>Quartier</td><td>{{ $client->quartier ?? '—' }}</td></tr>
        <tr><td>Type de carte</td><td>{{ $client->typeCarte?->code ?? '—' }}</td></tr>
        <tr><td>Statut carte</td><td>{{ $client->statut_carte }}</td></tr>
        <tr><td>Commercial</td><td>{{ $client->user->name ?? '—' }}</td></tr>
        <tr><td>Agence</td><td>{{ $client->user?->agence?->nom ?? '—' }}</td></tr>
        <tr><td>Créé le</td><td>{{ $client->created_at->format('d/m/Y H:i') }}</td></tr>
        @if($identite['has_file'])
        <tr><td>Pièce d’identité</td><td>Voir page 2 @if($identite['download_url'])<br><span class="muted">Lien : {{ $identite['download_url'] }}</span>@endif</td></tr>
        @endif
    </table>

    <h2>Ventes</h2>
    @forelse($client->ventes as $v)
        <p class="vente">
            {{ $v->created_at->format('d/m/Y H:i') }} —
            {{ $v->typeCarte?->code ?? '?' }} —
            {{ $v->user->name ?? '—' }} ({{ $v->agence->nom ?? '—' }}) — {{ $v->statut_activation }}
        </p>
    @empty
        <p class="muted">Aucune vente.</p>
    @endforelse

    @if($identite['has_file'])
    <div class="page-break"></div>
    <div class="id-page">
        <h2>Pièce d’identité</h2>
        @if(!$identite['stored'])
        <p class="muted">Fichier référencé mais absent du stockage.</p>
        @elseif($identite['image_src'])
        <p class="muted">{{ $identite['label'] }}</p>
        <img src="{{ $identite['image_src'] }}" alt="Pièce d'identité" class="id-img">
        @if($identite['download_url'])
        <p><a href="{{ $identite['download_url'] }}" class="id-link">Télécharger le fichier</a></p>
        @endif
        @elseif($identite['is_pdf'])
        <p>Document au format PDF : il ne peut pas être fusionné dans ce PDF. Utilisez le lien ci-dessous pour le télécharger.</p>
        @if($identite['download_url'])
        <p><a href="{{ $identite['download_url'] }}" class="id-link">Télécharger la pièce d’identité (PDF)</a></p>
        @endif
        @else
        @if($identite['download_url'])
        <p><a href="{{ $identite['download_url'] }}" class="id-link">Télécharger la pièce d’identité</a></p>
        @endif
        @endif
    </div>
    @endif
</body>
</html>
