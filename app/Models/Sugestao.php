<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sugestao extends Model
{
    use HasFactory;
    protected $table = 'sugestoes';
    protected $fillable = ['user_id', 'mensagem', 'tipo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
