<?php

namespace App\Http\Controllers;

use App\Models\Viagem;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ViagensExport;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        // Se não for admin, só pode ver as próprias viagens
        if (!$user->isAdmin()) {
            $request->merge(['usuario' => $user->name]);
            $usuarios = collect([$user]);
        } else {
            $usuarios = User::orderBy('name')->get();
        }
        // Buscar tipos de veículo e placas distintos para os filtros
        $tipos_veiculo = Viagem::select('tipo_veiculo')->distinct()->pluck('tipo_veiculo')->filter()->values();
        $placas = Viagem::select('placa')->distinct()->pluck('placa')->filter()->values();
        $viagens = collect();
        if ($request->usuario || $request->data_inicio || $request->data_fim || $request->tipo_veiculo || $request->placa) {
            $viagens = Viagem::with('user')
                ->when($request->usuario, function($q) use ($request) { return $q->where('condutor', $request->usuario); })
                ->when($request->data_inicio, function($q) use ($request) { return $q->where('data', '>=', $request->data_inicio); })
                ->when($request->data_fim, function($q) use ($request) { return $q->where('data', '<=', $request->data_fim); })
                ->when($request->tipo_veiculo, function($q) use ($request) { return $q->where('tipo_veiculo', $request->tipo_veiculo); })
                ->when($request->placa, function($q) use ($request) { return $q->where('placa', $request->placa); })
                ->get();
        }
        return view('relatorios.index', compact('usuarios', 'viagens', 'tipos_veiculo', 'placas'));
    }

    public function viagensPdf(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            $request->merge(['usuario' => $user->name]);
        }
        $viagens = Viagem::with('user')
            ->when($request->usuario, function($q) use ($request) { return $q->where('condutor', $request->usuario); })
            ->when($request->data_inicio, function($q) use ($request) { return $q->where('data', '>=', $request->data_inicio); })
            ->when($request->data_fim, function($q) use ($request) { return $q->where('data', '<=', $request->data_fim); })
            ->when($request->tipo_veiculo, function($q) use ($request) { return $q->where('tipo_veiculo', $request->tipo_veiculo); })
            ->when($request->placa, function($q) use ($request) { return $q->where('placa', $request->placa); })
            ->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('relatorios.viagens', compact('viagens'));
        $pdf->setPaper('a4', 'landscape');
        if ($request->action === 'download') {
            return $pdf->download('relatorio_viagens.pdf');
        }
        return $pdf->stream('relatorio_viagens.pdf');
    }

    public function viagensExcel(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            $request->merge(['usuario' => $user->name]);
        }
        $viagens = Viagem::with('user')
            ->when($request->usuario, function($q) use ($request) { return $q->where('condutor', $request->usuario); })
            ->when($request->data_inicio, function($q) use ($request) { return $q->where('data', '>=', $request->data_inicio); })
            ->when($request->data_fim, function($q) use ($request) { return $q->where('data', '<=', $request->data_fim); })
            ->when($request->tipo_veiculo, function($q) use ($request) { return $q->where('tipo_veiculo', $request->tipo_veiculo); })
            ->when($request->placa, function($q) use ($request) { return $q->where('placa', $request->placa); })
            ->get();
        return Excel::download(new ViagensExport($viagens), 'relatorio_viagens.xlsx');
    }
}
