@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Mensagens e Sugestões</h3>
    <form method="get" class="mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label for="tipo" class="form-label">Filtrar por tipo:</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="">Todos</option>
                    <option value="contato" @if($tipo=='contato') selected @endif>Fale Conosco</option>
                    <option value="sugestao" @if($tipo=='sugestao') selected @endif>Sugestão</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Tipo</th>
                    <th>Mensagem</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sugestoes as $sugestao)
                <tr>
                    <td>{{ $sugestao->id }}</td>
                    <td>
                        {{ $sugestao->user ? $sugestao->user->name : 'Desconhecido' }}<br>
                        <small>{{ $sugestao->user ? $sugestao->user->email : '' }}</small>
                    </td>
                    <td>
                        @if($sugestao->tipo=='contato')
                            <span class="badge bg-info">Fale Conosco</span>
                        @else
                            <span class="badge bg-secondary">Sugestão</span>
                        @endif
                    </td>
                    <td style="max-width:300px;white-space:pre-wrap;">
                        {{ $sugestao->mensagem }}
                    </td>
                    <td>{{ $sugestao->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.sugestoes.destroy', $sugestao->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta mensagem?');" class="mb-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                        <form action="{{ route('admin.sugestoes.responder', $sugestao->id) }}" method="POST">
                            @csrf
                            <textarea name="resposta" class="form-control mb-2" rows="2" placeholder="Digite sua resposta (será enviada por email)..." required></textarea>
                            <button type="submit" class="btn btn-sm btn-success">Enviar Resposta</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Nenhuma mensagem encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $sugestoes->withQueryString()->links() }}
</div>
@endsection
