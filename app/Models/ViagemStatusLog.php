<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViagemStatusLog extends Model
{
    protected $fillable = [
        'viagem_id',
        'user_id',
        'status',
        'created_at',
    ];

    public $timestamps = false;

    public function viagem(): BelongsTo
    {
        return $this->belongsTo(Viagem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
