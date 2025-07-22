<?php

namespace App\Http\Controllers;

use App\Models\Viagem;
use App\Services\Viagem\ViagemService;
use App\Services\Relatorio\RelatorioService;
use App\Http\Requests\Viagem\StoreViagemRequest;
use App\Http\Requests\Viagem\UpdateViagemRequest;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ViagemController extends Controller
{
    protected $viagemService;
    protected $relatorioService;

    public function __construct(ViagemService $viagemService, RelatorioService $relatorioService)
    {
        $this->viagemService = $viagemService;
        $this->relatorioService = $relatorioService;
    }

    /**
     * Lista viagens com filtros e cache
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Viagem::with('user');

        // Aplicar filtro de usuário se não for admin
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }

        // Aplicar filtros de busca
        $this->applyFilters($query, $request);

        // Cache baseado nos filtros e usuário
        $cacheKey = $this->generateCacheKey($request, $user);
        $viagens = Cache::remember($cacheKey, 300, function () use ($query) {
            return $query->orderBy('data', 'desc')->paginate(15);
        });

        // Resposta AJAX para paginação infinita
        if ($request->ajax()) {
            $html = view('viagens._row', compact('viagens'))->render();
            return response()->json(['html' => $html]);
        }
        
        return view('viagens.index-mobile', compact('viagens'));
    }

    /**
     * Mostra formulário de criação
     */
    public function create()
    {
        return view('viagens.create');
    }

    /**
     * Armazena nova viagem
     */
    public function store(StoreViagemRequest $request)
    {
        try {
            // Validar regras de negócio
            $errors = $this->viagemService->validateBusinessRules($request->validated());
            if (!empty($errors)) {
                return back()->withInput()->withErrors($errors);
            }

            // Criar viagem
            $viagem = Viagem::create($request->validated());

            // Processar anexos
            $this->processAnexos($viagem, $request);

            // Limpar cache relacionado
            $this->clearViagemCache();

            return redirect()->route('viagens.index')
                           ->with('success', 'Viagem cadastrada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao criar viagem: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'data' => $request->validated()
            ]);

            return back()->withInput()
                        ->with('error', 'Erro ao cadastrar viagem. Tente novamente.');
        }
    }

    /**
     * Mostra detalhes da viagem
     */
    public function show($id)
    {
        $viagem = Viagem::with('user')->find($id);
        
        if (!$viagem) {
            return redirect()->route('viagens.index')
                           ->with('error', 'Viagem não encontrada.');
        }

        // Verificar permissão
        if (!auth()->user()->is_admin && $viagem->user_id !== auth()->id()) {
            return redirect()->route('viagens.index')
                           ->with('error', 'Você não tem permissão para visualizar esta viagem.');
        }

        // Calcular estatísticas da viagem
        $stats = $this->viagemService->calculateStats($viagem);

        return view('viagens.show', compact('viagem', 'stats'));
    }

    /**
     * Mostra formulário de edição
     */
    public function edit($id)
    {
        $viagem = Viagem::find($id);
        
        if (!$viagem) {
            return redirect()->route('viagens.index')
                           ->with('error', 'Viagem não encontrada.');
        }

        // Verificar permissão
        if (!auth()->user()->is_admin && $viagem->user_id !== auth()->id()) {
            return redirect()->route('viagens.index')
                           ->with('error', 'Você não tem permissão para editar esta viagem.');
        }

        return view('viagens.edit', compact('viagem'));
    }

    /**
     * Atualiza viagem
     */
    public function update(UpdateViagemRequest $request, $id)
    {
        try {
            $viagem = Viagem::findOrFail($id);

            // Verificar permissão
            if (!auth()->user()->is_admin && $viagem->user_id !== auth()->id()) {
                return redirect()->route('viagens.index')
                               ->with('error', 'Você não tem permissão para editar esta viagem.');
            }

            // Validar regras de negócio
            $errors = $this->viagemService->validateBusinessRules($request->validated(), $viagem);
            if (!empty($errors)) {
                return back()->withInput()->withErrors($errors);
            }

            // Atualizar viagem
            $viagem->update($request->validated());

            // Processar anexos
            $this->processAnexos($viagem, $request);

            // Limpar cache relacionado
            $this->clearViagemCache();

            return redirect()->route('viagens.show', $viagem->id)
                           ->with('success', 'Viagem atualizada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar viagem: ' . $e->getMessage(), [
                'viagem_id' => $id,
                'user_id' => auth()->id(),
                'data' => $request->validated()
            ]);

            return back()->withInput()
                        ->with('error', 'Erro ao atualizar viagem. Tente novamente.');
        }
    }

    /**
     * Remove viagem
     */
    public function destroy(Viagem $viagem)
    {
        try {
            // Verificar permissão
            if (!auth()->user()->is_admin && $viagem->user_id !== auth()->id()) {
                return redirect()->route('viagens.index')
                               ->with('error', 'Você não tem permissão para excluir esta viagem.');
            }

            $viagem->delete();

            // Limpar cache relacionado
            $this->clearViagemCache();

            return redirect()->route('viagens.index')
                           ->with('success', 'Viagem excluída com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao excluir viagem: ' . $e->getMessage(), [
                'viagem_id' => $viagem->id,
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Erro ao excluir viagem. Tente novamente.');
        }
    }

    /**
     * Exporta viagens em PDF
     */
    public function exportarPdf(Request $request)
    {
        try {
            $filters = $request->only(['user_id', 'data_inicio', 'data_fim', 'tipo_veiculo', 'placa', 'destino']);
            
            // Se não for admin, filtrar apenas suas viagens
            if (!auth()->user()->is_admin) {
                $filters['user_id'] = auth()->id();
            }

            $viagens = $this->relatorioService->generateViagemReport($filters);
            $stats = $this->relatorioService->calculateReportStats($viagens);

            $pdf = PDF::loadView('viagens.pdf', compact('viagens', 'stats'));
            
            return $pdf->download('relatorio_viagens_' . date('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            Log::error('Erro ao gerar PDF: ' . $e->getMessage());
            
            return back()->with('error', 'Erro ao gerar relatório PDF. Tente novamente.');
        }
    }

    /**
     * Aplica filtros na query
     */
    private function applyFilters($query, Request $request): void
    {
        $filters = [
            'destino' => 'like',
            'tipo_veiculo' => 'like',
            'placa' => 'like',
            'condutor' => 'like'
        ];

        foreach ($filters as $field => $operator) {
            if ($request->filled($field)) {
                $value = $operator === 'like' ? '%' . $request->$field . '%' : $request->$field;
                $query->where($field, $operator, $value);
            }
        }

        // Filtros de data
        if ($request->filled('data_ini')) {
            $query->whereDate('data', '>=', $request->data_ini);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('data', '<=', $request->data_fim);
        }
    }

    /**
     * Gera chave de cache baseada nos filtros
     */
    private function generateCacheKey(Request $request, $user): string
    {
        $filters = $request->only(['destino', 'data_ini', 'data_fim', 'tipo_veiculo', 'placa', 'condutor']);
        $page = $request->get('page', 1);
        
        return 'viagens:' . md5(json_encode($filters) . ':page:' . $page . ':user:' . $user->id);
    }

    /**
     * Processa upload de anexos
     */
    private function processAnexos(Viagem $viagem, Request $request): void
    {
        if ($request->hasFile('anexos')) {
            $anexos = $viagem->anexos ?? [];
            
            foreach ($request->file('anexos') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('anexos_viagens/' . $viagem->id, 'public');
                    $anexos[] = $path;
                }
            }
            
            $viagem->update(['anexos' => $anexos]);
        }
    }

    /**
     * Limpa cache relacionado a viagens
     */
    private function clearViagemCache(): void
    {
        Cache::tags(['viagens'])->flush();
    }
}
