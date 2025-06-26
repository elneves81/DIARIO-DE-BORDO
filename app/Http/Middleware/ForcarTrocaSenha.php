<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ForcarTrocaSenha
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->precisa_trocar_senha) {
            if (!$request->is('senha/alterar') && !$request->is('logout')) {
                return redirect()->route('senha.alterar');
            }
        }
        return $next($request);
    }
}
