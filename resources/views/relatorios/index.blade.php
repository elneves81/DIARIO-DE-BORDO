@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Relatórios</h2>
    <div class="card mb-3">
        <div class="card-body">
            <form class="row g-3 mb-3" method="GET" action="{{ route('relatorios.index') }}">
                <div class="col-md-4">
                    <label for="usuario" class="form-label">Condutor</label>
                    <select name="usuario" id="usuario" class="form-select" {{ !(auth()->check() && auth()->user()->isAdmin()) ? 'disabled' : '' }}>
                        <option value="">Todos</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->name }}" {{ request('usuario', auth()->user()->name) == $usuario->name ? 'selected' : '' }}>{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                    @if(!(auth()->check() && auth()->user()->isAdmin()))
                        <input type="hidden" name="usuario" value="{{ auth()->user()->name }}">
                    @endif
                </div>
                <div class="col-md-3">
                    <label for="data_inicio" class="form-label">Data Início</label>
                    <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
                </div>
                <div class="col-md-3">
                    <label for="data_fim" class="form-label">Data Fim</label>
                    <input type="date" name="data_fim" id="data_fim" class="form-control" value="{{ request('data_fim') }}">
                </div>
                <div class="col-md-2">
                    <label for="tipo_veiculo" class="form-label">Tipo de Veículo</label>
                    <select name="tipo_veiculo" id="tipo_veiculo" class="form-select">
                        <option value="">Todos</option>
                        @foreach($tipos_veiculo as $tipo)
                            <option value="{{ $tipo }}" {{ request('tipo_veiculo') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="placa" class="form-label">Placa</label>
                    <select name="placa" id="placa" class="form-select">
                        <option value="">Todas</option>
                        @foreach($placas as $placa)
                            <option value="{{ $placa }}" {{ request('placa') == $placa ? 'selected' : '' }}>{{ $placa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark w-100">Filtrar</button>
                </div>
            </form>

            @if(request('usuario') || request('data_inicio') || request('data_fim'))
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#exportModal">Exportar PDF</button>
                <a href="{{ route('relatorios.viagens.excel', request()->all()) }}" class="btn btn-success">Exportar Excel</a>
            @else
                <div class="alert alert-info mt-3">Selecione um filtro e clique em "Filtrar" para visualizar e exportar os resultados.</div>
            @endif

            @if(isset($viagens) && count($viagens))
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Destino</th>
                                <th>Data</th>
                                <th>Condutor</th>
                                <th>KM Saída</th>
                                <th>KM Chegada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($viagens as $viagem)
                            <tr>
                                <td>{{ $viagem->id }}</td>
                                <td>{{ $viagem->destino }}</td>
                                <td>{{ \Carbon\Carbon::parse($viagem->data)->format('d/m/Y') }}</td>
                                <td>{{ $viagem->user->name ?? '-' }}</td>
                                <td>{{ $viagem->km_saida ?? '-' }}</td>
                                <td>{{ $viagem->km_chegada ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif(request()->all())
                <div class="alert alert-warning mt-3">Nenhum resultado encontrado para o filtro selecionado.</div>
            @endif
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exportModalLabel">Exportar Relatório PDF</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Como deseja exportar?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-info" onclick="exportPdf('view')">Mostrar em tela</button>
        <button type="button" class="btn btn-primary" onclick="exportPdf('print')">Imprimir</button>
        <button type="button" class="btn btn-success" onclick="exportPdf('download')">Salvar em PDF</button>
      </div>
    </div>
  </div>
</div>

<script>
function exportPdf(action) {
    let params = new URLSearchParams(window.location.search);
    params.set('action', action);
    let url = `{{ route('relatorios.viagens.pdf') }}?${params.toString()}`;
    if(action === 'print') {
        let win = window.open(url, '_blank');
        setTimeout(() => { win.print(); }, 1500);
    } else {
        window.open(url, '_blank');
    }
    var modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
    modal.hide();
}
</script>
@endsection
