<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureCompteActif;
use App\Http\Middleware\HandleInertiaRequests;
use App\Models\Campagne;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->call(fn () => Campagne::syncStatuts())->dailyAt('01:00');
    })
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Fait confiance au proxy nginx (hôte ou conteneur) en amont pour X-Forwarded-Proto/Host/etc. —
        // sans ça, derrière un reverse-proxy HTTPS, Laravel génère des URLs d'assets en http:// (contenu
        // mixte bloqué par le navigateur). L'app n'est jamais exposée directement, seul le proxy y accède.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => CheckRole::class,
        ]);
        $middleware->web(append: [
            HandleInertiaRequests::class,
            EnsureCompteActif::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
