<?php
namespace App\Services;

use App\Models\Resposta;

class QuestionarioService
{
    public function graficoResposta($pergunta)
    {
        $coresGerais = ['#4E73DF', '#1CC88A', '#36B9CC', '#F6C23E', '#E74A3B'];
        $respostasNome = [];
        $respostasPorPergunta = [];

        // Coleta os nomes das respostas únicas
        foreach ($pergunta->respostas as $resposta) {
            if (!in_array($resposta['resposta'], $respostasNome)) {
                array_push($respostasNome, $resposta['resposta']);
            }
        }

        // Conta as respostas para cada nome de resposta
        $contagemRespostas = [];
        foreach ($pergunta->respostas as $resposta) {
            $respostaNome = $resposta['resposta'];
            if (!isset($contagemRespostas[$respostaNome])) {
                $contagemRespostas[$respostaNome] = 0;
            }
            $contagemRespostas[$respostaNome]++;
        }

        // Prepara os dados do gráfico
        $series = [];
        foreach ($respostasNome as $respostaNome) {
            $series[] = $contagemRespostas[$respostaNome] ?? 0;
        }

        $grafico = (object)[
            'series' => [
                [
                    'name' => $pergunta['nome'],
                    'data' => $series,
                ],
            ],
            'chartOptions' => [
                'chart' => [
                    'height' => 350,
                    'type' => 'bar',
                ],
                'colors' => $coresGerais,
                'dataLabels' => [
                    'enabled' => true,
                ],
                'xaxis' => [
                    'type' => 'category',
                    'categories' => $respostasNome,
                ],
            ],
        ];

        return response()->json([
            'grafico' => $grafico,
        ]);
    }
}

