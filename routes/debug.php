<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/debug-user', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return response()->json([
            'logged_in' => true,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
            'is_admin_bool' => (bool) $user->is_admin,
        ]);
    } else {
        return response()->json([
            'logged_in' => false,
            'message' => 'Usuário não está logado'
        ]);
    }
});

// Rotas de teste CSRF
Route::get('/test-csrf', function () {
    return view('test-csrf');
})->name('test.csrf.page');

Route::post('/test-csrf', function (Illuminate\Http\Request $request) {
    return redirect()->back()->with('test_result', 'Formulário CSRF funcionando! Dados: ' . $request->teste);
})->name('test.csrf');

Route::post('/test-ajax', function (Illuminate\Http\Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'AJAX CSRF funcionando! Dados: ' . $request->test,
        'token' => csrf_token(),
        'session_id' => session()->getId()
    ]);
})->name('test.ajax');
