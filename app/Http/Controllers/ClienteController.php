<?php

namespace App\Http\Controllers;

use App\Models\Questionario;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClienteController extends Controller
{
    public function verQuestionario($token)
    {
        $questionario = Questionario::with('perguntas')
        ->where('token', $token)
        ->firstOrFail();
        
        return Inertia::render('Cliente/Questionario', [
            'questionario' => $questionario,
        ]);
    }
}
