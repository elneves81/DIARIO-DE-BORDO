@extends('layouts.app')

@section('content')
<style>
    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.8s ease-out forwards;
    }

    .fade-in-delay-1 { animation-delay: 0.1s; }
    .fade-in-delay-2 { animation-delay: 0.2s; }
    .fade-in-delay-3 { animation-delay: 0.3s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }

    .btn-gradient {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn-gradient:hover::before {
        left: 100%;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .card-modern {
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .table-modern {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    }

    .badge-modern {
        font-size: 0.8rem;
        padding: 0.5rem 0.8rem;
        border-radius: 20px;
        font-weight: 500;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4e54c8;
        box-shadow: 0 0 0 0.2rem rgba(78, 84, 200, 0.25);
        transform: scale(1.02);
        transition: all 0.3s ease;
    }

    .loading-spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #4e54c8;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    @media (max-width: 768px) {
        .btn-responsive {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        
        .card-body {
            padding: 1.5rem 1rem;
        }
    }
</style>

<div class="container py-4">
    <div class="d-flex align-items-center gap-3 mb-4 fade-in">
        <div class="rounded-circle d-flex align-items-center justify-content-center hover-lift" style="width:56px;height:56px;background:linear-gradient(135deg,#4e54c8 0%,#8f94fb 100%);color:#fff;box-shadow: 0 4px 15px rgba(78,84,200,0.3);">
            <i class="bi bi-bar-chart-line-fill fs-2"></i>
        </div>
        <div>
            <h2 class="mb-0 fw-bold" style="color:#4e54c8;">Relatórios</h2>
            <p class="text-muted mb-0 small">Gere e visualize relatórios de viagens</p>
        </div>
    </div>
    <div class="card card-modern shadow-lg border-0 rounded-4 mb-4 fade-in fade-in-delay-1" style="background: linear-gradient(135deg, #f8fafd 0%, #ffffff 100%);">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-funnel-fill text-primary"></i>
                <h5 class="mb-0 fw-bold text-primary">Filtros de Pesquisa</h5>
            </div>
            
            <form class="row g-3 mb-3" method="GET" action="{{ route('relatorios.index') }}" id="filterForm">
                <div class="col-12 col-md-4">
                    <label for="usuario" class="form-label fw-semibold">
                        <i class="bi bi-person-circle me-1"></i>Condutor
                    </label>
                    <select name="usuario" id="usuario" class="form-select shadow-sm" {{ !(auth()->check() && auth()->user()->isAdmin()) ? 'disabled' : '' }}>
                        <option value="">🧑‍💼 Todos os condutores</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->name }}" {{ request('usuario', auth()->user()->name) == $usuario->name ? 'selected' : '' }}>{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                    @if(!(auth()->check() && auth()->user()->isAdmin()))
                        <input type="hidden" name="usuario" value="{{ auth()->user()->name }}">
                    @endif
                </div>
                <div class="col-6 col-md-3">
                    <label for="data_inicio" class="form-label fw-semibold">
                        <i class="bi bi-calendar-date me-1"></i>Data Início
                    </label>
                    <input type="date" name="data_inicio" id="data_inicio" class="form-control shadow-sm" value="{{ request('data_inicio') }}">
                </div>
                <div class="col-6 col-md-3">
                    <label for="data_fim" class="form-label fw-semibold">
                        <i class="bi bi-calendar-check me-1"></i>Data Fim
                    </label>
                    <input type="date" name="data_fim" id="data_fim" class="form-control shadow-sm" value="{{ request('data_fim') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label for="tipo_veiculo" class="form-label fw-semibold">
                        <i class="bi bi-truck me-1"></i>Veículo
                    </label>
                    <select name="tipo_veiculo" id="tipo_veiculo" class="form-select shadow-sm">
                        <option value="">🚗 Todos</option>
                        @foreach($tipos_veiculo as $tipo)
                            <option value="{{ $tipo }}" {{ request('tipo_veiculo') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="placa" class="form-label fw-semibold">
                        <i class="bi bi-credit-card me-1"></i>Placa
                    </label>
                    <select name="placa" id="placa" class="form-select shadow-sm">
                        <option value="">📋 Todas</option>
                        @foreach($placas as $placa)
                            <option value="{{ $placa }}" {{ request('placa') == $placa ? 'selected' : '' }}>{{ $placa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-gradient w-100 d-flex align-items-center justify-content-center gap-2 fw-bold shadow-sm btn-responsive" style="background:linear-gradient(90deg,#4e54c8 0%,#8f94fb 100%);border:none;padding:0.75rem 1rem;">
                        <i class="bi bi-search"></i> 
                        <span>Filtrar</span>
                        <div class="loading-spinner" id="loadingSpinner"></div>
                    </button>
                </div>
            </form>

            @if(request('usuario') || request('data_inicio') || request('data_fim'))
                <div class="fade-in fade-in-delay-2">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-download text-success"></i>
                        <h6 class="mb-0 fw-bold text-success">Opções de Exportação</h6>
                    </div>
                    <div class="d-flex gap-2 mb-3 flex-wrap">
                        <button class="btn btn-info btn-gradient d-flex align-items-center gap-2 fw-bold shadow-sm btn-responsive hover-lift" data-bs-toggle="modal" data-bs-target="#exportModal" style="background:linear-gradient(90deg,#43cea2 0%,#185a9d 100%);border:none;">
                            <i class="bi bi-file-earmark-pdf"></i> 
                            <span>Exportar PDF</span>
                        </button>
                        <a href="{{ route('relatorios.viagens.excel', request()->all()) }}" class="btn btn-success btn-gradient d-flex align-items-center gap-2 fw-bold shadow-sm btn-responsive hover-lift" style="background:linear-gradient(90deg,#43e97b 0%,#38f9d7 100%);border:none;">
                            <i class="bi bi-file-earmark-excel"></i> 
                            <span>Exportar Excel</span>
                        </a>
                        <button class="btn btn-outline-secondary btn-gradient d-flex align-items-center gap-2 fw-bold shadow-sm btn-responsive" onclick="clearFilters()">
                            <i class="bi bi-x-circle"></i> 
                            <span>Limpar Filtros</span>
                        </button>
                    </div>
                </div>
            @else
                <div class="alert alert-info mt-3 d-flex align-items-center gap-3 fade-in fade-in-delay-2" style="background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%); border: none; border-radius: 12px;">
                    <div class="pulse-animation">
                        <i class="bi bi-info-circle-fill fs-4 text-primary"></i>
                    </div>
                    <div>
                        <strong>Como usar:</strong> Selecione um filtro e clique em "Filtrar" para visualizar e exportar os resultados.
                    </div>
                </div>
            @endif

            @if(isset($viagens) && count($viagens))
                <div class="fade-in fade-in-delay-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-table text-primary"></i>
                            <h6 class="mb-0 fw-bold text-primary">Resultados da Pesquisa</h6>
                        </div>
                        <div class="badge badge-modern bg-primary bg-opacity-10 text-primary">
                            {{ count($viagens) }} {{ count($viagens) == 1 ? 'registro' : 'registros' }} encontrado{{ count($viagens) == 1 ? '' : 's' }}
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-modern table-hover align-middle text-center mb-0" style="background:#fff;">
                            <thead style="background:linear-gradient(90deg,#4e54c8 0%,#8f94fb 100%);color:#fff;">
                                <tr>
                                    <th class="py-3"><i class="bi bi-hash me-1"></i>ID</th>
                                    <th class="py-3"><i class="bi bi-geo-alt me-1"></i>Destino</th>
                                    <th class="py-3"><i class="bi bi-calendar3 me-1"></i>Data</th>
                                    <th class="py-3"><i class="bi bi-person-badge me-1"></i>Condutor</th>
                                    <th class="py-3"><i class="bi bi-speedometer me-1"></i>KM Saída</th>
                                    <th class="py-3"><i class="bi bi-speedometer2 me-1"></i>KM Chegada</th>
                                    <th class="py-3"><i class="bi bi-calculator me-1"></i>KM Percorrido</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($viagens as $viagem)
                                <tr class="hover-lift" style="background:{{ $loop->even ? '#f8fafd' : '#fff' }}; transition: all 0.3s ease;">
                                    <td>
                                        <span class="badge badge-modern bg-primary bg-opacity-75 text-white">
                                            #{{ $viagem->id }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold text-start">
                                        <i class="bi bi-pin-map-fill me-1 text-danger"></i>
                                        {{ $viagem->destino }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            <i class="bi bi-calendar2-week text-secondary"></i>
                                            <span class="fw-medium">{{ \Carbon\Carbon::parse($viagem->data)->format('d/m/Y') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <div class="rounded-circle bg-info bg-opacity-10 p-1">
                                                <i class="bi bi-person-circle text-info"></i>
                                            </div>
                                            <span class="fw-medium">{{ $viagem->user->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern bg-secondary bg-opacity-50 text-dark">
                                            {{ number_format($viagem->km_saida ?? 0, 0, ',', '.') }} km
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern bg-success bg-opacity-50 text-dark">
                                            {{ number_format($viagem->km_chegada ?? 0, 0, ',', '.') }} km
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $kmPercorrido = ($viagem->km_chegada ?? 0) - ($viagem->km_saida ?? 0);
                                        @endphp
                                        <span class="badge badge-modern {{ $kmPercorrido > 0 ? 'bg-warning' : 'bg-light' }} bg-opacity-75 text-dark">
                                            {{ number_format($kmPercorrido, 0, ',', '.') }} km
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Resumo estatístico -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card card-modern bg-primary bg-opacity-10 border-0 text-center p-3">
                                <i class="bi bi-graph-up-arrow fs-2 text-primary mb-2"></i>
                                <h6 class="mb-1 text-primary">Total de Viagens</h6>
                                <h4 class="mb-0 fw-bold text-primary">{{ count($viagens) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-modern bg-success bg-opacity-10 border-0 text-center p-3">
                                <i class="bi bi-speedometer2 fs-2 text-success mb-2"></i>
                                <h6 class="mb-1 text-success">Total KM</h6>
                                <h4 class="mb-0 fw-bold text-success">
                                    {{ number_format($viagens->sum(function($v) { return ($v->km_chegada ?? 0) - ($v->km_saida ?? 0); }), 0, ',', '.') }} km
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-modern bg-warning bg-opacity-10 border-0 text-center p-3">
                                <i class="bi bi-bar-chart-line fs-2 text-warning mb-2"></i>
                                <h6 class="mb-1 text-warning">Média por Viagem</h6>
                                <h4 class="mb-0 fw-bold text-warning">
                                    {{ count($viagens) > 0 ? number_format($viagens->sum(function($v) { return ($v->km_chegada ?? 0) - ($v->km_saida ?? 0); }) / count($viagens), 0, ',', '.') : 0 }} km
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-modern bg-info bg-opacity-10 border-0 text-center p-3">
                                <i class="bi bi-people-fill fs-2 text-info mb-2"></i>
                                <h6 class="mb-1 text-info">Condutores</h6>
                                <h4 class="mb-0 fw-bold text-info">{{ $viagens->unique('user.name')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(request()->all())
                <div class="alert alert-warning mt-3 d-flex align-items-center gap-3 fade-in fade-in-delay-2" style="background: linear-gradient(135deg, #fff3cd 0%, #f8f9fa 100%); border: none; border-radius: 12px;">
                    <div class="pulse-animation">
                        <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
                    </div>
                    <div>
                        <strong>Nenhum resultado encontrado!</strong> Tente ajustar os filtros da pesquisa.
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Melhorado -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header" style="background:linear-gradient(90deg,#4e54c8 0%,#8f94fb 100%);color:#fff;border-radius:16px 16px 0 0;">
                <h5 class="modal-title fw-bold" id="exportModalLabel">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Exportar Relatório PDF
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <i class="bi bi-file-earmark-pdf fs-1 text-primary mb-2"></i>
                    <p class="fw-semibold mb-0">Como deseja exportar o relatório?</p>
                    <small class="text-muted">Escolha uma das opções abaixo</small>
                </div>
                <div class="row g-2">
                    <div class="col-12">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-info btn-lg d-flex align-items-center justify-content-center gap-2 hover-lift" onclick="exportPdf('view')">
                                <i class="bi bi-eye"></i> 
                                <span>Mostrar em Tela</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-center gap-2 hover-lift" onclick="exportPdf('print')">
                                <i class="bi bi-printer"></i> 
                                <span>Imprimir</span>
                            </button>
                            <button type="button" class="btn btn-outline-success btn-lg d-flex align-items-center justify-content-center gap-2 hover-lift" onclick="exportPdf('download')">
                                <i class="bi bi-download"></i> 
                                <span>Baixar PDF</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let pdfGenerated = false;

function exportPdf(action) {
    let params = new URLSearchParams(window.location.search);
    params.set('action', action);
    let url = `{{ route('relatorios.viagens.pdf') }}?${params.toString()}`;
    
    // Marca que um PDF foi gerado
    pdfGenerated = true;
    
    // Mostra feedback visual
    const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
    
    if(action === 'print') {
        let win = window.open(url, '_blank');
        setTimeout(() => { win.print(); }, 1500);
    } else {
        window.open(url, '_blank');
    }
    
    modal.hide();
}

function clearFilters() {
    window.location.href = "{{ route('relatorios.index') }}";
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Modal events
    const exportModal = document.getElementById('exportModal');
    if (exportModal) {
        exportModal.addEventListener('hidden.bs.modal', function() {
            if (pdfGenerated) {
                setTimeout(() => {
                    location.reload();
                }, 500);
            }
        });
        
        exportModal.addEventListener('shown.bs.modal', function() {
            pdfGenerated = false;
        });
    }
    
    // Form submission with loading
    const filterForm = document.getElementById('filterForm');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            loadingSpinner.style.display = 'block';
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
    
    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
            this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>
@endsection
