<?php

namespace App\Http\Controllers;

use App\Models\Campagne;
use App\Models\Vente;
use App\Services\CampagneStatsScope;
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
        Campagne::syncStatuts();
        $campagneIdsStats = CampagneStatsScope::idsPour(null);
        $fenetreStats = CampagneStatsScope::fenetreDates(null);
        $libelleStatsCampagne = CampagneStatsScope::libelle(null);

        $baseVentesStats = Vente::query();
        CampagneStatsScope::appliquerSurVentes($baseVentesStats, null);

        $ventesTotal = (clone $baseVentesStats)->count();
        $ventesMois = $fenetreStats
            ? (clone $baseVentesStats)->whereBetween('created_at', [$fenetreStats['debut'], $fenetreStats['fin']])->count()
            : 0;
        if ($fenetreStats) {
            $classement = $this->primeService->getClassementPourCampagnesIds(
                $campagneIdsStats,
                $fenetreStats['debut'],
                $fenetreStats['fin']
            );
        } else {
            $classement = collect();
        }

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
            'readOnly', 'libelleStatsCampagne'
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

        $campagnesStats = Campagne::getCampagnesPourStats($agenceId);
        $campagneIdsStats = $campagnesStats->pluck('id')->map(fn ($id) => (int) $id)->all();
        $fenetreStats = CampagneStatsScope::fenetreDates($agenceId);
        $libelleStatsCampagne = CampagneStatsScope::libelle($agenceId);
        $campagneStatsRef = $campagnesStats->first();

        if ($campagneIdsStats !== [] && $fenetreStats) {
            $mesVentes = Vente::query()
                ->where('user_id', $user->id)
                ->whereIn('campagne_id', $campagneIdsStats)
                ->count();
            $classement = $this->primeService->getClassementPourCampagnesIds(
                $campagneIdsStats,
                $fenetreStats['debut'],
                $fenetreStats['fin'],
                false,
                $agenceId
            );
        } else {
            $mesVentes = 0;
            $classement = collect();
        }

        $rangIdx = $classement->search(fn ($c) => (int) $c['user_id'] === (int) $user->id);
        $monRang = $rangIdx !== false ? (int) $classement->values()[$rangIdx]['rang'] : null;

        return view('dashboard.commercial', compact(
            'user', 'mesVentes', 'classement', 'monRang', 'campagneActive', 'campagnesOuvertes', 'peutVendre',
            'campagneStatsRef', 'libelleStatsCampagne'
        ));
    }
}
