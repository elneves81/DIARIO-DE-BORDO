@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 py-3">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header d-flex flex-column flex-md-row align-items-center justify-content-between py-3" style="background: linear-gradient(90deg, #0d47a1 0%, #1976d2 100%); color: #fff;">
                    <h5 class="mb-2 mb-md-0 d-flex align-items-center gap-2" style="color: #fff;">
                        <i class="bi bi-truck-front-fill"></i> Registros de Viagens
                    </h5>
                    <a href="{{ route('viagens.create') }}" class="btn btn-success fw-bold shadow-sm d-flex align-items-center gap-2" style="border: none;">
                        <i class="bi bi-plus-circle-fill"></i> Nova Viagem
                    </a>
                </div>
                <div class="card-body py-3">
                    @if(session('success'))
                        <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
                    @endif
                    <!-- Filtros organizados -->
                    <form method="GET" action="" class="row g-3 mb-3 align-items-end">
                        <div class="col-12 col-md-3">
                            <label for="filtro_destino" class="form-label mb-1">Destino</label>
                            <input type="text" name="destino" id="filtro_destino" value="{{ request('destino') }}" class="form-control" placeholder="Destino">
                        </div>
                        <div class="col-6 col-md-2">
                            <label for="filtro_data_ini" class="form-label mb-1">Data Inicial</label>
                            <input type="date" name="data_ini" id="filtro_data_ini" value="{{ request('data_ini') }}" class="form-control">
                        </div>
                        <div class="col-6 col-md-2">
                            <label for="filtro_data_fim" class="form-label mb-1">Data Final</label>
                            <input type="date" name="data_fim" id="filtro_data_fim" value="{{ request('data_fim') }}" class="form-control">
                        </div>
                        <div class="col-6 col-md-2">
                            <label for="filtro_tipo_veiculo" class="form-label mb-1">Veículo</label>
                            <input type="text" name="tipo_veiculo" id="filtro_tipo_veiculo" value="{{ request('tipo_veiculo') }}" class="form-control" placeholder="Tipo">
                        </div>
                        <div class="col-6 col-md-2">
                            <label for="filtro_condutor" class="form-label mb-1">Condutor</label>
                            <input type="text" name="condutor" id="filtro_condutor" value="{{ request('condutor') }}" class="form-control" placeholder="Condutor">
                        </div>
                        <div class="col-12 col-md-1 d-grid">
                            <button type="submit" class="btn btn-dark">Filtrar</button>
                        </div>
                    </form>
                    <!-- Tabela elegante e funcional -->
                    <div class="w-100 rounded shadow-sm" style="background: #fff; max-height: 70vh; overflow-y: auto;">
                        <table class="table table-hover table-striped align-middle text-center mb-0" style="font-size: 0.95rem;">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th class="py-3">Data</th>
                                    <th class="py-3">Saída<br><small class="text-light">(Hora/KM)</small></th>
                                    <th class="py-3">Destino</th>
                                    <th class="py-3">Chegada<br><small class="text-light">(Hora/KM)</small></th>
                                    <th class="py-3">Condutor</th>
                                    <th class="py-3" style="min-width: 160px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="viagens-list">
                                @forelse($viagens as $viagem)
                                    @include('viagens._row', ['viagens' => [$viagem]])
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Nenhuma viagem cadastrada.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- Container para scroll infinito -->
                        <div id="fim-dos-registros" class="mt-4 mb-2 text-center text-muted fw-bold" style="display:none;" aria-live="polite">Fim dos registros</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPage = {{ $viagens->currentPage() }};
const lastPage = {{ $viagens->lastPage() }};
let isLoading = false;
let infiniteScrollEnabled = true;

function showFimDosRegistros() {
    document.getElementById('fim-dos-registros').style.display = 'block';
    infiniteScrollEnabled = false;
    window.removeEventListener('scroll', window._viagensScrollHandler);
}
function hideFimDosRegistros() {
    document.getElementById('fim-dos-registros').style.display = 'none';
}

function hasVerticalScrollbar() {
    return document.documentElement.scrollHeight - window.innerHeight > 5;
}

function loadMoreViagens() {
    if (isLoading || currentPage >= lastPage || !infiniteScrollEnabled) return;
    isLoading = true;
    const params = new URLSearchParams(window.location.search);
    params.set('page', currentPage + 1);
    fetch(window.location.pathname + '?' + params.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
        if (!res.ok) throw new Error('Erro ao carregar viagens');
        return res.json();
    })
    .then(data => {
        if (data.html && data.html.trim() !== '') {
            document.getElementById('viagens-list').insertAdjacentHTML('beforeend', data.html);
            currentPage++;
            if (currentPage >= lastPage || !hasVerticalScrollbar()) {
                currentPage = lastPage;
                showFimDosRegistros();
            } else {
                hideFimDosRegistros();
            }
        } else {
            currentPage = lastPage;
            showFimDosRegistros();
        }
        isLoading = false;
    })
    .catch(() => {
        isLoading = false;
        currentPage = lastPage;
        showFimDosRegistros();
    });
}

(function() {
    let scrollTimeout;
    function infiniteScrollHandler() {
        if (!infiniteScrollEnabled) return;
        if (isLoading) return;
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            if (
                !isLoading &&
                currentPage < lastPage &&
                hasVerticalScrollbar() &&
                (window.innerHeight + window.scrollY) >= (document.body.offsetHeight - 200)
            ) {
                loadMoreViagens();
            } else {
                if (currentPage >= lastPage) showFimDosRegistros();
                else hideFimDosRegistros();
            }
        }, 200);
    }
    window._viagensScrollHandler = infiniteScrollHandler;
    window.addEventListener('scroll', infiniteScrollHandler);

    function checkInitialState() {
        if (currentPage >= lastPage || !hasVerticalScrollbar()) {
            showFimDosRegistros();
        } else {
            hideFimDosRegistros();
        }
    }
    document.addEventListener('DOMContentLoaded', checkInitialState);
    checkInitialState();

    // Esconde mensagem de fim ao submeter filtros
    document.querySelector('form').addEventListener('submit', function() {
        hideFimDosRegistros();
        infiniteScrollEnabled = true;
        currentPage = 1;
        window.addEventListener('scroll', window._viagensScrollHandler);
    });
})();
</script>
@endpush

<!-- Remover o atributo integrity dos links do Bootstrap para evitar bloqueio do recurso -->
{{--
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
--}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>