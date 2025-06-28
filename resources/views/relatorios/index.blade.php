@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#4e54c8 0%,#8f94fb 100%);color:#fff;">
            <i class="bi bi-bar-chart-line-fill fs-3"></i>
        </div>
        <h2 class="mb-0 fw-bold" style="color:#4e54c8;">Relatórios</h2>
    </div>
    <div class="card shadow border-0 rounded-4 mb-3" style="background: #f8fafd;">
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
                    <button type="submit" class="btn btn-primary w-100 d-flex align-items-center gap-2 fw-bold shadow-sm" style="background:linear-gradient(90deg,#4e54c8 0%,#8f94fb 100%);border:none;">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                </div>
            </form>

            @if(request('usuario') || request('data_inicio') || request('data_fim'))
                <div class="d-flex gap-2 mb-3 flex-wrap">
                    <button class="btn btn-info d-flex align-items-center gap-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#exportModal" style="background:linear-gradient(90deg,#43cea2 0%,#185a9d 100%);border:none;">
                        <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
                    </button>
                    <a href="{{ route('relatorios.viagens.excel', request()->all()) }}" class="btn btn-success d-flex align-items-center gap-2 fw-bold shadow-sm" style="background:linear-gradient(90deg,#43e97b 0%,#38f9d7 100%);border:none;">
                        <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                    </a>
                </div>
            @else
                <div class="alert alert-info mt-3 d-flex align-items-center gap-2"><i class="bi bi-info-circle-fill"></i> Selecione um filtro e clique em "Filtrar" para visualizar e exportar os resultados.</div>
            @endif

            @if(isset($viagens) && count($viagens))
                <div class="table-responsive mt-4">
                    <table class="table table-bordered align-middle text-center shadow-sm rounded-3 overflow-hidden" style="background:#fff;">
                        <thead style="background:linear-gradient(90deg,#4e54c8 0%,#8f94fb 100%);color:#fff;">
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
                            <tr style="background:{{ $loop->even ? '#f8fafd' : '#fff' }};">
                                <td><span class="badge bg-primary bg-opacity-75">{{ $viagem->id }}</span></td>
                                <td class="fw-semibold">{{ $viagem->destino }}</td>
                                <td><i class="bi bi-calendar2-week me-1 text-secondary"></i> {{ \Carbon\Carbon::parse($viagem->data)->format('d/m/Y') }}</td>
                                <td><i class="bi bi-person-circle me-1 text-info"></i> {{ $viagem->user->name ?? '-' }}</td>
                                <td><span class="badge bg-secondary bg-opacity-50">{{ $viagem->km_saida ?? '-' }}</span></td>
                                <td><span class="badge bg-success bg-opacity-50">{{ $viagem->km_chegada ?? '-' }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif(request()->all())
                <div class="alert alert-warning mt-3 d-flex align-items-center gap-2"><i class="bi bi-exclamation-triangle-fill"></i> Nenhum resultado encontrado para o filtro selecionado.</div>
            @endif
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4">
      <div class="modal-header" style="background:linear-gradient(90deg,#4e54c8 0%,#8f94fb 100%);color:#fff;">
        <h5 class="modal-title" id="exportModalLabel"><i class="bi bi-file-earmark-pdf me-2"></i>Exportar Relatório PDF</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <span class="fw-semibold">Como deseja exportar?</span>
      </div>
      <div class="modal-footer d-flex gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-info d-flex align-items-center gap-2" onclick="exportPdf('view')"><i class="bi bi-eye"></i> Mostrar em tela</button>
        <button type="button" class="btn btn-primary d-flex align-items-center gap-2" onclick="exportPdf('print')"><i class="bi bi-printer"></i> Imprimir</button>
        <button type="button" class="btn btn-success d-flex align-items-center gap-2" onclick="exportPdf('download')"><i class="bi bi-download"></i> Salvar em PDF</button>
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
