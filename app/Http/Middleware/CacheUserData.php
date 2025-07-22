<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class CacheUserData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            
            // Cache dados básicos do usuário por 1 hora
            $user = Cache::remember("user_data_{$userId}", 3600, function () {
                $currentUser = Auth::user();
                return [
                    'id' => $currentUser->id,
                    'name' => $currentUser->name,
                    'email' => $currentUser->email,
                    'is_admin' => $currentUser->is_admin,
                    'precisa_trocar_senha' => $currentUser->precisa_trocar_senha
                ];
            });
            
            // Disponibiliza dados em cache para a view
            view()->share('cached_user', $user);
        }

        return $next($request);
    }
}
