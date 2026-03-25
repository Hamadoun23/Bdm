@php
    $gdaAppIcon = asset('logo/iconesgda.png');
@endphp
{{-- Favicon + icônes install PWA (Android / Chrome / Edge) --}}
<link rel="icon" type="image/png" sizes="32x32" href="{{ $gdaAppIcon }}">
<link rel="icon" type="image/png" sizes="192x192" href="{{ $gdaAppIcon }}">
<link rel="icon" type="image/png" sizes="512x512" href="{{ $gdaAppIcon }}">
{{-- iOS / Safari « Ajouter à l’écran d’accueil » --}}
<link rel="apple-touch-icon" sizes="180x180" href="{{ $gdaAppIcon }}">
<link rel="apple-touch-icon" href="{{ $gdaAppIcon }}">
