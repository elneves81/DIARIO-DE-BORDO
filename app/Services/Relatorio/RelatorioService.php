<?php

namespace App\Services\Relatorio;

use App\Models\Viagem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RelatorioService
{
    /**
     * Gera dados para relatório de viagens com filtros
     */
    public function generateViagemReport(array $filters = []): Collection
    {
        $query = Viagem::with('user');

        // Aplicar filtros
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['data_inicio'])) {
            $query->whereDate('data', '>=', $filters['data_inicio']);
        }

        if (!empty($filters['data_fim'])) {
            $query->whereDate('data', '<=', $filters['data_fim']);
        }

        if (!empty($filters['tipo_veiculo'])) {
            $query->where('tipo_veiculo', 'like', '%' . $filters['tipo_veiculo'] . '%');
        }

        if (!empty($filters['placa'])) {
            $query->where('placa', 'like', '%' . $filters['placa'] . '%');
        }

        if (!empty($filters['destino'])) {
            $query->where('destino', 'like', '%' . $filters['destino'] . '%');
        }

        return $query->orderBy('data', 'desc')->get();
    }

    /**
     * Calcula estatísticas do relatório
     */
    public function calculateReportStats(Collection $viagens): array
    {
        $totalViagens = $viagens->count();
        $totalKm = 0;
        $totalCombustivel = 0;
        $viagensCompletas = 0;
        $tempoTotal = 0;

        foreach ($viagens as $viagem) {
            // Calcular KM percorrido
            if ($viagem->km_chegada && $viagem->km_saida) {
                $totalKm += ($viagem->km_chegada - $viagem->km_saida);
                $viagensCompletas++;
            }

            // Somar combustível
            if ($viagem->quantidade_abastecida) {
                $totalCombustivel += $viagem->quantidade_abastecida;
            }

            // Calcular tempo total
            if ($viagem->hora_chegada && $viagem->hora_saida) {
                $saida = Carbon::createFromFormat('H:i', $viagem->hora_saida);
                $chegada = Carbon::createFromFormat('H:i', $viagem->hora_chegada);
                
                if ($chegada->lt($saida)) {
                    $chegada->addDay();
                }
                
                $tempoTotal += $saida->diffInMinutes($chegada);
            }
        }

        return [
            'total_viagens' => $totalViagens,
            'viagens_completas' => $viagensCompletas,
            'total_km' => $totalKm,
            'media_km_viagem' => $viagensCompletas > 0 ? round($totalKm / $viagensCompletas, 2) : 0,
            'total_combustivel' => $totalCombustivel,
            'tempo_total_minutos' => $tempoTotal,
            'tempo_total_horas' => round($tempoTotal / 60, 2),
            'periodo' => [
                'inicio' => $viagens->min('data'),
                'fim' => $viagens->max('data')
            ]
        ];
    }

    /**
     * Gera relatório por usuário
     */
    public function generateUserReport(int $userId, array $filters = []): array
    {
        $user = User::findOrFail($userId);
        $filters['user_id'] = $userId;
        
        $viagens = $this->generateViagemReport($filters);
        $stats = $this->calculateReportStats($viagens);

        // Estatísticas específicas do usuário
        $destinosFrequentes = $viagens->groupBy('destino')
            ->map->count()
            ->sortDesc()
            ->take(10);

        $veiculosUtilizados = $viagens->whereNotNull('tipo_veiculo')
            ->groupBy('tipo_veiculo')
            ->map->count()
            ->sortDesc();

        return [
            'user' => $user,
            'viagens' => $viagens,
            'stats' => $stats,
            'destinos_frequentes' => $destinosFrequentes,
            'veiculos_utilizados' => $veiculosUtilizados
        ];
    }

    /**
     * Gera relatório consolidado (todos os usuários)
     */
    public function generateConsolidatedReport(array $filters = []): array
    {
        $viagens = $this->generateViagemReport($filters);
        $stats = $this->calculateReportStats($viagens);

        // Estatísticas por usuário
        $statsPorUsuario = $viagens->groupBy('user_id')->map(function($viagensUsuario) {
            return [
                'user' => $viagensUsuario->first()->user,
                'total_viagens' => $viagensUsuario->count(),
                'total_km' => $viagensUsuario->sum(function($v) {
                    return ($v->km_chegada && $v->km_saida) ? $v->km_chegada - $v->km_saida : 0;
                }),
                'total_combustivel' => $viagensUsuario->sum('quantidade_abastecida')
            ];
        })->sortByDesc('total_km');

        // Top destinos
        $topDestinos = $viagens->groupBy('destino')
            ->map->count()
            ->sortDesc()
            ->take(15);

        // Viagens por mês
        $viagensPorMes = $viagens->groupBy(function($viagem) {
            return Carbon::parse($viagem->data)->format('Y-m');
        })->map->count()->sortKeys();

        return [
            'viagens' => $viagens,
            'stats' => $stats,
            'stats_por_usuario' => $statsPorUsuario,
            'top_destinos' => $topDestinos,
            'viagens_por_mes' => $viagensPorMes
        ];
    }

    /**
     * Gera dados para gráficos do dashboard
     */
    public function generateDashboardData(int $userId = null): array
    {
        $query = Viagem::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $viagens = $query->get();

        // Viagens por status (baseado em km_chegada)
        $statusData = [
            'concluidas' => $viagens->whereNotNull('km_chegada')->count(),
            'em_andamento' => $viagens->whereNull('km_chegada')
                ->where('data', '<=', now()->format('Y-m-d'))->count(),
            'agendadas' => $viagens->whereNull('km_chegada')
                ->where('data', '>', now()->format('Y-m-d'))->count()
        ];

        // Viagens por mês (últimos 12 meses)
        $viagensPorMes = [];
        for ($i = 11; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            $count = $viagens->filter(function($viagem) use ($mes) {
                return Carbon::parse($viagem->data)->isSameMonth($mes);
            })->count();
            
            $viagensPorMes[$mes->format('M/Y')] = $count;
        }

        // Top 10 destinos
        $topDestinos = $viagens->groupBy('destino')
            ->map->count()
            ->sortDesc()
            ->take(10);

        return [
            'status_data' => $statusData,
            'viagens_por_mes' => $viagensPorMes,
            'top_destinos' => $topDestinos,
            'total_viagens' => $viagens->count(),
            'total_km' => $viagens->sum(function($v) {
                return ($v->km_chegada && $v->km_saida) ? $v->km_chegada - $v->km_saida : 0;
            })
        ];
    }

    /**
     * Formata dados para exportação Excel
     */
    public function formatForExcel(Collection $viagens): array
    {
        return $viagens->map(function($viagem) {
            return [
                'Data' => Carbon::parse($viagem->data)->format('d/m/Y'),
                'Condutor' => $viagem->condutor,
                'Destino' => $viagem->destino,
                'Hora Saída' => $viagem->hora_saida,
                'KM Saída' => $viagem->km_saida,
                'Hora Chegada' => $viagem->hora_chegada ?? 'Em andamento',
                'KM Chegada' => $viagem->km_chegada ?? 'Em andamento',
                'Distância' => ($viagem->km_chegada && $viagem->km_saida) 
                    ? $viagem->km_chegada - $viagem->km_saida . ' km' 
                    : 'Em andamento',
                'Tipo Veículo' => $viagem->tipo_veiculo ?? 'Não informado',
                'Placa' => $viagem->placa ?? 'Não informado',
                'Combustível' => $viagem->quantidade_abastecida 
                    ? $viagem->quantidade_abastecida . ' L' 
                    : 'Não abastecido',
                'Usuário' => $viagem->user->name ?? 'N/A'
            ];
        })->toArray();
    }
}
