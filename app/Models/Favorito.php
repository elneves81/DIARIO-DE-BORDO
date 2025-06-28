<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'destino',
        'tipo_veiculo',
        'placa',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
