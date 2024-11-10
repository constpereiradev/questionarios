<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpcaoResposta extends Model
{

    protected $table = 'opcao_respostas';

    protected $fillable = [
        'opcao',
        'tipo_pegunta',
        'pergunta_id',
    ];


    public function pergunta(): BelongsTo{
        return $this->belongsTo(Pergunta::class);
    }
}
