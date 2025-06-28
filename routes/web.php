<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ViagemController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\SugestaoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/dashboard/analytics', function () {
    return view('dashboard-analytics');
})->middleware(['auth'])->name('dashboard.analytics');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('viagens', ViagemController::class);

// Rota para exportar viagens em PDF
Route::get('/viagens/pdf', [App\Http\Controllers\ViagemController::class, 'exportarPdf'])->name('viagens.pdf');

Route::middleware(['auth'])->group(function () {
    Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::get('/relatorios/viagens/pdf', [RelatorioController::class, 'viagensPdf'])->name('relatorios.viagens.pdf');
    Route::get('/relatorios/viagens/excel', [RelatorioController::class, 'viagensExcel'])->name('relatorios.viagens.excel');
});

// Sugestões com rate limiting
Route::post('/sugestoes', [SugestaoController::class, 'store'])
    ->middleware(['auth', 'throttle:sugestoes'])
    ->name('sugestoes.store');

// Sugestões/Admin Sugestões com middlewares aprimorados
Route::middleware(['auth', 'admin', 'log.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', App\Http\Controllers\AdminUserController::class);
    Route::post('users/{user}/reset-password', [App\Http\Controllers\AdminUserController::class, 'resetPassword'])->name('users.reset-password');
    Route::patch('users/{user}/toggle-admin', [App\Http\Controllers\AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::post('users/{user}/toggle-admin', [App\Http\Controllers\AdminUserController::class, 'toggleAdmin']);
    Route::resource('sugestoes', App\Http\Controllers\AdminSugestaoController::class)->only(['index', 'destroy']);
    Route::post('sugestoes/{id}/responder', [App\Http\Controllers\AdminSugestaoController::class, 'responder'])->name('sugestoes.responder');
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rotas de gerenciamento de usuários (admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('users', [App\Http\Controllers\AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{id}/edit', [App\Http\Controllers\AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('users/{id}', [App\Http\Controllers\AdminUserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [App\Http\Controllers\AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('users/{id}/reset', [App\Http\Controllers\AdminUserController::class, 'resetPassword'])->name('users.reset');
});

Route::middleware(['auth', 'forcar_troca_senha'])->group(function () {
    Route::get('senha/alterar', function() {
        return view('auth.trocar_senha');
    })->name('senha.alterar');
    Route::post('senha/alterar', function(Illuminate\Http\Request $request) {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->precisa_trocar_senha = false;
        $user->save();
        return redirect()->route('dashboard')->with('success', 'Senha alterada com sucesso!');
    });
});

// Removido Auth::routes(['verify' => true]); pois não está usando laravel/ui

require __DIR__.'/auth.php';
require __DIR__.'/debug.php';
