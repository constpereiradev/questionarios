<?php

namespace App\Http\Controllers;

use App\Models\OpcaoResposta;
use App\Models\Pergunta;
use App\Models\Questionario;
use App\Models\Resposta;
use App\Services\QuestionarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

class QuestionarioController extends Controller
{
    protected $questionarioService;

    public function __construct(
        QuestionarioService $questionarioService
    ) {
        $this->questionarioService = $questionarioService;
    }

    public function index()
    {
        return Inertia::render('Questionario/Index');
    }

    public function api(Request $request)
    {
        $questionarios = Questionario::with('perguntas', 'perguntas.respostas', 'perguntas.opcoes')
            ->orderBy('created_at', 'desc');
           
        if($request->questionario !== null){
            $questionarios = $questionarios
            ->where('nome', 'like', '%' . $request->questionario . '%');
        }

        return response()->json([
            'questionarios' => $questionarios->get(),
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
        $graficos = [];
        $qtdRespostas = 0;
        $questionario = Questionario::with('perguntas', 'perguntas.respostas', 'perguntas.opcoes')->findOrFail($id);

        // Gera os gráficos para cada pergunta
        foreach ($questionario->perguntas as $pergunta) {
            $graficos[] = $this->questionarioService->graficoResposta($pergunta);
        }

        // Conta o total de respostas
        foreach ($questionario->perguntas as $pergunta) {
            $qtdRespostas += count($pergunta->respostas);
        }

        return Inertia::render('Questionario/View', [
            'qtdRespostas' => $qtdRespostas,
            'questionario' => $questionario,
            'graficos' => $graficos,
        ]);
    }

    public function delete($id)
    {
        try {
            $questionario = Questionario::findOrFail($id);

            $questionario->perguntas()->each(function ($pergunta) {
                $pergunta->respostas()->delete();
                $pergunta->opcoes()->delete();
                $pergunta->delete();
            });

            $questionario->delete();

            return Inertia::render('Questionario/Index');
            
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
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
