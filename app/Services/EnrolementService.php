<?php

namespace App\Services;

use App\Models\Campagne;
use App\Models\EnrolementClient;
use App\Models\User;
use InvalidArgumentException;

class EnrolementService
{
    public function enregistrerEnrolement(array $data, int $userId): EnrolementClient
    {
        $user = User::findOrFail($userId);

        if ($user->role !== 'commercial' || ! $user->agence_id) {
            throw new InvalidArgumentException('Seul un commercial avec une agence peut enregistrer un enrôlement.');
        }

        if (! $user->actif) {
            throw new InvalidArgumentException('Compte commercial désactivé. Vous ne pouvez pas enregistrer d’enrôlement.');
        }

        $agenceId = (int) $user->agence_id;

        Campagne::syncStatuts();
        $ouvertes = Campagne::getActivesPourAgence($agenceId)
            ->where('type', Campagne::TYPE_ENROLEMENT_APP)
            ->filter(fn (Campagne $c) => $c->estEngageCommercial($user->id))
            ->values();
        if ($ouvertes->isEmpty()) {
            throw new InvalidArgumentException(
                'Aucune campagne d’enrôlement en cours pour votre agence.'
            );
        }

        $campagneIdDemande = isset($data['campagne_id']) ? (int) $data['campagne_id'] : null;
        $campagne = null;
        if ($ouvertes->count() > 1) {
            if (! $campagneIdDemande) {
                throw new InvalidArgumentException(
                    'Plusieurs campagnes d’enrôlement sont ouvertes : indiquez la campagne (sélection sur le formulaire).'
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

        if (! $campagne->estOuverte($agenceId)) {
            throw new InvalidArgumentException(
                'Cette campagne n’accepte pas d’enrôlements pour votre agence pour le moment.'
            );
        }

        if (! $campagne->commercialAAccepteContrat($user->id)) {
            throw new InvalidArgumentException(
                'Vous devez d’abord accepter le contrat de prestation de la campagne « '.$campagne->nom.' » (rubrique « Mon contrat ») avant de pouvoir enregistrer un enrôlement.'
            );
        }

        return EnrolementClient::create([
            'campagne_id' => $campagne->id,
            'user_id' => $user->id,
            'agence_id' => $agenceId,
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'telephone' => $data['telephone'] ?? null,
            'adresse' => $data['adresse'] ?? null,
        ])->load(['agence', 'campagne']);
    }
}
