<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Viagem;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $now = Carbon::now();
        $labels = [];
        $data = [];

        // Gera os últimos 12 meses
        for ($i = 11; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $labels[] = $date->format('m/Y');
            $data[] = Viagem::where('user_id', $userId)
                ->whereYear('data', $date->year)
                ->whereMonth('data', $date->month)
                ->count();
        }

        // Dados já usados no dashboard
        $totalViagens = Viagem::where('user_id', $userId)->count();
        $ultimaViagem = Viagem::where('user_id', $userId)->orderByDesc('data')->orderByDesc('created_at')->first();

        return view('dashboard', compact('labels', 'data', 'totalViagens', 'ultimaViagem'));
    }
}
