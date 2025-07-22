<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Viagem extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'hora_saida',
        'km_saida',
        'destino',
        'hora_chegada',
        'km_chegada',
        'condutor',
        'user_id',
        'num_registro_abastecimento',
        'quantidade_abastecida',
        'tipo_veiculo',
        'placa',
        'checklist',
        'anexos',
    ];

    protected $dates = ['data'];

    protected $casts = [
        'data' => 'datetime',
        'checklist' => 'array',
        'anexos' => 'array',
        'quantidade_abastecida' => 'decimal:2',
    ];

    protected $with = ['user'];

    // ============================================================================
    // RELACIONAMENTOS
    // ============================================================================

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ============================================================================
    // SCOPES
    // ============================================================================

    /**
     * Scope para filtrar viagens por usuário
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para viagens concluídas
     */
    public function scopeConcluidas(Builder $query): Builder
    {
        return $query->whereNotNull('km_chegada');
    }

    /**
     * Scope para viagens em andamento
     */
    public function scopeEmAndamento(Builder $query): Builder
    {
        return $query->whereNull('km_chegada')
                    ->whereDate('data', '<=', now());
    }

    /**
     * Scope para viagens agendadas
     */
    public function scopeAgendadas(Builder $query): Builder
    {
        return $query->whereNull('km_chegada')
                    ->whereDate('data', '>', now());
    }

    /**
     * Scope para filtrar por período
     */
    public function scopePorPeriodo(Builder $query, string $dataInicio, string $dataFim): Builder
    {
        return $query->whereBetween('data', [$dataInicio, $dataFim]);
    }

    /**
     * Scope para filtrar por mês/ano
     */
    public function scopePorMes(Builder $query, int $mes, int $ano): Builder
    {
        return $query->whereMonth('data', $mes)
                    ->whereYear('data', $ano);
    }

    /**
     * Scope para busca por texto
     */
    public function scopeBuscar(Builder $query, string $termo): Builder
    {
        return $query->where(function($q) use ($termo) {
            $q->where('destino', 'like', "%{$termo}%")
              ->orWhere('condutor', 'like', "%{$termo}%")
              ->orWhere('tipo_veiculo', 'like', "%{$termo}%")
              ->orWhere('placa', 'like', "%{$termo}%");
        });
    }

    /**
     * Scope para ordenação padrão
     */
    public function scopeOrdenacaoPadrao(Builder $query): Builder
    {
        return $query->orderBy('data', 'desc')
                    ->orderBy('hora_saida', 'desc');
    }

    // ============================================================================
    // ACCESSORS & MUTATORS
    // ============================================================================

    /**
     * Accessor para status da viagem
     */
    public function getStatusAttribute(): string
    {
        if ($this->km_chegada) {
            return 'concluida';
        }
        
        if (Carbon::parse($this->data)->isAfter(now()->startOfDay())) {
            return 'agendada';
        }
        
        return 'em_andamento';
    }

    /**
     * Accessor para status colorido
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'concluida' => 'success',
            'em_andamento' => 'warning',
            'agendada' => 'info',
            default => 'secondary'
        };
    }

    /**
     * Accessor para distância percorrida
     */
    public function getDistanciaPercorridaAttribute(): ?int
    {
        if ($this->km_chegada && $this->km_saida) {
            return $this->km_chegada - $this->km_saida;
        }
        
        return null;
    }

    /**
     * Accessor para tempo de viagem em minutos
     */
    public function getTempoViagemAttribute(): ?int
    {
        if (!$this->hora_chegada || !$this->hora_saida) {
            return null;
        }

        $saida = Carbon::createFromFormat('H:i', $this->hora_saida);
        $chegada = Carbon::createFromFormat('H:i', $this->hora_chegada);
        
        // Se chegada for menor, assumir que passou da meia-noite
        if ($chegada->lt($saida)) {
            $chegada->addDay();
        }
        
        return $saida->diffInMinutes($chegada);
    }

    /**
     * Accessor para velocidade média
     */
    public function getVelocidadeMediaAttribute(): ?float
    {
        if (!$this->distancia_percorrida || !$this->tempo_viagem) {
            return null;
        }
        
        $tempoHoras = $this->tempo_viagem / 60;
        return round($this->distancia_percorrida / $tempoHoras, 2);
    }

    /**
     * Accessor para data formatada
     */
    public function getDataFormatadaAttribute(): string
    {
        return Carbon::parse($this->data)->format('d/m/Y');
    }

    /**
     * Mutator para placa (sempre maiúscula)
     */
    public function setPlacaAttribute(?string $value): void
    {
        $this->attributes['placa'] = $value ? strtoupper($value) : null;
    }

    /**
     * Mutator para tipo de veículo (primeira letra maiúscula)
     */
    public function setTipoVeiculoAttribute(?string $value): void
    {
        $this->attributes['tipo_veiculo'] = $value ? ucfirst(strtolower($value)) : null;
    }

    // ============================================================================
    // MÉTODOS AUXILIARES
    // ============================================================================

    /**
     * Verifica se a viagem está concluída
     */
    public function isConcluida(): bool
    {
        return $this->status === 'concluida';
    }

    /**
     * Verifica se a viagem está em andamento
     */
    public function isEmAndamento(): bool
    {
        return $this->status === 'em_andamento';
    }

    /**
     * Verifica se a viagem está agendada
     */
    public function isAgendada(): bool
    {
        return $this->status === 'agendada';
    }

    /**
     * Retorna array com estatísticas da viagem
     */
    public function getEstatisticas(): array
    {
        return [
            'distancia_percorrida' => $this->distancia_percorrida,
            'tempo_viagem' => $this->tempo_viagem,
            'velocidade_media' => $this->velocidade_media,
            'status' => $this->status,
            'data_formatada' => $this->data_formatada,
        ];
    }

    /**
     * Verifica se o usuário pode editar esta viagem
     */
    public function canEdit(User $user): bool
    {
        return $user->is_admin || $this->user_id === $user->id;
    }

    /**
     * Verifica se o usuário pode visualizar esta viagem
     */
    public function canView(User $user): bool
    {
        return $user->is_admin || $this->user_id === $user->id;
    }
}


