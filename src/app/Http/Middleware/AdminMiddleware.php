<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o usuário está logado
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar esta área.');
        }

        // Verificar se o usuário é admin
        if (! Auth::user()->is_admin) {
            if (request()->expectsJson()) {
                abort(403, 'Acesso negado. Apenas administradores podem acessar esta área.');
            }

            return redirect()->route('dashboard')->with('error', 'Acesso negado. Apenas administradores podem acessar esta área.');
        }

        return $next($request);
    }
}
