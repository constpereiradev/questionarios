<?php

namespace App\Http\Controllers;

use App\Models\OpcaoResposta;
use App\Models\Pergunta;
use App\Models\Questionario;
use App\Models\Resposta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $questionarios = Questionario::with('perguntas', 'perguntas.respostas', 'perguntas.opcoes')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'questionarios' => $questionarios,
        ]);
    }

    public function create()
    {
        return Inertia::render('Questionario/Create');
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

                foreach ($request->novoQuestionario['perguntas'] as $index => $perguntaQuestionario) {

                    try {
                        $pergunta = Pergunta::create([
                            'nome' => $perguntaQuestionario['nome'],
                            'tipo' => $perguntaQuestionario['tipo'],
                            'questionario_id' => $questionario->id
                        ]);

                        if ($pergunta && $pergunta->tipo == 'multipla_escolha') {
                            foreach ($perguntaQuestionario['opcoes'] as $index => $opcao) {
                                if ($opcao !== null && $opcao !== '') {
                                    try {
                                        OpcaoResposta::create([
                                            'opcao' => $opcao,
                                            'tipo_pegunta' => $pergunta['tipo'],
                                            'pergunta_id' => $pergunta->id
                                        ]);
                                    } catch (\Exception $e) {
                                        DB::rollBack();
                                        dd($e->getMessage());
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        dd($e->getMessage());
                    }
                }

                $link = route('cliente.questionario.ver', ['token' => $questionario->token]);
                return $link;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function read($id)
    {
        $qtdRespostas = 0;
        $questionario = Questionario::with('perguntas', 'perguntas.respostas', 'perguntas.opcoes')->findOrFail($id);
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

        if ($pergunta) {
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
