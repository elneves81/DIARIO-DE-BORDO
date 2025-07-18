<?php

namespace App\Services\Viagem;

use App\Models\Viagem;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ViagemService
{
    /**
     * Valida regras de negócio para criação/edição de viagem
     */
    public function validateBusinessRules(array $data, ?Viagem $viagem = null): array
    {
        $errors = [];

        // Validação de data
        $dataViagem = Carbon::parse($data['data']);
        $hoje = Carbon::today();
        
        if ($dataViagem->isAfter($hoje)) {
            $errors['data'][] = 'Não é possível registrar viagens futuras.';
        }
        
        if ($dataViagem->isBefore($hoje->subDays(7))) {
            $errors['data'][] = 'Não é possível registrar viagens com mais de 7 dias retroativos.';
        }

        // Validação de KM
        if (isset($data['km_chegada']) && isset($data['km_saida'])) {
            if ($data['km_chegada'] < $data['km_saida']) {
                $errors['km_chegada'][] = 'KM de chegada não pode ser menor que o de saída.';
            }
            
            $distancia = $data['km_chegada'] - $data['km_saida'];
            if ($distancia > 1000) {
                $errors['km_chegada'][] = 'Distância muito alta (acima de 1000km). Verifique os dados.';
            }
        }

        // Validação de horários
        if (isset($data['hora_chegada']) && isset($data['hora_saida'])) {
            $saida = Carbon::createFromFormat('H:i', $data['hora_saida']);
            $chegada = Carbon::createFromFormat('H:i', $data['hora_chegada']);
            
            if ($chegada->isSameDay($saida) && $chegada->lte($saida)) {
                $errors['hora_chegada'][] = 'Hora de chegada deve ser posterior à de saída.';
            }
        }

        // Validação de viagens duplicadas (mesmo dia, mesmo condutor)
        if (!$viagem) { // apenas na criação
            $viagemExistente = Viagem::where('user_id', auth()->id())
                ->where('data', $data['data'])
                ->where('condutor', $data['condutor'])
                ->where('destino', $data['destino'])
                ->exists();
                
            if ($viagemExistente) {
                $errors['geral'][] = 'Já existe uma viagem registrada para este condutor nesta data e destino.';
            }
        }

        return $errors;
    }

    /**
     * Calcula estatísticas da viagem
     */
    public function calculateStats(Viagem $viagem): array
    {
        $stats = [
            'distancia_percorrida' => 0,
            'tempo_viagem' => null,
            'velocidade_media' => null
        ];

        if ($viagem->km_chegada && $viagem->km_saida) {
            $stats['distancia_percorrida'] = $viagem->km_chegada - $viagem->km_saida;
        }

        if ($viagem->hora_chegada && $viagem->hora_saida) {
            $saida = Carbon::createFromFormat('H:i', $viagem->hora_saida);
            $chegada = Carbon::createFromFormat('H:i', $viagem->hora_chegada);
            
            // Se chegada for menor, assumir que passou da meia-noite
            if ($chegada->lt($saida)) {
                $chegada->addDay();
            }
            
            $stats['tempo_viagem'] = $saida->diffInMinutes($chegada);
            
            // Calcular velocidade média se temos distância e tempo
            if ($stats['distancia_percorrida'] > 0 && $stats['tempo_viagem'] > 0) {
                $tempoHoras = $stats['tempo_viagem'] / 60;
                $stats['velocidade_media'] = round($stats['distancia_percorrida'] / $tempoHoras, 2);
            }
        }

        return $stats;
    }

    /**
     * Gera relatório mensal do usuário
     */
    public function generateMonthlyReport(int $userId, int $month, int $year): array
    {
        $viagens = Viagem::where('user_id', $userId)
            ->whereMonth('data', $month)
            ->whereYear('data', $year)
            ->get();

        $totalViagens = $viagens->count();
        $totalKm = $viagens->sum(function($v) {
            return ($v->km_chegada && $v->km_saida) ? $v->km_chegada - $v->km_saida : 0;
        });

        $totalTempo = 0;
        foreach ($viagens as $viagem) {
            if ($viagem->hora_chegada && $viagem->hora_saida) {
                $saida = Carbon::createFromFormat('H:i', $viagem->hora_saida);
                $chegada = Carbon::createFromFormat('H:i', $viagem->hora_chegada);
                if ($chegada->lt($saida)) $chegada->addDay();
                $totalTempo += $saida->diffInMinutes($chegada);
            }
        }

        return [
            'total_viagens' => $totalViagens,
            'total_km' => $totalKm,
            'total_tempo_minutos' => $totalTempo,
            'media_km_por_viagem' => $totalViagens > 0 ? round($totalKm / $totalViagens, 2) : 0,
            'destinos_frequentes' => $viagens->groupBy('destino')->map->count()->sortDesc()->take(5)->toArray()
        ];
    }
}
