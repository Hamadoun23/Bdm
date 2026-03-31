<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function byAgence(Request $request, int $agenceId): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        if (! $user->isAdmin() && ! $user->isDirection() && $user->agence_id != $agenceId) {
            return response()->json(['success' => false, 'message' => 'Accès refusé'], 403);
        }

        $stocks = Stock::where('agence_id', $agenceId)->get();

        return response()->json([
            'success' => true,
            'stocks' => $stocks,
        ]);
    }
}
