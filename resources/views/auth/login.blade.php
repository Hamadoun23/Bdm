<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#FF6A3A">
    <title>Connexion — Gda Money</title>
    @include('layouts.partials.pwa')
    @include('layouts.partials.favicon')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/gda-theme.css') }}?v=9" rel="stylesheet">
    <style>
        body {
            font-family: var(--gda-font-family);
        }
        .gda-login-page {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: max(1.5rem, env(safe-area-inset-top, 0px)) max(1.5rem, env(safe-area-inset-right, 0px)) max(1.5rem, env(safe-area-inset-bottom, 0px)) max(1.5rem, env(safe-area-inset-left, 0px));
            background:
                radial-gradient(ellipse 100% 80% at 80% -10%, rgba(255, 106, 58, 0.25) 0%, transparent 55%),
                radial-gradient(ellipse 70% 50% at 0% 110%, rgba(56, 20, 25, 0.5) 0%, transparent 50%),
                linear-gradient(160deg, #381419 0%, #303030 45%, #2a2424 100%);
            position: relative;
            overflow: hidden;
        }
        .gda-login-page::before {
            content: "";
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }
        .gda-login-card {
            position: relative;
            max-width: min(440px, 100%);
            width: 100%;
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 106, 58, 0.15);
        }
        .gda-login-header {
            background: linear-gradient(135deg, #ff6a3a 0%, #b26440 100%);
            padding: 2rem 1.75rem 1.75rem;
            text-align: center;
            border-bottom: 3px solid rgba(255, 255, 255, 0.25);
        }
        .gda-login-header img {
            height: 80px;
            width: auto;
            max-width: 220px;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.25));
            margin-bottom: 0.75rem;
        }
        .gda-login-header h1 {
            font-family: var(--gda-font-family);
            font-weight: 800;
            font-size: 1.85rem;
            margin: 0;
            color: #fff;
            letter-spacing: -0.02em;
        }
        .gda-login-header p {
            margin: 0.35rem 0 0;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.92);
            font-weight: 500;
        }
        .gda-login-body {
            background: #fff;
            padding: 2rem 1.75rem 2rem;
        }
        .gda-login-body .form-control {
            border-radius: 0.65rem;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(48, 48, 48, 0.15);
        }
        .gda-login-body .form-control:focus {
            border-color: #ff6a3a;
            box-shadow: 0 0 0 0.2rem rgba(255, 106, 58, 0.2);
        }
        .gda-login-btn {
            border-radius: 0.65rem;
            padding: 0.8rem 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ff6a3a 0%, #e85a2e 100%);
            border: none;
            box-shadow: 0 6px 20px rgba(255, 106, 58, 0.4);
        }
        .gda-login-btn:hover {
            background: linear-gradient(135deg, #ff7d52 0%, #ff6a3a 100%);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="gda-login-page">
        <div class="gda-login-card">
            <div class="gda-login-header">
                <img src="{{ asset('logo/gdamoney.png') }}" alt="Gda Money">
                <h1>Gda Money</h1>
                <p>Connexion à votre espace</p>
            </div>
            <div class="gda-login-body">
                @if (session('status'))
                    <div class="alert alert-info mb-3">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Identifiant</label>
                        <input id="email" type="text" name="email" class="form-control" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Admin : nom (ex. Sylla) · Commercial / chef : n° téléphone">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Mot de passe</label>
                        @include('layouts.partials.password-input-group', [
                            'name' => 'password',
                            'id' => 'password',
                            'required' => true,
                            'autocomplete' => 'current-password',
                            'placeholder' => '••••••••',
                            'inputClass' => '',
                        ])
                    </div>
                    <div class="mb-4">
                        <div class="form-check">
                            <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
                            <label for="remember_me" class="form-check-label">Rester connecté</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary gda-login-btn w-100">
                        Se connecter
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('layouts.partials.password-toggle-script')
    @include('layouts.partials.register-sw')
</body>
</html>
