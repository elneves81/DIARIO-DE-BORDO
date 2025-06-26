<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'placa'
    ];

    protected $dates = ['data'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}


