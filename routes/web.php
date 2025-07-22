
<?php

// Dashboard Analytics para AJAX autenticado via sessão
Route::middleware(['auth'])->get('/analytics/dashboard', [\App\Http\Controllers\AnalyticsController::class, 'getDashboardData'])->name('web.analytics.dashboard');

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ViagemController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\SugestaoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminSugestaoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

// ============================================================================
// ROTAS PÚBLICAS
// ============================================================================

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

Route::get('/offline', function () {
    return view('offline');
})->name('offline');

// ============================================================================
// ROTAS DE AUTENTICAÇÃO
// ============================================================================

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// ============================================================================
// ROTAS AUTENTICADAS
// ============================================================================

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/analytics', function () {
        return view('dashboard-analytics');
    })->name('dashboard.analytics');

    // Perfil do usuário
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Viagens
    Route::resource('viagens', ViagemController::class);
    Route::get('/viagens/export/pdf', [ViagemController::class, 'exportarPdf'])->name('viagens.export.pdf');

    // Relatórios
    Route::prefix('relatorios')->name('relatorios.')->group(function () {
        Route::get('/', [RelatorioController::class, 'index'])->name('index');
        Route::get('/viagens/pdf', [RelatorioController::class, 'viagensPdf'])->name('viagens.pdf');
        Route::get('/viagens/excel', [RelatorioController::class, 'viagensExcel'])->name('viagens.excel');
    });

    // Sugestões (com rate limiting)
    Route::post('/sugestoes', [SugestaoController::class, 'store'])
        ->middleware('throttle:sugestoes')
        ->name('sugestoes.store');
});

// ============================================================================
// ROTAS ADMINISTRATIVAS
// ============================================================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Gerenciamento de usuários
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])
        ->name('users.reset-password');
    Route::patch('users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])
        ->name('users.toggle-admin');

    // Gerenciamento de sugestões
    Route::resource('sugestoes', AdminSugestaoController::class)->only(['index', 'destroy']);
    Route::post('sugestoes/{id}/responder', [AdminSugestaoController::class, 'responder'])
        ->name('sugestoes.responder');
});

// ============================================================================
// ROTAS DE TROCA DE SENHA OBRIGATÓRIA
// ============================================================================

Route::middleware(['auth', 'forcar_troca_senha'])->prefix('senha')->name('senha.')->group(function () {
    Route::get('/alterar', function() {
        return view('auth.trocar_senha');
    })->name('alterar');
    
    Route::post('/alterar', function(Illuminate\Http\Request $request) {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
            'precisa_trocar_senha' => false,
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Senha alterada com sucesso!');
    })->name('update');
});

// ============================================================================
// INCLUDES DE ROTAS EXTERNAS
// ============================================================================

require __DIR__.'/auth.php';

// Incluir rotas de debug apenas em desenvolvimento
if (app()->environment(['local', 'testing'])) {
    require __DIR__.'/debug.php';
}
