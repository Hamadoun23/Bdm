<?php

namespace App\Services;

use App\Models\Campagne;
use App\Models\Client;
use App\Models\MouvementStock;
use App\Models\Stock;
use App\Models\TypeCarte;
use App\Models\Vente;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class VenteService
{
    public function enregistrerVente(array $data, int $userId): Vente
    {
        $user = \App\Models\User::findOrFail($userId);

        if ($user->role !== 'commercial' || !$user->agence_id) {
            throw new InvalidArgumentException('Seul un commercial avec une agence peut enregistrer une vente.');
        }

        if (!$user->actif) {
            throw new InvalidArgumentException('Compte commercial désactivé. Vous ne pouvez pas enregistrer de vente.');
        }

        $typeCarteId = (int) ($data['type_carte_id'] ?? 0);
        $typeCarte = TypeCarte::where('id', $typeCarteId)->where('actif', true)->first();
        if (!$typeCarte) {
            throw new InvalidArgumentException('Type de carte invalide ou inactif.');
        }

        $agenceId = (int) $user->agence_id;

        Campagne::syncStatuts();
        $ouvertes = Campagne::getActivesPourAgence($agenceId);
        if ($ouvertes->isEmpty()) {
            throw new InvalidArgumentException(
                'Aucune campagne en cours pour votre agence. Les ventes ne sont possibles que pendant une campagne active ; une campagne terminée ou arrêtée ne permet plus d’enregistrer de vente.'
            );
        }

        $campagneIdDemande = isset($data['campagne_id']) ? (int) $data['campagne_id'] : null;
        $campagne = null;
        if ($ouvertes->count() > 1) {
            if (! $campagneIdDemande) {
                throw new InvalidArgumentException(
                    'Plusieurs campagnes sont ouvertes : indiquez la campagne (sélection sur le formulaire).'
                );
            }
            $campagne = $ouvertes->firstWhere('id', $campagneIdDemande);
            if (! $campagne) {
                throw new InvalidArgumentException('Campagne non reconnue ou non ouverte pour votre agence.');
            }
        } else {
            $campagne = $ouvertes->first();
            if ($campagneIdDemande && (int) $campagne->id !== $campagneIdDemande) {
                throw new InvalidArgumentException('Campagne non reconnue ou non ouverte pour votre agence.');
            }
        }

        if (! $campagne->estOuverteAuxVentes($agenceId)) {
            throw new InvalidArgumentException(
                'Cette campagne n’accepte pas les ventes pour votre agence pour le moment.'
            );
        }

        $stock = Stock::where('agence_id', $agenceId)
            ->where('type_carte_id', $typeCarteId)
            ->first();

        $montant = (int) $typeCarte->prix;
        if ($campagne->estActivePourPrimes($agenceId) && $campagne->remiseSappliqueAuType($typeCarteId)) {
            $montant = $campagne->montantApresRemise((int) $typeCarte->prix);
        }

        return DB::transaction(function () use ($data, $user, $agenceId, $typeCarteId, $typeCarte, $stock, $montant, $campagne) {
            $client = Client::create([
                'prenom' => $data['prenom'],
                'nom' => $data['nom'],
                'telephone' => $data['telephone'] ?? null,
                'ville' => $data['ville'] ?? null,
                'quartier' => $data['quartier'] ?? null,
                'type_carte_id' => $typeCarteId,
                'statut_carte' => 'vendue',
                'carte_identite' => $data['carte_identite'] ?? null,
                'user_id' => $user->id,
            ]);

            $vente = Vente::create([
                'client_id' => $client->id,
                'user_id' => $user->id,
                'agence_id' => $agenceId,
                'campagne_id' => $campagne->id,
                'type_carte_id' => $typeCarteId,
                'montant' => $montant,
                'statut_activation' => 'vendue',
            ]);

            if ($stock && $stock->quantite >= 1) {
                $stock->decrement('quantite');
                MouvementStock::create([
                    'agence_id' => $agenceId,
                    'type_carte_id' => $typeCarteId,
                    'quantite' => -1,
                    'type_mouvement' => 'vente',
                    'vente_id' => $vente->id,
                ]);
            }

            return $vente->load(['client', 'agence', 'typeCarte', 'campagne']);
        });
    }
}
