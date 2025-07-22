<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas de Analytics protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    // Rota de teste simples
    Route::get('/analytics/test', function () {
        return response()->json(['status' => 'success', 'message' => 'API funcionando', 'user' => auth()->user()->name]);
    })->name('api.analytics.test');
    Route::get('/analytics/notifications', [AnalyticsController::class, 'getNotifications'])->name('api.analytics.notifications');
});

// Rotas de Notificações Push
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/vapid-key', [NotificationController::class, 'getVapidKey'])->name('api.notifications.vapid-key');
    Route::post('/notifications/subscribe', [NotificationController::class, 'subscribe'])->name('api.notifications.subscribe');
    Route::post('/notifications/unsubscribe', [NotificationController::class, 'unsubscribe'])->name('api.notifications.unsubscribe');
    Route::post('/notifications/test', [NotificationController::class, 'testNotification'])->name('api.notifications.test');
});
