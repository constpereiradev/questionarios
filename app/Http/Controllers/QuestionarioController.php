<?php

namespace App\Http\Controllers;

use App\Models\Pergunta;
use App\Models\Questionario;
use App\Models\Resposta;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class QuestionarioController extends Controller
{
    public function index()
    {
        return Inertia::render('Questionario/Index');
    }

    public function api()
    {
        $questionarios = Questionario::with('perguntas', 'perguntas.respostas')
            ->get();

        return response()->json([
            'questionarios' => $questionarios,
        ]);
    }

    public function store(Request $request)
    {

        try {
            $questionario = Questionario::create([
                'nome' => $request->novoQuestionario['nome'],
                'status' => 'Ativo',
                'token' => Str::uuid(),
            ]);

            if ($questionario) {

                foreach ($request->novoQuestionario['perguntas'] as $index => $pergunta) {
                    Pergunta::create([
                        'nome' => $pergunta['nome'],
                        'tipo' => $pergunta['tipo'],
                        'questionario_id' => $questionario->id
                    ]);
                }

                $link = route('cliente.questionario.ver', ['token' => $questionario->token]);
                return $link;
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function show($id)
    {
        $qtdRespostas = 0;
        $questionario = Questionario::with('perguntas', 'perguntas.respostas')->findOrFail($id);
        foreach ($questionario->perguntas as $key => $pergunta) {
            $qtdRespostas = count($pergunta->respostas);
        }

        return Inertia::render('Questionario/View', [
            'qtdRespostas' => $qtdRespostas,
            'questionario' => $questionario,
        ]);
    }

    public function registrarResposta(Request $request)
    {
        $pergunta = Pergunta::find($request->pergunta_id);

        if($pergunta){
            try {
               
                Resposta::create([
                    'resposta' => $request->resposta,
                    'pergunta_id' => $pergunta->id,
                ]);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }
    }
}
