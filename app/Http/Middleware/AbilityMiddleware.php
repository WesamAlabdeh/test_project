<?php

namespace App\Http\Middleware;

use App\Exceptions\Errors;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AbilityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $ability): Response
    {
        $user = Auth::user();
        $token = $user->currentAccessToken();
        if ($token->can($ability)) {
            return $next($request);
        }
        Errors::NotAuthorized();
    }
}
