@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-4">
    <!-- Cabeçalho simples -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h3 class="fw-bold text-primary">Dashboard - Diário de Bordo</h3>
            <p class="text-muted">Gerencie suas viagens de forma eficiente</p>
            <!-- Botão para testar o modal -->
            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#welcomeModal">
                <i class="bi bi-info-circle me-1"></i>Ver Boas-vindas
            </button>
        </div>
    </div>

    <!-- Modal de Boas-vindas -->
    <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient text-white border-0" style="background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);">
                    <h5 class="modal-title fw-bold" id="welcomeModalLabel">
                        <i class="bi bi-emoji-sunglasses me-2"></i>Bem-vindo ao Sistema!
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="fw-bold text-primary mb-3">Olá, {{ Auth::user()->name }}!</h4>
                    <p class="text-muted mb-4">
                        É um prazer tê-lo(a) de volta ao <strong>Diário de Bordo</strong>. 
                        Aqui você pode gerenciar suas viagens de forma rápida e organizada.
                    </p>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body text-center py-2">
                                    <i class="bi bi-plus-circle text-success fs-3"></i>
                                    <p class="small mb-0 mt-1">Nova Viagem</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body text-center py-2">
                                    <i class="bi bi-list-check text-primary fs-3"></i>
                                    <p class="small mb-0 mt-1">Ver Viagens</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info border-0 bg-light">
                        <i class="bi bi-lightbulb me-2"></i>
                        <strong>Dica:</strong> Use os cards abaixo para navegar rapidamente pelas funcionalidades do sistema.
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-between">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="dontShowAgain">
                        <label class="form-check-label small text-muted" for="dontShowAgain">
                            Não mostrar novamente
                        </label>
                    </div>
                    <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-check-circle me-2"></i>Começar a usar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Cards principais com tons suaves -->
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 text-center shadow border-0" style="background: linear-gradient(135deg, #b6cee8 0%, #f578dc 100%); color: #333;">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3"><i class="bi bi-plus-circle-dotted me-2"></i>Nova Viagem</h5>
                    <p class="card-text">Cadastre uma nova viagem rapidamente.</p>
                    <a href="{{ route('viagens.create') }}" class="btn btn-outline-primary w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" tabindex="0" aria-label="Cadastrar nova viagem">
                        <i class="bi bi-geo-alt-fill"></i> Cadastrar Viagem
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 text-center shadow border-0" style="background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%); color: #333;">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3"><i class="bi bi-list-check me-2"></i>Minhas Viagens</h5>
                    <p class="card-text">Veja e gerencie todas as viagens cadastradas.</p>
                    <a href="{{ route('viagens.index') }}" class="btn btn-outline-success w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" tabindex="0" aria-label="Ver minhas viagens">
                        <i class="bi bi-journal-richtext"></i> Ver Viagens
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 text-center shadow border-0" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); color: #333;">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3"><i class="bi bi-person-circle me-2"></i>Meu Perfil</h5>
                    <p class="card-text">Atualize seus dados de usuário e senha.</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-info w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" tabindex="0" aria-label="Editar perfil">
                        <i class="bi bi-pencil-square"></i> Editar Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 text-center shadow border-0" style="background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%); color: #333;">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3"><i class="bi bi-bar-chart-line-fill me-2"></i>Relatórios</h5>
                    <p class="card-text">Exporte relatórios de viagens em PDF ou Excel.</p>
                    <a href="{{ route('relatorios.index') }}" class="btn btn-outline-primary w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" tabindex="0" aria-label="Acessar relatórios">
                        <i class="bi bi-file-earmark-bar-graph"></i> Acessar Relatórios
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 text-center shadow border-0" style="background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%); color: #333;">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3"><i class="bi bi-question-circle-fill me-2"></i>Ajuda</h5>
                    <p class="card-text">Precisa de suporte? Entre em contato com o administrador.</p>
                    <button type="button" class="btn btn-outline-danger w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#contatoModal" tabindex="0" aria-label="Fale conosco">
                        <i class="bi bi-chat-dots"></i> Fale Conosco
                    </button>
                    <!-- Modal de Contato -->
                    <div class="modal fade" id="contatoModal" tabindex="-1" aria-labelledby="contatoModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="contatoModalLabel">Fale Conosco</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                          </div>
                          <form action="{{ route('sugestoes.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                              <input type="hidden" name="tipo" value="contato">
                              <div class="mb-3">
                                <label for="mensagem_contato" class="form-label">Mensagem</label>
                                <textarea name="mensagem" id="mensagem_contato" class="form-control" rows="4" placeholder="Descreva sua dúvida, problema ou sugestão..." required aria-label="Mensagem de contato"></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                              <button type="submit" class="btn btn-outline-success">Enviar</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 text-center shadow border-0" style="background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%); color: #333;">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3"><i class="bi bi-gear-fill me-2"></i>Configurações</h5>
                    <p class="card-text">Personalize preferências do sistema e notificações.</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" tabindex="0" aria-label="Editar perfil e preferências">
                        <i class="bi bi-sliders"></i> Editar Perfil e Preferências
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Cards de resumo e sugestões -->
    <div class="row g-3 g-md-4">
        <div class="col-12 col-md-6 mb-4 mb-md-0">
            <div class="card h-100 text-center shadow border-0" style="background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%); color: #333;">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3"><i class="bi bi-graph-up-arrow me-2"></i>Resumo Rápido</h5>
                    @php
                        $totalViagens = \App\Models\Viagem::where('user_id', Auth::id())->count();
                        $ultimaViagem = \App\Models\Viagem::where('user_id', Auth::id())->orderByDesc('data')->orderByDesc('created_at')->first();
                        use Carbon\Carbon;
                        $hoje = Carbon::today();
                        $inicioMes = Carbon::now()->startOfMonth();
                        $user = Auth::user();
                        $viagensDia = \App\Models\Viagem::where('condutor', $user->name)
                            ->whereDate('data', $hoje)
                            ->get();
                        $kmDia = $viagensDia->filter(function($v) {
                            return isset($v->km_saida, $v->km_chegada) && $v->km_chegada >= $v->km_saida;
                        })->sum(function($v) {
                            return ($v->km_chegada ?? 0) - ($v->km_saida ?? 0);
                        });
                        $viagensMes = \App\Models\Viagem::where('condutor', $user->name)
                            ->whereBetween('data', [$inicioMes, $hoje])
                            ->get();
                        $kmMes = $viagensMes->filter(function($v) {
                            return isset($v->km_saida, $v->km_chegada) && $v->km_chegada >= $v->km_saida;
                        })->sum(function($v) {
                            return ($v->km_chegada ?? 0) - ($v->km_saida ?? 0);
                        });
                        $kmDia = max(0, $kmDia);
                        $kmMes = max(0, $kmMes);
                    @endphp
                    <ul class="list-group mb-3 text-start">
                        <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-transparent border-0 text-dark">
                            <span><strong><i class="bi bi-speedometer2 me-2"></i>KMs rodados hoje ({{ $hoje->format('d/m/Y') }}):</strong></span>
                            <span class="badge rounded-pill px-3 py-2 bg-primary bg-opacity-25 text-primary" style="font-size:1rem;"><i class="bi bi-lightning-charge-fill me-1"></i> {{ $kmDia }}</span>
                        </li>
                        <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-transparent border-0 text-dark">
                            <span><strong><i class="bi bi-calendar2-week-fill me-2"></i>KMs rodados no mês ({{ $inicioMes->format('m/Y') }}):</strong></span>
                            <span class="badge rounded-pill px-3 py-2 bg-success bg-opacity-25 text-success" style="font-size:1rem;"><i class="bi bi-calendar2-check-fill me-1"></i> {{ $kmMes }}</span>
                        </li>
                        <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-transparent border-0 text-dark">
                            <span><i class="bi bi-collection me-2"></i>Total de viagens cadastradas:</span>
                            <span class="badge rounded-pill px-3 py-2 bg-warning bg-opacity-25 text-warning" style="font-size:1rem;"><i class="bi bi-flag-fill me-1"></i> {{ $totalViagens }}</span>
                        </li>
                        <li class="list-group-item bg-transparent border-0 text-dark">
                            <strong><i class="bi bi-clock-history me-2"></i>Última viagem:</strong><br>
                            @if($ultimaViagem)
                                Data: {{ $ultimaViagem->data instanceof \Carbon\Carbon ? $ultimaViagem->data->format('d/m/Y') : $ultimaViagem->data }}<br>
                                Destino: {{ $ultimaViagem->destino }}<br>
                                KM Saída: {{ $ultimaViagem->km_saida }}<br>
                                KM Chegada: {{ $ultimaViagem->km_chegada }}
                                <a href="{{ route('viagens.edit', $ultimaViagem->id) }}" class="btn btn-outline-warning btn-sm ms-2 mt-2 mt-md-0 w-100 w-md-auto d-inline-flex align-items-center gap-2" tabindex="0" aria-label="Editar última viagem">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                            @else
                                Nenhuma viagem cadastrada ainda.
                            @endif
                        </li>
                    </ul>
                    <a href="{{ route('viagens.index') }}" class="btn btn-outline-secondary w-100 fw-bold d-flex align-items-center justify-content-center gap-2" tabindex="0" aria-label="Ver detalhes das viagens">
                        <i class="bi bi-eye"></i> Ver Detalhes
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card h-100 text-center shadow border-0" style="background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%); color: #333;">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3"><i class="bi bi-lightbulb-fill me-2"></i>Sugestões</h5>
                    <p class="card-text">Envie ideias para melhorar o sistema.</p>
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('sugestoes.store') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <textarea name="mensagem" class="form-control" rows="3" placeholder="Digite sua sugestão..." required aria-label="Sugestão"></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-secondary w-100 fw-bold d-flex align-items-center justify-content-center gap-2" tabindex="0" aria-label="Enviar sugestão">
                            <i class="bi bi-send"></i> Enviar Sugestão
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Gráfico -->
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12">
            <div class="card h-100 text-center shadow border-0 bg-transparent mb-4">
                <div class="card-body">
                    <h5 class="card-title text-dark"><i class="bi bi-graph-up me-2"></i>Evolução das Viagens (Últimos 12 meses)</h5>
                    <canvas id="viagensChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Script do gráfico
    const ctx = document.getElementById('viagensChart').getContext('2d');
    const viagensChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels ?? []),
            datasets: [{
                label: 'Viagens por mês',
                data: @json($data ?? []),
                borderColor: '#4e54c8',
                backgroundColor: 'rgba(142,149,251,0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            scales: {
                y: { beginAtZero: true, precision: 0 }
            }
        }
    });

    // Script do popup de boas-vindas corrigido
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard carregado - verificando popup');
        
        // Verifica se o usuário marcou para não mostrar novamente
        const dontShowAgain = localStorage.getItem('welcomePopupDontShow');
        console.log('DontShowAgain status:', dontShowAgain);
        
        if (dontShowAgain !== 'true') {
            // Só mostra se o usuário não marcou para não mostrar
            setTimeout(function() {
                console.log('Tentando mostrar o modal');
                const modalElement = document.getElementById('welcomeModal');
                if (modalElement) {
                    console.log('Modal encontrado, criando instância Bootstrap');
                    const welcomeModal = new bootstrap.Modal(modalElement);
                    welcomeModal.show();
                    console.log('Modal mostrado');
                } else {
                    console.error('Modal não encontrado!');
                }
            }, 2000);
        } else {
            console.log('Popup não será mostrado - usuário optou por não ver novamente');
        }

        // Quando o modal for fechado
        const modalElement = document.getElementById('welcomeModal');
        if (modalElement) {
            modalElement.addEventListener('hidden.bs.modal', function() {
                const checkbox = document.getElementById('dontShowAgain');
                if (checkbox && checkbox.checked) {
                    localStorage.setItem('welcomePopupDontShow', 'true');
                    console.log('Usuário escolheu não ver mais o popup - salvo no localStorage');
                }
            });
        }
        
        // Função para resetar (caso queira testar novamente)
        window.resetWelcomePopup = function() {
            localStorage.removeItem('welcomePopupDontShow');
            console.log('Popup resetado - será mostrado novamente no próximo carregamento');
        };
    });
</script>
@endsection
