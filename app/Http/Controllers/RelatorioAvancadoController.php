<?php

namespace App\Http\Controllers;

use App\Models\Viagem;
use App\Services\ViagemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioAvancadoController extends Controller
{
    protected $viagemService;

    public function __construct(ViagemService $viagemService)
    {
        $this->viagemService = $viagemService;
    }

    /**
     * Dashboard com estatísticas gerais
     */
    public function dashboard()
    {
        $user = auth()->user();
        $mesAtual = Carbon::now();
        
        // Estatísticas do mês atual
        $statsMesAtual = $this->viagemService->generateMonthlyReport(
            $user->id, 
            $mesAtual->month, 
            $mesAtual->year
        );

        // Estatísticas do mês anterior
        $mesAnterior = $mesAtual->copy()->subMonth();
        $statsMesAnterior = $this->viagemService->generateMonthlyReport(
            $user->id, 
            $mesAnterior->month, 
            $mesAnterior->year
        );

        // Dados para gráficos
        $viagensPorMes = $this->getViagensPorMes($user->id);
        $kmPorMes = $this->getKmPorMes($user->id);
        $destinosFrequentes = $this->getDestinosFrequentes($user->id);

        return view('relatorios.dashboard', compact(
            'statsMesAtual', 
            'statsMesAnterior', 
            'viagensPorMes', 
            'kmPorMes', 
            'destinosFrequentes'
        ));
    }

    /**
     * Relatório detalhado por período
     */
    public function relatorio(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'formato' => 'in:html,pdf,excel'
        ]);

        $dataInicio = Carbon::parse($request->data_inicio);
        $dataFim = Carbon::parse($request->data_fim);
        $user = auth()->user();

        $viagens = Viagem::where('user_id', $user->id)
            ->whereBetween('data', [$dataInicio, $dataFim])
            ->orderBy('data', 'desc')
            ->get();

        // Calcular totais e estatísticas
        $stats = $this->calculatePeriodStats($viagens);

        $data = compact('viagens', 'stats', 'dataInicio', 'dataFim', 'user');

        switch ($request->formato) {
            case 'pdf':
                $pdf = Pdf::loadView('relatorios.pdf', $data);
                return $pdf->download("relatorio_viagens_{$dataInicio->format('Y-m-d')}_{$dataFim->format('Y-m-d')}.pdf");
            
            case 'excel':
                return $this->exportToExcel($viagens, $stats);
            
            default:
                return view('relatorios.detalhado', $data);
        }
    }

    /**
     * Relatório comparativo por usuários (admin only)
     */
    public function comparativo(Request $request)
    {
        $this->authorize('admin');

        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        $dataInicio = Carbon::parse($request->data_inicio);
        $dataFim = Carbon::parse($request->data_fim);

        $stats = DB::table('viagens')
            ->join('users', 'viagens.user_id', '=', 'users.id')
            ->select([
                'users.name',
                'users.email',
                DB::raw('COUNT(viagens.id) as total_viagens'),
                DB::raw('SUM(CASE WHEN viagens.km_chegada IS NOT NULL AND viagens.km_saida IS NOT NULL 
                         THEN viagens.km_chegada - viagens.km_saida ELSE 0 END) as total_km'),
                DB::raw('AVG(CASE WHEN viagens.km_chegada IS NOT NULL AND viagens.km_saida IS NOT NULL 
                         THEN viagens.km_chegada - viagens.km_saida ELSE NULL END) as media_km'),
                DB::raw('MIN(viagens.data) as primeira_viagem'),
                DB::raw('MAX(viagens.data) as ultima_viagem')
            ])
            ->whereBetween('viagens.data', [$dataInicio, $dataFim])
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_viagens', 'desc')
            ->get();

        return view('relatorios.comparativo', compact('stats', 'dataInicio', 'dataFim'));
    }

    /**
     * Métodos auxiliares
     */
    private function getViagensPorMes(int $userId, int $meses = 12): array
    {
        $dados = DB::table('viagens')
            ->select([
                DB::raw('YEAR(data) as ano'),
                DB::raw('MONTH(data) as mes'),
                DB::raw('COUNT(*) as total')
            ])
            ->where('user_id', $userId)
            ->where('data', '>=', Carbon::now()->subMonths($meses))
            ->groupBy('ano', 'mes')
            ->orderBy('ano')
            ->orderBy('mes')
            ->get();

        return $dados->map(function($item) {
            return [
                'periodo' => Carbon::create($item->ano, $item->mes)->format('M/Y'),
                'total' => $item->total
            ];
        })->toArray();
    }

    private function getKmPorMes(int $userId, int $meses = 12): array
    {
        $dados = DB::table('viagens')
            ->select([
                DB::raw('YEAR(data) as ano'),
                DB::raw('MONTH(data) as mes'),
                DB::raw('SUM(CASE WHEN km_chegada IS NOT NULL AND km_saida IS NOT NULL 
                         THEN km_chegada - km_saida ELSE 0 END) as total_km')
            ])
            ->where('user_id', $userId)
            ->where('data', '>=', Carbon::now()->subMonths($meses))
            ->groupBy('ano', 'mes')
            ->orderBy('ano')
            ->orderBy('mes')
            ->get();

        return $dados->map(function($item) {
            return [
                'periodo' => Carbon::create($item->ano, $item->mes)->format('M/Y'),
                'total_km' => $item->total_km ?: 0
            ];
        })->toArray();
    }

    private function getDestinosFrequentes(int $userId, int $limite = 10): array
    {
        return DB::table('viagens')
            ->select(['destino', DB::raw('COUNT(*) as total')])
            ->where('user_id', $userId)
            ->where('data', '>=', Carbon::now()->subMonths(6))
            ->groupBy('destino')
            ->orderBy('total', 'desc')
            ->limit($limite)
            ->pluck('total', 'destino')
            ->toArray();
    }

    private function calculatePeriodStats($viagens): array
    {
        $totalViagens = $viagens->count();
        $totalKm = $viagens->sum(function($v) {
            return ($v->km_chegada && $v->km_saida) ? $v->km_chegada - $v->km_saida : 0;
        });

        $viagensComKm = $viagens->filter(function($v) {
            return $v->km_chegada && $v->km_saida;
        });

        return [
            'total_viagens' => $totalViagens,
            'total_km' => $totalKm,
            'media_km_viagem' => $viagensComKm->count() > 0 ? round($totalKm / $viagensComKm->count(), 2) : 0,
            'destinos_unicos' => $viagens->pluck('destino')->unique()->count(),
            'condutores_unicos' => $viagens->pluck('condutor')->unique()->count(),
            'maior_distancia' => $viagensComKm->max(function($v) {
                return $v->km_chegada - $v->km_saida;
            }) ?: 0,
            'menor_distancia' => $viagensComKm->min(function($v) {
                return $v->km_chegada - $v->km_saida;
            }) ?: 0
        ];
    }
}
