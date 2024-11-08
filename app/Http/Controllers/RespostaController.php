<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RespostaController extends Controller
{
    public function store(Request $request)
    {
        dd($request->resposta);   
    }
}
