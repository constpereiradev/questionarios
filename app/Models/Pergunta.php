<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pergunta extends Model
{
    protected $fillable = [
        'nome',
        'tipo',
        'questionario_id'
    ];

    public function questionario(): BelongsTo
    {
        return $this->belongsTo(Questionario::class);
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(Resposta::class);
    }
}
