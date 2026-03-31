<?php

namespace App\Services;

use App\Models\Campagne;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class ContratPrestationService
{
    /** @return array<string, mixed> */
    public function donneesPourTemplate(Campagne $campagne, User $commercial): array
    {
        $lundiDebut = $campagne->date_debut->copy();
        if ($lundiDebut->dayOfWeek !== Carbon::MONDAY) {
            $lundiDebut->startOfWeek(Carbon::MONDAY);
        }

        return [
            'campagne' => $campagne,
            'commercial' => $commercial,
            'lundi_effectif' => $lundiDebut,
            'date_signature_affichee' => now()->format('d/m/Y'),
        ];
    }

    public function renduHtml(Campagne $campagne, User $commercial): View
    {
        return view('contrats.prestation', $this->donneesPourTemplate($campagne, $commercial));
    }
}
