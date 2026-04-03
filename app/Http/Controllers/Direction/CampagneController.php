<?php

namespace App\Http\Controllers\Direction;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Services\CampagneDetailService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampagneController extends Controller
{
    public function index(): View
    {
        Campagne::syncStatuts();
        $campagnes = Campagne::with('agences')->orderByDesc('date_debut')->paginate(15);

        return view('direction.campagnes.index', compact('campagnes'));
    }

    public function show(Request $request, Campagne $campagne, CampagneDetailService $detailService): View
    {
        $data = $detailService->buildShowData($campagne, $request);

        return view('admin.campagnes.show', array_merge($data, [
            'isDirectionDetail' => true,
        ]));
    }
}
