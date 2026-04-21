<?php

namespace App\Http\Controllers;

use App\Models\Campagne;
use App\Models\Vente;
use App\Services\PrimeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
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
        $classement = $this->primeService->getClassement(now()->format('Y-m'));

        Campagne::syncStatuts();
        $campagnesTotal = Campagne::count();
        $campagnesActivesListe = Campagne::where('actif', true)->orderByDesc('date_debut')->get();
        $campagneActive = $campagnesActivesListe->first();
        $campagnesEnCours = Campagne::where('statut', Campagne::STATUT_EN_COURS)->count();
        $campagnesProgrammees = Campagne::where('statut', Campagne::STATUT_PROGRAMMEE)->count();

        $readOnly = $directionReadOnly;

        return view('dashboard.admin', compact(
            'user',
            'ventesTotal', 'ventesMois',
            'classement', 'campagnesTotal', 'campagneActive', 'campagnesActivesListe', 'campagnesEnCours', 'campagnesProgrammees',
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
        $campagnesOuvertes = $agenceId ? Campagne::getActivesPourAgence($agenceId) : collect();
        $campagneActive = $campagnesOuvertes->first();
        $peutVendre = $agenceId && $campagnesOuvertes->isNotEmpty();

        if ($campagnesOuvertes->isNotEmpty()) {
            $idsCampagnes = $campagnesOuvertes->pluck('id')->all();
            $mesVentes = Vente::query()
                ->where('user_id', $user->id)
                ->whereIn('campagne_id', $idsCampagnes)
                ->count();
            $campagneClassement = $campagneActive;
            $dateDebut = $campagneClassement->date_debut->copy()->startOfDay();
            $dateFin = $campagneClassement->date_fin->copy()->endOfDay();
            $classement = $this->primeService->getClassementPourCampagne($campagneClassement, $dateDebut, $dateFin);
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
            'user', 'mesVentes', 'classement', 'monRang', 'campagneActive', 'campagnesOuvertes', 'peutVendre'
        ));
    }
}
