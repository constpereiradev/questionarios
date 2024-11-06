<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questionario extends Model
{
    protected $fillable = [
        'nome',
        'status'
    ];


    public function perguntas(): HasMany
    {
        return $this->hasMany(Pergunta::class);
    }
}
