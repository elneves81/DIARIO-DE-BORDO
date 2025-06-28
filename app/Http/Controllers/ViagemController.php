<?php

namespace App\Http\Controllers;

use App\Models\Viagem;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class ViagemController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Viagem::query();

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        // Filtros avançados
        if ($request->filled('destino')) {
            $query->where('destino', 'like', '%' . $request->destino . '%');
        }
        if ($request->filled('data_ini')) {
            $query->whereDate('data', '>=', $request->data_ini);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('data', '<=', $request->data_fim);
        }
        if ($request->filled('tipo_veiculo')) {
            $query->where('tipo_veiculo', 'like', '%' . $request->tipo_veiculo . '%');
        }
        if ($request->filled('placa')) {
            $query->where('placa', 'like', '%' . $request->placa . '%');
        }
        if ($request->filled('condutor')) {
            $query->where('condutor', 'like', '%' . $request->condutor . '%');
        }

        // Gera uma chave de cache baseada nos filtros e na página
        $cacheKey = 'viagens:' . md5(json_encode($request->all()) . ':page:' . $request->get('page', 1) . ':user:' . ($user->id ?? 'guest'));
        $viagens = \Cache::remember($cacheKey, 60, function () use ($query) {
            return $query->orderBy('data', 'desc')->paginate(15);
        });

        if ($request->ajax()) {
            $html = view('viagens._row', compact('viagens'))->render();
            return response()->json(['html' => $html]);
        }
        
        // Sempre usar a view mobile (mais moderna com cards e status coloridos)
        return view('viagens.index-mobile', compact('viagens'));
    }

    public function create()
    {
        return view('viagens.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'data' => [
                'required',
                'date',
                function($attribute, $value, $fail) {
                    $hoje = date('Y-m-d');
                    if ($value !== $hoje) {
                        $fail('Você não pode agendar essa viagem. A data precisa ser de Hoje!!');
                    }
                }
            ],
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
        // Validação extra: hora_chegada só se informado, e deve ser válida
        if ($request->filled('hora_chegada')) {
            $h_saida = $request->hora_saida;
            $h_chegada = $request->hora_chegada;
            if (strlen($h_saida) === 5 && strlen($h_chegada) === 5) {
                if ($h_chegada === $h_saida) {
                    return back()->withInput()->withErrors(['hora_chegada' => 'A hora de chegada não pode ser igual à hora de saída.']);
                }
                // Se hora_chegada < hora_saida, permite (viagem atravessa a meia-noite)
                // Só bloqueia se for igual
            }
        }

        $viagem = Viagem::create(array_merge(
            $request->except('anexos'),
            [
                'checklist' => $request->input('checklist', [])
            ]
        ));

        // Upload e salvamento dos anexos
        if ($request->hasFile('anexos')) {
            $anexos = [];
            foreach ($request->file('anexos') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('anexos_viagens/' . $viagem->id, 'public');
                    $anexos[] = $path;
                }
            }
            $viagem->anexos = $anexos;
            $viagem->save();
        }

        return redirect()->route('viagens.index')->with('success', 'Viagem cadastrada com sucesso!');
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
            'hora_chegada' => 'nullable',
            'km_chegada' => 'nullable|integer',
            'condutor' => 'required',
        ]);

        // Validação extra: km_chegada só se informado, deve ser maior ou igual ao km_saida
        if ($request->filled('km_chegada')) {
            if ($request->input('km_chegada') < $request->input('km_saida')) {
                return back()->withInput()->withErrors(['km_chegada' => 'O KM de chegada não pode ser menor que o KM de saída.']);
            }
        }
        
        // Validação extra: hora_chegada só se informado, deve ser válida
        if ($request->filled('hora_chegada')) {
            $h_saida = $request->hora_saida;
            $h_chegada = $request->hora_chegada;
            if (strlen($h_saida) === 5 && strlen($h_chegada) === 5) {
                if ($h_chegada < $h_saida) {
                    return back()->withInput()->withErrors(['hora_chegada' => 'A hora de chegada não pode ser inferior à hora de saída.']);
                }
            }
        }

        $data = $request->except(['anexos', 'origem']);
        $data['user_id'] = auth()->id();

        // DEBUG: Verifique o que chega no request e o que será salvo
        \Log::info('Viagem update request', $request->all());
        \Log::info('Viagem update data', $data);

        $viagem->update($data);

        // Upload e atualização dos anexos
        if ($request->hasFile('anexos')) {
            $anexos = $viagem->anexos ?? [];
            foreach ($request->file('anexos') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('anexos_viagens/' . $viagem->id, 'public');
                    $anexos[] = $path;
                }
            }
            $viagem->anexos = $anexos;
            $viagem->save();
        }

        return redirect()->route('viagens.show', $viagem->id)
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