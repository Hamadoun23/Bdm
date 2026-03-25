<?php

use App\Http\Controllers\Api\VenteController;
use App\Http\Controllers\Commercial\VenteController as CommercialVenteController;
use Illuminate\Support\Facades\Route;

Route::get('/site.webmanifest', function () {
    $base = rtrim(request()->getBasePath(), '/');
    $start = ($base === '' || $base === '/') ? '/' : $base.'/';
    // Icône PWA / écran d’accueil : public/logo/iconesgda.png
    $icon = asset('logo/iconesgda.png');

    return response()->json([
        'name' => 'Gda Money — Cartes & performance',
        'short_name' => config('app.name', 'Gda Money'),
        'description' => 'Application de gestion des ventes de cartes et du suivi des performances.',
        'start_url' => $start,
        'scope' => $start,
        'display' => 'standalone',
        'display_override' => ['standalone', 'minimal-ui', 'browser'],
        'background_color' => '#381419',
        'theme_color' => '#FF6A3A',
        'lang' => 'fr',
        'dir' => 'ltr',
        'orientation' => 'any',
        'icons' => [
            ['src' => $icon, 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => $icon, 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => $icon, 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable'],
        ],
    ], 200, [
        'Content-Type' => 'application/manifest+json; charset=UTF-8',
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
})->name('pwa.manifest');

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    // Ventes : uniquement les commerciaux
    Route::get('/ventes', [CommercialVenteController::class, 'index'])->name('ventes.index');
    Route::get('/ventes/create', [CommercialVenteController::class, 'create'])->name('ventes.create')->middleware('role:commercial');
    Route::post('/api/ventes', [VenteController::class, 'store'])->name('api.ventes.store')->middleware('role:commercial');

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('agences', \App\Http\Controllers\Admin\AgenceController::class)->except('show');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->parameters(['users' => 'user'])->except('show');
        Route::resource('types-cartes', \App\Http\Controllers\Admin\TypeCarteController::class)->except(['show']);
        Route::resource('campagnes', \App\Http\Controllers\Admin\CampagneController::class);
        Route::post('campagnes/{campagne}/arreter', [\App\Http\Controllers\Admin\CampagneController::class, 'arreter'])->name('campagnes.arreter');
        Route::post('campagnes/{campagne}/annuler', [\App\Http\Controllers\Admin\CampagneController::class, 'annuler'])->name('campagnes.annuler');
        Route::post('campagnes/{campagne}/reprogrammer', [\App\Http\Controllers\Admin\CampagneController::class, 'reprogrammer'])->name('campagnes.reprogrammer');
        Route::get('stocks', [\App\Http\Controllers\Admin\StockController::class, 'index'])->name('stocks.index');
        Route::post('stocks/approvisionner', [\App\Http\Controllers\Admin\StockController::class, 'approvisionner'])->name('stocks.approvisionner');
        Route::post('stocks/ajuster', [\App\Http\Controllers\Admin\StockController::class, 'ajuster'])->name('stocks.ajuster');
        Route::get('stocks/mouvements', [\App\Http\Controllers\Admin\StockController::class, 'mouvements'])->name('stocks.mouvements');
        Route::get('stocks/mouvements/{agenceId}', [\App\Http\Controllers\Admin\StockController::class, 'mouvements'])->name('stocks.mouvements.agence');
        Route::get('rapports', [\App\Http\Controllers\Admin\RapportController::class, 'index'])->name('rapports.index');
        Route::get('rapports/export', [\App\Http\Controllers\Admin\RapportController::class, 'export'])->name('rapports.export');
    });

    // Chef d'agence : dashboard, stocks agence, performances (lecture), activation (lecture), réclamations
    Route::get('/agence/stocks', [\App\Http\Controllers\ChefAgence\StockController::class, 'index'])->name('agence.stocks')->middleware('role:admin,chef_agence');
    Route::post('/agence/stocks/approvisionner', [\App\Http\Controllers\ChefAgence\StockController::class, 'approvisionner'])->name('agence.stocks.approvisionner')->middleware('role:chef_agence');
    Route::post('/agence/stocks/ajuster', [\App\Http\Controllers\ChefAgence\StockController::class, 'ajuster'])->name('agence.stocks.ajuster')->middleware('role:chef_agence');

    Route::get('/performances', [\App\Http\Controllers\PerformanceController::class, 'index'])->name('performances.index');

    Route::get('/api/stocks/agence/{agenceId}', [\App\Http\Controllers\Api\StockController::class, 'byAgence'])->name('api.stocks.agence');
});

require __DIR__.'/auth.php';
