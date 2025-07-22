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

    public function responder(Request $request, $id)
    {
        $request->validate([
            'resposta' => 'required|string|min:3',
        ]);
        
        $sugestao = \App\Models\Sugestao::findOrFail($id);
        
        // Envia e-mail para o usuário SEM salvar a resposta no banco
        if ($sugestao->user && $sugestao->user->email) {
            // Cria um objeto temporário com a resposta para o email
            $sugestaoComResposta = clone $sugestao;
            $sugestaoComResposta->resposta = $request->resposta;
            $sugestaoComResposta->respondida_em = now();
            
            try {
                \Mail::to($sugestao->user->email)->send(new \App\Mail\RespostaSugestaoMail($sugestaoComResposta));
                $message = 'Resposta enviada por email com sucesso!';
            } catch (\Exception $e) {
                \Log::error('Erro ao enviar email de resposta de sugestão: ' . $e->getMessage());
                $message = 'Resposta processada, mas email não pôde ser enviado.';
            }
        } else {
            $message = 'Resposta processada (usuário sem email cadastrado).';
        }
        
        return redirect()->route('admin.sugestoes.index')->with('success', $message);
    }
}
