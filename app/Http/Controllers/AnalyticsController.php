<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Viagem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Obter dados do dashboard analytics
     */
    public function getDashboardData(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->is_admin;
        
        // Query base - admin vê tudo, usuário comum só suas viagens
        $viagensQuery = $isAdmin ? Viagem::query() : Viagem::where('user_id', $user->id);
        
        // Período de análise (padrão: últimos 12 meses)
        $startDate = $request->get('start_date', Carbon::now()->subMonths(12));
        $endDate = $request->get('end_date', Carbon::now());
        
        $viagensQuery->whereBetween('created_at', [$startDate, $endDate]);
        
        // KPIs Principais
        $totalViagens = $viagensQuery->count();
        $totalDistancia = $viagensQuery->sum('distancia_km') ?? 0;
        $totalGastos = $viagensQuery->sum('valor_total') ?? 0;
        $duracaoMedia = $this->calcularDuracaoMedia($viagensQuery->get());
        $taxaAprovacao = $this->calcularTaxaAprovacao($viagensQuery->get());
        $crescimentoMensal = $this->calcularCrescimentoMensal($viagensQuery);
        
        // Dados para gráficos
        $statusData = $this->getStatusData($viagensQuery);
        $monthlyData = $this->getMonthlyData($viagensQuery, $startDate, $endDate);
        $destinationsData = $this->getDestinationsData($viagensQuery);
        $expensesData = $this->getExpensesData($viagensQuery);
        $timelineData = $this->getTimelineData($viagensQuery, $startDate, $endDate);
        
        // Atividade recente
        $atividadeRecente = $this->getAtividadeRecente($user, $isAdmin);
        
        return response()->json([
            'kpis' => [
                'totalTrips' => $totalViagens,
                'totalDistance' => $totalDistancia,
                'totalExpenses' => $totalGastos,
                'avgTripDuration' => $duracaoMedia,
                'approvalRate' => $taxaAprovacao,
                'monthlyGrowth' => $crescimentoMensal
            ],
            'charts' => [
                'statusData' => $statusData,
                'monthlyData' => $monthlyData,
                'destinationsData' => $destinationsData,
                'expensesData' => $expensesData,
                'timelineData' => $timelineData
            ],
            'recentActivity' => $atividadeRecente,
            'lastUpdated' => Carbon::now()->toISOString()
        ]);
    }
    
    /**
     * Dados de status das viagens
     */
    private function getStatusData($viagensQuery)
    {
        $statusCounts = $viagensQuery->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        return [
            'approved' => $statusCounts['aprovado'] ?? 0,
            'pending' => $statusCounts['pendente'] ?? 0,
            'rejected' => $statusCounts['rejeitado'] ?? 0,
            'draft' => $statusCounts['rascunho'] ?? 0
        ];
    }
    
    /**
     * Dados mensais de viagens
     */
    private function getMonthlyData($viagensQuery, $startDate, $endDate)
    {
        $monthlyStats = $viagensQuery->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total'),
                DB::raw('sum(case when status = "aprovado" then 1 else 0 end) as aprovadas'),
                DB::raw('sum(case when status = "pendente" then 1 else 0 end) as pendentes')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        $labels = [];
        $realizadas = [];
        $pendentes = [];
        
        foreach ($monthlyStats as $stat) {
            $labels[] = Carbon::createFromDate($stat->year, $stat->month, 1)->format('M');
            $realizadas[] = $stat->aprovadas;
            $pendentes[] = $stat->pendentes;
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Viagens Realizadas',
                    'data' => $realizadas,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)'
                ],
                [
                    'label' => 'Viagens Pendentes',
                    'data' => $pendentes,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.8)'
                ]
            ]
        ];
    }
    
    /**
     * Destinos mais visitados
     */
    private function getDestinationsData($viagensQuery)
    {
        $destinations = $viagensQuery->select('destino', DB::raw('count(*) as total'))
            ->where('status', 'aprovado')
            ->groupBy('destino')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        return [
            'labels' => $destinations->pluck('destino')->toArray(),
            'data' => $destinations->pluck('total')->toArray()
        ];
    }
    
    /**
     * Gastos por categoria
     */
    private function getExpensesData($viagensQuery)
    {
        // Simulando categorias de gastos - adapte conforme sua estrutura
        $totalGastos = $viagensQuery->where('status', 'aprovado')->sum('valor_total');
        
        if ($totalGastos == 0) {
            return [
                'labels' => ['Sem dados'],
                'data' => [100]
            ];
        }
        
        // Estimativa baseada em padrões típicos
        return [
            'labels' => ['Hospedagem', 'Transporte', 'Alimentação', 'Combustível', 'Outros'],
            'data' => [35, 25, 20, 15, 5] // Percentuais
        ];
    }
    
    /**
     * Dados da linha temporal
     */
    private function getTimelineData($viagensQuery, $startDate, $endDate)
    {
        $monthlyTotals = $viagensQuery->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        $labels = [];
        $monthly = [];
        $cumulative = [];
        $runningTotal = 0;
        
        foreach ($monthlyTotals as $stat) {
            $labels[] = Carbon::createFromDate($stat->year, $stat->month, 1)->format('M');
            $monthly[] = $stat->total;
            $runningTotal += $stat->total;
            $cumulative[] = $runningTotal;
        }
        
        return [
            'labels' => $labels,
            'cumulative' => $cumulative,
            'monthly' => $monthly
        ];
    }
    
    /**
     * Calcular duração média das viagens
     */
    private function calcularDuracaoMedia($viagens)
    {
        if ($viagens->count() == 0) return 0;
        
        $totalDias = 0;
        $viagensComDuracao = 0;
        
        foreach ($viagens as $viagem) {
            if ($viagem->data_fim && $viagem->data_inicio) {
                $inicio = Carbon::parse($viagem->data_inicio);
                $fim = Carbon::parse($viagem->data_fim);
                $totalDias += $inicio->diffInDays($fim) + 1;
                $viagensComDuracao++;
            }
        }
        
        return $viagensComDuracao > 0 ? round($totalDias / $viagensComDuracao, 1) : 0;
    }
    
    /**
     * Calcular taxa de aprovação
     */
    private function calcularTaxaAprovacao($viagens)
    {
        $total = $viagens->count();
        if ($total == 0) return 0;
        
        $aprovadas = $viagens->where('status', 'aprovado')->count();
        return round(($aprovadas / $total) * 100, 1);
    }
    
    /**
     * Calcular crescimento mensal
     */
    private function calcularCrescimentoMensal($viagensQuery)
    {
        $mesAtual = $viagensQuery->clone()
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        $mesAnterior = $viagensQuery->clone()
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
        
        if ($mesAnterior == 0) return $mesAtual > 0 ? 100 : 0;
        
        return round((($mesAtual - $mesAnterior) / $mesAnterior) * 100, 1);
    }
    
    /**
     * Obter atividade recente
     */
    private function getAtividadeRecente($user, $isAdmin)
    {
        $viagensRecentes = Viagem::when(!$isAdmin, function($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        return $viagensRecentes->map(function($viagem) {
            $tempoDecorrido = Carbon::parse($viagem->updated_at)->diffForHumans();
            
            $icone = match($viagem->status) {
                'aprovado' => 'bg-success',
                'pendente' => 'bg-warning',
                'rejeitado' => 'bg-danger',
                default => 'bg-info'
            };
            
            $acao = match($viagem->status) {
                'aprovado' => 'aprovada',
                'pendente' => 'criada/atualizada',
                'rejeitado' => 'rejeitada',
                default => 'em análise'
            };
            
            return [
                'icon' => $icone,
                'title' => "Viagem para {$viagem->destino} {$acao}",
                'subtitle' => $tempoDecorrido,
                'user' => $viagem->user->name ?? 'Sistema'
            ];
        });
    }
    
    /**
     * Notificações para o usuário
     */
    public function getNotifications(Request $request)
    {
        $user = auth()->user();
        
        // Verificar viagens com mudança de status recente
        $viagensComMudanca = Viagem::where('user_id', $user->id)
            ->where('updated_at', '>=', Carbon::now()->subHours(24))
            ->where('status', '!=', 'rascunho')
            ->get();
        
        $notificacoes = [];
        
        foreach ($viagensComMudanca as $viagem) {
            $notificacoes[] = [
                'id' => 'viagem_' . $viagem->id,
                'title' => 'Status da Viagem Atualizado',
                'body' => "Sua viagem para {$viagem->destino} foi {$viagem->status}",
                'icon' => '/img/icon-192x192.png',
                'data' => [
                    'url' => route('viagens.show', $viagem->id),
                    'viagem_id' => $viagem->id
                ],
                'timestamp' => $viagem->updated_at->toISOString()
            ];
        }
        
        return response()->json($notificacoes);
    }
}
