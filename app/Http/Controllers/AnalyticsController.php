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
        try {
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
            $totalDistancia = $this->calcularDistanciaTotal($viagensQuery->get());
            $totalGastos = $this->calcularGastosTotal($viagensQuery->get());
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
        
        } catch (\Exception $e) {
            \Log::error('Erro no getDashboardData: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro interno do servidor',
                'message' => 'Não foi possível carregar os dados do dashboard.',
                'kpis' => [
                    'totalTrips' => 0,
                    'totalDistance' => 0,
                    'totalExpenses' => 0,
                    'avgTripDuration' => 0,
                    'approvalRate' => 0,
                    'monthlyGrowth' => 0
                ],
                'charts' => [
                    'statusData' => ['approved' => 0, 'pending' => 0, 'rejected' => 0, 'draft' => 0],
                    'monthlyData' => [],
                    'destinationsData' => [],
                    'expensesData' => [],
                    'timelineData' => []
                ],
                'recentActivity' => [],
                'lastUpdated' => Carbon::now()->toISOString()
            ], 500);
        }
    }
    
    /**
     * Dados de status das viagens
     */
    private function getStatusData($viagensQuery)
    {
        $viagens = $viagensQuery->get();
        $statusCounts = [
            'concluida' => 0,
            'em_andamento' => 0,
            'agendada' => 0
        ];
        
        foreach ($viagens as $viagem) {
            $status = $viagem->status;
            if (isset($statusCounts[$status])) {
                $statusCounts[$status]++;
            }
        }
        
        return [
            'approved' => $statusCounts['concluida'],
            'pending' => $statusCounts['em_andamento'],
            'rejected' => 0, // Não há status rejeitado no modelo atual
            'draft' => $statusCounts['agendada']
        ];
    }
    
    /**
     * Dados mensais de viagens
     */
    private function getMonthlyData($viagensQuery, $startDate, $endDate)
    {
        // Usar dados simplificados baseados no modelo atual
        $viagens = $viagensQuery->get();
        $monthlyData = [];
        
        foreach ($viagens as $viagem) {
            $month = Carbon::parse($viagem->created_at)->format('M');
            if (!isset($monthlyData[$month])) {
                $monthlyData[$month] = ['total' => 0, 'concluidas' => 0, 'pendentes' => 0];
            }
            $monthlyData[$month]['total']++;
            
            if ($viagem->status === 'concluida') {
                $monthlyData[$month]['concluidas']++;
            } else {
                $monthlyData[$month]['pendentes']++;
            }
        }
        
        $labels = array_keys($monthlyData);
        $realizadas = array_column($monthlyData, 'concluidas');
        $pendentes = array_column($monthlyData, 'pendentes');
        
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
        $viagens = $viagensQuery->get();
        $destinations = [];
        
        foreach ($viagens as $viagem) {
            if ($viagem->status === 'concluida' && $viagem->destino) {
                if (!isset($destinations[$viagem->destino])) {
                    $destinations[$viagem->destino] = 0;
                }
                $destinations[$viagem->destino]++;
            }
        }
        
        // Ordenar por quantidade e pegar os top 5
        arsort($destinations);
        $destinations = array_slice($destinations, 0, 5, true);
        
        if (empty($destinations)) {
            return [
                'labels' => ['Sem dados'],
                'data' => [1]
            ];
        }
        
        return [
            'labels' => array_keys($destinations),
            'data' => array_values($destinations)
        ];
    }
    
    /**
     * Gastos por categoria
     */
    private function getExpensesData($viagensQuery)
    {
        // Usar dados simplificados - não há campo valor_total no modelo atual
        $viagens = $viagensQuery->get();
        $totalGastos = 0;
        
        foreach ($viagens as $viagem) {
            if ($viagem->quantidade_abastecida) {
                $totalGastos += $viagem->quantidade_abastecida * 5.50;
            }
        }
        
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
     * Calcular distância total
     */
    private function calcularDistanciaTotal($viagens)
    {
        $totalDistancia = 0;
        
        foreach ($viagens as $viagem) {
            if ($viagem->distancia_percorrida) {
                $totalDistancia += $viagem->distancia_percorrida;
            }
        }
        
        return $totalDistancia;
    }
    
    /**
     * Calcular gastos total (simulado baseado em combustível)
     */
    private function calcularGastosTotal($viagens)
    {
        $totalGastos = 0;
        
        foreach ($viagens as $viagem) {
            if ($viagem->quantidade_abastecida) {
                // Estimativa: R$ 5,50 por litro
                $totalGastos += $viagem->quantidade_abastecida * 5.50;
            }
        }
        
        return round($totalGastos, 2);
    }
    
    /**
     * Calcular duração média das viagens
     */
    private function calcularDuracaoMedia($viagens)
    {
        if ($viagens->count() == 0) return 0;
        
        $totalHoras = 0;
        $viagensComDuracao = 0;
        
        foreach ($viagens as $viagem) {
            if ($viagem->tempo_viagem) {
                $totalHoras += $viagem->tempo_viagem / 60; // converter minutos para horas
                $viagensComDuracao++;
            }
        }
        
        return $viagensComDuracao > 0 ? round($totalHoras / $viagensComDuracao, 1) : 0;
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
