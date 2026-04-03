<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompteActif
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && in_array($user->role, ['commercial', 'commercial_telephonique', 'direction'], true) && ! $user->actif) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte est désactivé. Contactez l\'administration.',
            ]);
        }

        return $next($request);
    }
}
