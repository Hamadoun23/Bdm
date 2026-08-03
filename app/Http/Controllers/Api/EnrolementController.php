<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Services\EnrolementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EnrolementController extends Controller
{
    public function __construct(
        private EnrolementService $enrolementService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez vous connecter.',
            ], 401);
        }
        if (! $user->isCommercial() || ! $user->agence_id) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé. Seuls les commerciaux peuvent enregistrer des enrôlements.',
            ], 403);
        }

        Campagne::syncStatuts();
        $idsCampagnesOuvertes = Campagne::getActivesPourAgence((int) $user->agence_id)
            ->where('type', Campagne::TYPE_ENROLEMENT_APP)
            ->filter(fn (Campagne $c) => $c->estEngageCommercial($user->id))
            ->pluck('id')
            ->all();
        if ($idsCampagnesOuvertes === []) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune campagne d’enrôlement ouverte pour votre agence.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'campagne_id' => [
                Rule::requiredIf(count($idsCampagnesOuvertes) > 1),
                'nullable',
                'integer',
                Rule::in($idsCampagnesOuvertes),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->safe()->all();
        if (count($idsCampagnesOuvertes) === 1) {
            $data['campagne_id'] = $idsCampagnesOuvertes[0];
        }

        try {
            $enrolement = $this->enrolementService->enregistrerEnrolement($data, $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Enrôlement enregistré avec succès.',
                'enrolement' => $enrolement,
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
