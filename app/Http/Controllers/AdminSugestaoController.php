<?php

namespace App\Http\Controllers;

use App\Models\Sugestao;
use Illuminate\Http\Request;

class AdminSugestaoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $tipo = $request->get('tipo');
        $query = Sugestao::with('user')->orderByDesc('created_at');
        if ($tipo) {
            $query->where('tipo', $tipo);
        }
        $sugestoes = $query->paginate(20);
        return view('admin.sugestoes.index', compact('sugestoes', 'tipo'));
    }

    public function destroy($id)
    {
        $sugestao = Sugestao::findOrFail($id);
        $sugestao->delete();
        return redirect()->route('admin.sugestoes.index')->with('success', 'Mensagem excluída com sucesso!');
    }
}
