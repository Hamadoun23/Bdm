<?php

namespace App\Http\Controllers\Direction;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Services\CampagneDetailService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CampagneController extends Controller
{
    public function index(): Response
    {
        Campagne::syncStatuts();
        $campagnes = Campagne::with('agences')->orderByDesc('date_debut')->paginate(15);

        return Inertia::render('Direction/Campagnes/Index', [
            'campagnes' => [
                'data' => $campagnes->getCollection()->map(fn (Campagne $c) => [
                    'id' => $c->id,
                    'nom' => $c->nom,
                    'date_debut' => $c->date_debut->format('d/m/Y'),
                    'date_fin' => $c->date_fin->format('d/m/Y'),
                    'agences' => $c->toutes_agences ? 'Toutes' : ($c->agences->pluck('nom')->join(', ') ?: '—'),
                    'prime_meilleur_vendeur' => number_format($c->prime_meilleur_vendeur, 0, ',', ' '),
                    'statut' => $c->statut_effectif,
                ])->values(),
                'links' => $campagnes->linkCollection(),
                'from' => $campagnes->firstItem(),
                'to' => $campagnes->lastItem(),
                'total' => $campagnes->total(),
            ],
        ]);
    }

    public function show(Request $request, Campagne $campagne, CampagneDetailService $detailService): Response
    {
        $data = $detailService->buildShowData($campagne, $request);

        return Inertia::render('Admin/Campagnes/Show', $detailService->toInertiaProps($data, true));
    }
}
