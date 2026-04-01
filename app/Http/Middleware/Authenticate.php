<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        // Pour les requêtes AJAX/API, on ne redirige pas
        if ($request->expectsJson()) {
            return null;
        }

        // Pour les requêtes web, on redirige vers la page d'accueil
        return '/';
    }
}
