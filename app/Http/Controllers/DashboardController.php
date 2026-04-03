<?php

namespace App\Http\Controllers;

use App\Models\Campagne;
use App\Models\Stock;
use App\Models\Vente;
use App\Services\PrimeService;
use App\Services\StockAlertService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private StockAlertService $stockAlertService,
        private PrimeService $primeService
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        if (! $user) {
            return view('dashboard.guest');
        }

        if ($user->isAdmin()) {
            return $this->dashboardAdmin($user, false);
        }

        if ($user->isDirection()) {
            return $this->dashboardAdmin($user, true);
        }

        if ($user->isCommercialTelephonique()) {
            return $this->dashboardTelephonique($user);
        }

        return $this->dashboardCommercial($user);
    }

    private function dashboardAdmin($user, bool $directionReadOnly): View
    {
        $ventesTotal = Vente::count();
        $ventesMois = Vente::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $stocks = Stock::with('agence')->get();
        $alertes = $this->stockAlertService->getAlertes();
        $classement = $this->primeService->getClassement(now()->format('Y-m'));

        Campagne::syncStatuts();
        $campagnesTotal = Campagne::count();
        $campagneActive = Campagne::where('actif', true)->first();
        $campagnesEnCours = Campagne::where('statut', Campagne::STATUT_EN_COURS)->count();
        $campagnesProgrammees = Campagne::where('statut', Campagne::STATUT_PROGRAMMEE)->count();

        $readOnly = $directionReadOnly;

        return view('dashboard.admin', compact(
            'user',
            'ventesTotal', 'ventesMois', 'stocks', 'alertes',
            'classement', 'campagnesTotal', 'campagneActive', 'campagnesEnCours', 'campagnesProgrammees',
            'readOnly'
        ));
    }

    private function dashboardTelephonique($user): View
    {
        $user->load('agence');
        Campagne::syncStatuts();
        $agenceId = $user->agence_id ? (int) $user->agence_id : null;
        $campagneActive = $agenceId ? Campagne::getActiveForAgence($agenceId) : null;
        $signataire = $campagneActive && $campagneActive->userEstSignataireContrat($user);

        return view('dashboard.telephonique', compact('user', 'campagneActive', 'signataire'));
    }

    private function dashboardCommercial($user): View
    {
        $user->load('agence');
        Campagne::syncStatuts();
        $agenceId = $user->agence_id ? (int) $user->agence_id : null;
        $campagneActive = $agenceId ? Campagne::getActiveForAgence($agenceId) : null;
        $peutVendre = $agenceId && $campagneActive && $campagneActive->estOuverteAuxVentes($agenceId);

        $stocks = $user->agence_id ? Stock::with('typeCarte')->where('agence_id', $user->agence_id)->get() : collect();

        // Même logique que Performances : période = campagne active pour l’agence (pas le mois calendaire seul).
        if ($campagneActive) {
            $dateDebut = $campagneActive->date_debut->copy()->startOfDay();
            $dateFin = $campagneActive->date_fin->copy()->endOfDay();
            $mesVentes = Vente::query()
                ->where('user_id', $user->id)
                ->where('campagne_id', $campagneActive->id)
                ->whereBetween('created_at', [$dateDebut, $dateFin])
                ->count();
            $classement = $this->primeService->getClassementPourCampagne($campagneActive, $dateDebut, $dateFin);
        } else {
            $mesVentes = Vente::query()
                ->where('user_id', $user->id)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count();
            $classement = $this->primeService->getClassement(now()->format('Y-m'), $agenceId);
        }

        $rangIdx = $classement->search(fn ($c) => (int) $c['user_id'] === (int) $user->id);
        $monRang = $rangIdx !== false ? (int) $classement->values()[$rangIdx]['rang'] : null;

        return view('dashboard.commercial', compact(
            'user', 'mesVentes', 'stocks', 'classement', 'monRang', 'campagneActive', 'peutVendre'
        ));
    }

    public function alertesStockFaible(): View
    {
        $alertes = $this->stockAlertService->getAlertes()->sortBy(function (Stock $s) {
            $agence = $s->agence?->nom ?? '';
            $code = $s->typeCarte?->code ?? '';

            return $agence.' '.$code;
        })->values();

        return view('dashboard.alertes-stock-faible', compact('alertes'));
    }
}
