<?php

namespace App\Http\Middleware;

use App\Models\Campagne;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'prenom' => $user->prenom,
                    'role' => $user->role,
                    'agence_id' => $user->agence_id,
                    'is_admin' => $user->isAdmin(),
                    'is_direction' => $user->isDirection(),
                    'is_commercial' => $user->isCommercial(),
                    'is_commercial_telephonique' => $user->isCommercialTelephonique(),
                    'peut_vendre' => $user->isCommercial() && $this->campagneOuverteEngagee($user, Campagne::TYPE_VENTE_CARTE),
                    'peut_enroler' => $user->isCommercial() && $this->campagneOuverteEngagee($user, Campagne::TYPE_ENROLEMENT_APP),
                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'status' => fn () => $request->session()->get('status'),
                'success_article' => fn () => $request->session()->get('success_article'),
            ],
        ];
    }

    /** Le commercial a-t-il, en ce moment, au moins une campagne ouverte de ce type où il est réellement engagé ? */
    private function campagneOuverteEngagee(User $user, string $type): bool
    {
        if (! $user->agence_id) {
            return false;
        }

        return Campagne::getActivesPourAgence((int) $user->agence_id)
            ->where('type', $type)
            ->contains(fn (Campagne $c) => $c->estEngageCommercial($user->id));
    }
}
