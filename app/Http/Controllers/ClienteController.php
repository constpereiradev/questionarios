<?php

namespace App\Http\Controllers;

use App\Models\Questionario;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClienteController extends Controller
{
    public function verQuestionario($id)
    {
        $questionario = Questionario::with('perguntas')->findOrFail($id);
        
        return Inertia::render('Cliente/Questionario', [
            'questionario' => $questionario,
        ]);
    }
}
