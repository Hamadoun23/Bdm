<?php

use App\Http\Controllers\Admin\AgenceController;
use App\Http\Controllers\Admin\CampagneAideVersementController;
use App\Http\Controllers\Admin\CampagneContratArticleController;
use App\Http\Controllers\Admin\CampagneController;
use App\Http\Controllers\Admin\RapportController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\TelephoniqueRapportController as AdminTelephoniqueRapportController;
use App\Http\Controllers\Admin\TypeCarteController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserLoginLogController;
use App\Http\Controllers\Api\VenteController;
use App\Http\Controllers\Clients\ClientController as ClientsClientController;
use App\Http\Controllers\Commercial\ClientController as CommercialClientController;
use App\Http\Controllers\Commercial\ContratPrestationController;
use App\Http\Controllers\Commercial\TelephoniqueRapportController;
use App\Http\Controllers\Commercial\VenteController as CommercialVenteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Direction\CampagneController as DirectionCampagneController;
use App\Http\Controllers\Direction\ReferentielController as DirectionReferentielController;
use App\Http\Controllers\PerformanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/site.webmanifest', function () {
    $base = rtrim(request()->getBasePath(), '/');
    $start = ($base === '' || $base === '/') ? '/' : $base.'/';
    // Icône PWA / écran d’accueil : public/logo/iconesgda.png
    $icon = asset('logo/iconesgda.png');

    return response()->json([
        'name' => config('app.name', 'Campagne BDM'),
        'short_name' => config('app.name', 'Campagne BDM'),
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware('role:admin,direction')->get('/alertes-stock-faible', [DashboardController::class, 'alertesStockFaible'])->name('alertes.stock-faible');
    // Ventes terrain : commerciaux + lecture admin/direction
    Route::get('/ventes', [CommercialVenteController::class, 'index'])->name('ventes.index')->middleware('role:admin,direction,commercial');
    Route::get('/ventes/create', [CommercialVenteController::class, 'create'])->name('ventes.create')->middleware('role:commercial');
    Route::delete('/ventes/{vente}', [CommercialVenteController::class, 'destroy'])->name('ventes.destroy')->middleware('role:commercial');
    Route::post('/api/ventes', [VenteController::class, 'store'])->name('api.ventes.store')->middleware('role:commercial');

    Route::middleware('role:commercial')->group(function () {
        Route::get('/mes-clients/{client}/modifier', [CommercialClientController::class, 'edit'])->name('commercial.clients.edit');
        Route::put('/mes-clients/{client}', [CommercialClientController::class, 'update'])->name('commercial.clients.update');
        Route::delete('/mes-clients/{client}', [CommercialClientController::class, 'destroy'])->name('commercial.clients.destroy');
    });

    Route::middleware('role:commercial,commercial_telephonique')->group(function () {
        Route::get('/mon-contrat', [ContratPrestationController::class, 'show'])->name('commercial.contrat');
        Route::post('/mon-contrat/accepter', [ContratPrestationController::class, 'accepter'])->name('commercial.contrat.accepter');
        Route::post('/mon-contrat/rejeter', [ContratPrestationController::class, 'rejeter'])->name('commercial.contrat.rejeter');
        Route::post('/mes-aides/{versement}/accuser', [ContratPrestationController::class, 'accuserVersement'])->name('commercial.aides.accuser');
    });

    Route::middleware('role:commercial_telephonique')->group(function () {
        Route::get('/reporting-telephonique', [TelephoniqueRapportController::class, 'index'])->name('commercial.telephonique.index');
        Route::get('/reporting-telephonique/saisie', [TelephoniqueRapportController::class, 'create'])->name('commercial.telephonique.create');
        Route::post('/reporting-telephonique', [TelephoniqueRapportController::class, 'store'])->name('commercial.telephonique.store');
        Route::delete('/reporting-telephonique/{telephoniqueRapport}', [TelephoniqueRapportController::class, 'destroy'])->name('commercial.telephonique.destroy');
    });

    Route::middleware('role:admin,direction')->prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientsClientController::class, 'index'])->name('index');
        Route::get('/{client}/export', [ClientsClientController::class, 'export'])->name('export');
        Route::get('/{client}', [ClientsClientController::class, 'show'])->name('show');
    });

    Route::middleware('role:direction')->prefix('direction')->name('direction.')->group(function () {
        Route::get('campagnes', [DirectionCampagneController::class, 'index'])->name('campagnes.index');
        Route::get('campagnes/{campagne}', [DirectionCampagneController::class, 'show'])->name('campagnes.show');
        Route::get('types-de-cartes', [DirectionReferentielController::class, 'typesCartes'])->name('types-cartes.index');
    });

    Route::middleware('role:admin,direction')->prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [RapportController::class, 'index'])->name('index');
        Route::get('/export', [RapportController::class, 'export'])->name('export');
        Route::get('/campagnes/{campagne}/ventes', [RapportController::class, 'campagneVentes'])->name('campagnes.ventes');
        Route::get('/campagnes/{campagne}/clients', [RapportController::class, 'campagneClients'])->name('campagnes.clients');
    });

    Route::get('/admin/rapports', function () {
        return redirect()->route('rapports.index');
    })->middleware(['auth', 'role:admin']);

    Route::get('/admin/rapports/export', function (Request $request) {
        return redirect()->route('rapports.export', $request->only(['type', 'date', 'agence']));
    })->middleware(['auth', 'role:admin']);

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('agences', AgenceController::class)->except('show');
        Route::resource('users', UserController::class)->parameters(['users' => 'user'])->except('show');
        Route::resource('types-cartes', TypeCarteController::class)->except(['show']);
        Route::resource('campagnes', CampagneController::class);
        Route::post('campagnes/{campagne}/arreter', [CampagneController::class, 'arreter'])->name('campagnes.arreter');
        Route::post('campagnes/{campagne}/annuler', [CampagneController::class, 'annuler'])->name('campagnes.annuler');
        Route::post('campagnes/{campagne}/reprogrammer', [CampagneController::class, 'reprogrammer'])->name('campagnes.reprogrammer');
        Route::post('campagnes/{campagne}/versements', [CampagneAideVersementController::class, 'store'])->name('campagnes.versements.store');
        Route::delete('campagnes/{campagne}/versements/{versement}', [CampagneAideVersementController::class, 'destroy'])->name('campagnes.versements.destroy');
        Route::post('campagnes/{campagne}/contrat-articles', [CampagneContratArticleController::class, 'store'])->name('campagnes.contrat-articles.store');
        Route::put('campagnes/{campagne}/contrat-articles/{article}', [CampagneContratArticleController::class, 'update'])->name('campagnes.contrat-articles.update');
        Route::delete('campagnes/{campagne}/contrat-articles/{article}', [CampagneContratArticleController::class, 'destroy'])->name('campagnes.contrat-articles.destroy');
        Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
        Route::post('stocks/approvisionner', [StockController::class, 'approvisionner'])->name('stocks.approvisionner');
        Route::post('stocks/ajuster', [StockController::class, 'ajuster'])->name('stocks.ajuster');
        Route::get('stocks/mouvements', [StockController::class, 'mouvements'])->name('stocks.mouvements');
        Route::get('stocks/mouvements/{agenceId}', [StockController::class, 'mouvements'])->name('stocks.mouvements.agence');
        Route::get('journal-connexions', [UserLoginLogController::class, 'index'])->name('login-logs.index');
        Route::get('reporting-telephonique', [AdminTelephoniqueRapportController::class, 'index'])->name('telephonique-rapports.index');
    });

    Route::get('/performances', [PerformanceController::class, 'index'])->name('performances.index');

    Route::get('/api/stocks/agence/{agenceId}', [App\Http\Controllers\Api\StockController::class, 'byAgence'])->name('api.stocks.agence');
});

require __DIR__.'/auth.php';
