<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VenteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VenteController extends Controller
{
    public function __construct(
        private VenteService $venteService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez vous connecter.',
            ], 401);
        }
        if (!$user->isCommercial() || !$user->agence_id) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé. Seuls les commerciaux peuvent enregistrer des ventes.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'ville' => 'nullable|string|max:100',
            'quartier' => 'nullable|string|max:100',
            'type_carte_id' => ['required', Rule::exists('types_cartes', 'id')->where('actif', true)],
            'carte_identite' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->safe()->except('carte_identite');
        if ($request->hasFile('carte_identite')) {
            $data['carte_identite'] = $request->file('carte_identite')->store('cartes-identite', 'public');
        }

        try {
            $vente = $this->venteService->enregistrerVente($data, $user->id);
            return response()->json([
                'success' => true,
                'message' => 'Vente enregistrée avec succès.',
                'vente' => $vente,
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue.',
            ], 500);
        }
    }
}
