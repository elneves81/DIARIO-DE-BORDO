<?php

namespace App\Http\Controllers;

use App\Models\Viagem;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class ViagemController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            $viagens = Viagem::orderBy('data', 'desc')->get();
        } else {
            $viagens = Viagem::where('user_id', $user->id)->orderBy('data', 'desc')->get();
        }
        return view('viagens.index', compact('viagens'));
    }

    public function create()
    {
        return view('viagens.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required|date',
            'hora_saida' => 'required',
            'km_saida' => 'required|integer',
            'destino' => 'required',
            'condutor' => 'required',
        ]);

        // Validação extra: km_chegada só se informado, e deve ser maior ou igual ao km_saida
        if ($request->filled('km_chegada')) {
            if ($request->km_chegada < $request->km_saida) {
                return back()->withInput()->withErrors(['km_chegada' => 'O KM de chegada não pode ser menor que o KM de saída.']);
            }
        }
        // Validação extra: hora_chegada só se informado, e deve ser maior ou igual à hora_saida
        if ($request->filled('hora_chegada')) {
            $h_saida = $request->hora_saida;
            $h_chegada = $request->hora_chegada;
            if (strlen($h_saida) === 5 && strlen($h_chegada) === 5) {
                if ($h_chegada < $h_saida) {
                    return back()->withInput()->withErrors(['hora_chegada' => 'A hora de chegada não pode ser inferior à hora de saída.']);
                }
            }
        }

        $data = $request->all();
        $data['user_id'] = auth()->id();
        Viagem::create($data);

        return redirect()->route('viagens.index')
                         ->with('success', 'Viagem registrada com sucesso!');
    }

    public function show($id)
    {
        $viagem = \App\Models\Viagem::find($id);
        if (!$viagem) {
            return redirect()->route('viagens.index')->with('error', 'Viagem não encontrada.');
        }
        return view('viagens.show', compact('viagem'));
    }

    public function edit($id)
    {
        $viagem = Viagem::find($id);
        if (!$viagem) {
            return redirect()->route('viagens.index')->with('error', 'Viagem não encontrada ou parâmetro ausente.');
        }
        return view('viagens.edit', compact('viagem'));
    }

    public function update(Request $request, $id)
    {
        $viagem = Viagem::findOrFail($id);
        $request->validate([
            'data' => 'required|date',
            'hora_saida' => 'required',
            'km_saida' => 'required|integer',
            'destino' => 'required',
            'hora_chegada' => 'required',
            'km_chegada' => 'required|integer',
            'condutor' => 'required',
        ]);

        // Validação extra: km_chegada deve ser maior ou igual ao km_saida
        if ($request->input('km_chegada') < $request->input('km_saida')) {
            return back()->withInput()->withErrors(['km_chegada' => 'O KM de chegada não pode ser menor que o KM de saída.']);
        }
        // Validação extra: hora_chegada deve ser maior ou igual à hora_saida
        $h_saida = $request->hora_saida;
        $h_chegada = $request->hora_chegada;
        if (strlen($h_saida) === 5 && strlen($h_chegada) === 5) {
            if ($h_chegada < $h_saida) {
                return back()->withInput()->withErrors(['hora_chegada' => 'A hora de chegada não pode ser inferior à hora de saída.']);
            }
        }

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $viagem->update($data);

        return redirect()->route('viagens.index')
                         ->with('success', 'Viagem atualizada com sucesso!');
    }

    public function destroy(Viagem $viagem)
    {
        $viagem->delete();

        return redirect()->route('viagens.index')
                         ->with('success', 'Viagem excluída com sucesso!');
    }

    public function exportarPdf()
    {
        $viagens = Viagem::orderBy('data', 'desc')->get();
        $pdf = PDF::loadView('viagens.pdf', compact('viagens'));
        return $pdf->download('relatorio_viagens.pdf');
    }
}