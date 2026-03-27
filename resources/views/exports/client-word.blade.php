<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche client {{ $client->prenom }} {{ $client->nom }}</title>
    <!--[if gte mso 9]><xml><w:WordDocument><w:View>Print</w:View></w:WordDocument></xml><![endif]-->
    <style>
        body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; }
        h1 { font-size: 18pt; }
        h2 { font-size: 14pt; margin-top: 1.2em; }
        table { border-collapse: collapse; width: 100%; }
        td { border: 1px solid #ccc; padding: 6px; vertical-align: top; }
        td.k { font-weight: bold; background: #f5f5f5; width: 32%; }
        .muted { color: #666; font-size: 9pt; }
        .page-break { page-break-after: always; }
        .id-img { max-width: 100%; height: auto; }
    </style>
</head>
<body>
    <h1>Fiche client</h1>
    <p class="muted">Généré le {{ now()->format('d/m/Y H:i') }} — ID #{{ $client->id }}</p>

    <table>
        <tr><td class="k">Prénom</td><td>{{ $client->prenom }}</td></tr>
        <tr><td class="k">Nom</td><td>{{ $client->nom }}</td></tr>
        <tr><td class="k">Téléphone</td><td>{{ $client->telephone ?? '—' }}</td></tr>
        <tr><td class="k">Ville</td><td>{{ $client->ville ?? '—' }}</td></tr>
        <tr><td class="k">Quartier</td><td>{{ $client->quartier ?? '—' }}</td></tr>
        <tr><td class="k">Type de carte</td><td>{{ $client->typeCarte?->code ?? '—' }}</td></tr>
        <tr><td class="k">Statut carte</td><td>{{ $client->statut_carte }}</td></tr>
        <tr><td class="k">Commercial</td><td>{{ $client->user->name ?? '—' }}</td></tr>
        <tr><td class="k">Agence</td><td>{{ $client->user?->agence?->nom ?? '—' }}</td></tr>
        <tr><td class="k">Créé le</td><td>{{ $client->created_at->format('d/m/Y H:i') }}</td></tr>
        @if($identite['has_file'])
        <tr><td class="k">Pièce d’identité</td><td>Voir page 2</td></tr>
        @endif
    </table>

    <h2>Ventes</h2>
    @forelse($client->ventes as $v)
        <p>
            {{ $v->created_at->format('d/m/Y H:i') }} —
            {{ $v->typeCarte?->code ?? '?' }} —
            @if($v->montant !== null){{ number_format((int) $v->montant) }} F@else — @endif
            — {{ $v->user->name ?? '—' }} ({{ $v->agence->nom ?? '—' }}) — {{ $v->statut_activation }}
        </p>
    @empty
        <p class="muted">Aucune vente.</p>
    @endforelse

    @if($identite['has_file'])
    <div class="page-break"></div>
    <h2>Pièce d’identité</h2>
    @if(!$identite['stored'])
    <p class="muted">Fichier référencé mais absent du stockage.</p>
    @elseif($identite['image_src'])
    <p class="muted">{{ $identite['label'] }}</p>
    <p><img src="{{ $identite['image_src'] }}" alt="Pièce d'identité" class="id-img"></p>
    @if($identite['download_url'])
    <p><a href="{{ $identite['download_url'] }}">Télécharger le fichier</a></p>
    @endif
    @elseif($identite['is_pdf'])
    <p>Document PDF : ouvrez le lien suivant pour télécharger la pièce d’identité.</p>
    @if($identite['download_url'])
    <p><a href="{{ $identite['download_url'] }}">Télécharger la pièce d’identité (PDF)</a></p>
    @endif
    @else
    @if($identite['download_url'])
    <p><a href="{{ $identite['download_url'] }}">Télécharger la pièce d’identité</a></p>
    @endif
    @endif
    @endif
</body>
</html>
