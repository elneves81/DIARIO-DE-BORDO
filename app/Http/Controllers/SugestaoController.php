<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sugestao;
use Illuminate\Support\Facades\Auth;

class SugestaoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'mensagem' => 'required|string|max:1000',
        ]);

        Sugestao::create([
            'user_id' => Auth::id(),
            'mensagem' => $request->mensagem,
            'tipo' => $request->input('tipo', 'sugestao'),
        ]);

        return redirect()->back()->with('success', 'Sugestão enviada com sucesso!');
    }
}
