<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#FF6A3A">
    <title>@yield('title', 'Accueil') — {{ config('app.name') }}</title>
    @include('layouts.partials.pwa')
    @include('layouts.partials.favicon')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/gda-theme.css') }}?v=5" rel="stylesheet">
</head>
<body class="gda-app">
    <nav class="navbar navbar-expand-lg navbar-dark gda-navbar gda-navbar-split">
        <div class="gda-navbar-row d-flex flex-grow-1 align-items-stretch flex-wrap w-100">
            <a class="gda-brand-zone gda-brand d-flex align-items-stretch" href="{{ auth()->check() ? route('dashboard') : url('/') }}">
                <span class="gda-brand-wrap d-flex align-items-center gap-3">
                    <img src="{{ asset('logo/gdamoney.png') }}" alt="Gda Money" class="gda-logo-img" width="72" height="72">
                    <span class="gda-brand-text">
                        <span class="gda-brand-title">Gda Money</span>
                        <span class="gda-brand-tagline d-none d-sm-block">Cartes &amp; performance</span>
                    </span>
                </span>
            </a>
            <div class="gda-navbar-end d-flex flex-grow-1 align-items-center flex-wrap">
                <div class="container d-flex flex-wrap align-items-center justify-content-end py-2 py-lg-0 gx-3">
                    <button class="navbar-toggler d-lg-none ms-auto my-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Menu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-lg-end flex-grow-lg-1 w-100 w-lg-auto" id="navbarNav">
                        <ul class="navbar-nav ms-auto align-items-lg-center">
                            @auth
                                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                                @if(auth()->user()?->isChefAgence())
                                <li class="nav-item"><a class="nav-link" href="{{ route('ventes.index') }}">Ventes agence</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('clients.index') }}">Clients</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('rapports.index') }}">Rapports</a></li>
                                @endif
                                @if(auth()->user()?->isAdmin())
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Administration</a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('admin.agences.index') }}">Agences</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.campagnes.index') }}">Campagnes</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.types-cartes.index') }}">Types de cartes</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.stocks.index') }}">Stocks</a></li>
                                        <li><a class="dropdown-item" href="{{ route('rapports.index') }}">Rapports</a></li>
                                        <li><a class="dropdown-item" href="{{ route('clients.index') }}">Clients</a></li>
                                    </ul>
                                </li>
                                @endif
                                @if(auth()->user()?->isChefAgence())
                                <li class="nav-item"><a class="nav-link" href="{{ url('/agence/stocks') }}">Stocks agence</a></li>
                                @endif
                                @unless(auth()->user()?->isCommercial())
                                <li class="nav-item"><a class="nav-link" href="{{ route('performances.index') }}">Performances</a></li>
                                @endunless
                                <li class="nav-item">
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link nav-link text-white">Déconnexion</button>
                                    </form>
                                </li>
                            @else
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="gda-main container py-3 py-md-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    @include('layouts.partials.register-sw')
</body>
</html>
