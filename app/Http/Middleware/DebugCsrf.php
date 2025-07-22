<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugCsrf
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
        // Log informações de debug para CSRF
        Log::info('CSRF Debug', [
            'url' => $request->url(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'csrf_token' => $request->header('X-CSRF-TOKEN'),
            'csrf_session' => session()->token(),
            'session_id' => session()->getId(),
            'cookies' => $request->cookies->all(),
            'has_csrf_header' => $request->hasHeader('X-CSRF-TOKEN'),
            'csrf_from_input' => $request->input('_token'),
        ]);

        return $next($request);
    }
}
