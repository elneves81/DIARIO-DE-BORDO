<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogAdminActions
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
        $response = $next($request);

        // Log apenas ações de admin que modificam dados
        if (Auth::check() && Auth::user()->is_admin && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $user = Auth::user();
            $action = $request->method() . ' ' . $request->path();
            $data = $request->except(['password', 'password_confirmation', '_token', '_method']);
            
            Log::channel('admin')->info('Admin Action', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'action' => $action,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => $data,
                'timestamp' => now()->toDateTimeString()
            ]);
        }

        return $response;
    }
}
