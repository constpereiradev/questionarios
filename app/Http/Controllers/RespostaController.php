<?php

namespace App\Http\Controllers;

use App\Models\Pergunta;
use App\Models\Resposta;
use Illuminate\Http\Request;

class RespostaController extends Controller
{
    public function store(Request $request)
    {

        $respostas = $request->respostas;
        
        foreach ($respostas as $index => $resposta) {


            try {
                Resposta::create([
                    'resposta' => $resposta['resposta'],
                    'pergunta_id' => $resposta['pergunta_id']
                ]);
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
            
        }
    }
}
