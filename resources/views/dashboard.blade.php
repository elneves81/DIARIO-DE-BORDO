@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-4">
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="mt-2 text-muted">Carregando dados...</p>
        </div>
    </div>

    <!-- Cabeçalho Profissional e Robusto -->
    <div class="row mb-5 fade-in">
        <div class="col-12">
            <div class="professional-header">
                <div class="header-background">
                    <div class="header-overlay"></div>
                    <div class="header-content">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="header-info">
                                    <div class="welcome-badge">
                                        <i class="bi bi-person-check-fill me-2"></i>
                                        Bem-vindo, {{ Auth::user()->name }}
                                    </div>
                                    <h1 class="header-title">
                                        <span class="title-main">Dashboard</span>
                                        <span class="title-subtitle">Executivo</span>
                                    </h1>
                                    <p class="header-description">
                                        <i class="bi bi-geo-alt-fill me-2"></i>
                                        Central de Controle e Gestão de Viagens Corporativas
                                    </p>
                                    <div class="header-stats">
                                        @php
                                            $totalViagensUser = \App\Models\Viagem::where('user_id', Auth::id())->count();
                                            $viagensHoje = \App\Models\Viagem::where('user_id', Auth::id())->whereDate('data', today())->count();
                                        @endphp
                                        <div class="stat-item-header">
                                            <span class="stat-number">{{ $totalViagensUser }}</span>
                                            <span class="stat-label">Total de Viagens</span>
                                        </div>
                                        <div class="stat-divider"></div>
                                        <div class="stat-item-header">
                                            <span class="stat-number">{{ $viagensHoje }}</span>
                                            <span class="stat-label">Hoje</span>
                                        </div>
                                        <div class="stat-divider"></div>
                                        <div class="stat-item-header">
                                            <span class="stat-number">{{ now()->format('d/m/Y') }}</span>
                                            <span class="stat-label">Data Atual</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="header-actions-professional">
                                    <div class="action-group">
                                        <button type="button" class="btn-professional btn-primary-pro dashboard-btn-fix" data-bs-toggle="modal" data-bs-target="#welcomeModal">
                                            <div class="btn-icon">
                                                <i class="bi bi-info-circle-fill"></i>
                                            </div>
                                            <div class="btn-content">
                                                <span class="btn-title">Boas-vindas</span>
                                                <span class="btn-subtitle">Guia do Sistema</span>
                                            </div>
                                        </button>
                                        
                                        <button type="button" class="btn-professional btn-success-pro dashboard-btn-fix" onclick="location.reload()">
                                            <div class="btn-icon">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </div>
                                            <div class="btn-content">
                                                <span class="btn-title">Atualizar</span>
                                                <span class="btn-subtitle">Dados em Tempo Real</span>
                                            </div>
                                        </button>
                                    </div>
                                    
                                    <div class="quick-actions">
                                        <button type="button" class="quick-btn" onclick="window.location.href='{{ route('viagens.create') }}'">
                                            <i class="bi bi-plus-lg"></i>
                                            <span>Nova Viagem</span>
                                        </button>
                                        <button type="button" class="quick-btn" onclick="window.location.href='{{ route('relatorios.index') }}'">
                                            <i class="bi bi-file-earmark-bar-graph"></i>
                                            <span>Relatórios</span>
                                        </button>
                                        <button type="button" class="quick-btn" data-bs-toggle="modal" data-bs-target="#contatoModal">
                                            <i class="bi bi-headset"></i>
                                            <span>Suporte</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Boas-vindas Modernizado -->
    <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modern-modal">
                <div class="modal-header modern-modal-header">
                    <h5 class="modal-title fw-bold" id="welcomeModalLabel">
                        <i class="bi bi-rocket-takeoff me-2"></i>Bem-vindo ao Sistema!
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <div class="welcome-icon">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3 gradient-text">Olá, {{ Auth::user()->name }}!</h4>
                    <p class="text-muted mb-4 lead">
                        É um prazer tê-lo(a) de volta ao <strong>Diário de Bordo</strong>. 
                        Aqui você pode gerenciar suas viagens de forma rápida e organizada.
                    </p>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="welcome-feature-card">
                                <div class="feature-icon bg-success">
                                    <i class="bi bi-plus-circle"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Nova Viagem</h6>
                                <p class="small text-muted mb-0">Cadastre rapidamente</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="welcome-feature-card">
                                <div class="feature-icon bg-primary">
                                    <i class="bi bi-list-check"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Gerenciar</h6>
                                <p class="small text-muted mb-0">Acompanhe suas viagens</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info modern-alert">
                        <i class="bi bi-lightbulb me-2"></i>
                        <strong>Dica:</strong> Use os cards do dashboard para navegar rapidamente pelas funcionalidades.
                    </div>
                </div>
                <div class="modal-footer modern-modal-footer">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="dontShowAgain">
                        <label class="form-check-label text-muted" for="dontShowAgain">
                            Não mostrar novamente
                        </label>
                    </div>
                    <button type="button" class="btn btn-primary modern-btn px-4" data-bs-dismiss="modal">
                        <i class="bi bi-check-circle me-2"></i>Vamos começar!
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Cards Executivos Profissionais -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="executive-card success-card" data-aos="fade-up" data-aos-delay="100">
                <div class="card-background">
                    <div class="card-overlay"></div>
                    <div class="card-content-executive">
                        <div class="card-header-executive">
                            <div class="card-icon-executive success-icon">
                                <i class="bi bi-plus-circle-fill"></i>
                            </div>
                            <div class="card-badge">
                                <span>Ação Rápida</span>
                            </div>
                        </div>
                        <div class="card-body-executive">
                            <h3 class="card-title-executive">Nova Viagem</h3>
                            <p class="card-description-executive">
                                Cadastre uma nova viagem rapidamente e organize seus deslocamentos corporativos.
                            </p>
                            <div class="card-stats">
                                <div class="stat-mini">
                                    <span class="stat-number-mini">{{ \App\Models\Viagem::where('user_id', Auth::id())->whereDate('created_at', today())->count() }}</span>
                                    <span class="stat-label-mini">Hoje</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer-executive">
                            <a href="{{ route('viagens.create') }}" class="btn-executive btn-success-exec">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Cadastrar Viagem</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="executive-card primary-card" data-aos="fade-up" data-aos-delay="200">
                <div class="card-background">
                    <div class="card-overlay"></div>
                    <div class="card-content-executive">
                        <div class="card-header-executive">
                            <div class="card-icon-executive primary-icon">
                                <i class="bi bi-list-check"></i>
                            </div>
                            <div class="card-badge">
                                <span>Gestão</span>
                            </div>
                        </div>
                        <div class="card-body-executive">
                            <h3 class="card-title-executive">Minhas Viagens</h3>
                            <p class="card-description-executive">
                                Visualize e gerencie todas as suas viagens cadastradas no sistema.
                            </p>
                            <div class="card-stats">
                                <div class="stat-mini">
                                    <span class="stat-number-mini">{{ \App\Models\Viagem::where('user_id', Auth::id())->count() }}</span>
                                    <span class="stat-label-mini">Total</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer-executive">
                            <a href="{{ route('viagens.index') }}" class="btn-executive btn-primary-exec">
                                <i class="bi bi-journal-richtext"></i>
                                <span>Ver Viagens</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="executive-card info-card" data-aos="fade-up" data-aos-delay="300">
                <div class="card-background">
                    <div class="card-overlay"></div>
                    <div class="card-content-executive">
                        <div class="card-header-executive">
                            <div class="card-icon-executive info-icon">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="card-badge">
                                <span>Perfil</span>
                            </div>
                        </div>
                        <div class="card-body-executive">
                            <h3 class="card-title-executive">Meu Perfil</h3>
                            <p class="card-description-executive">
                                Atualize seus dados pessoais, senha e preferências do sistema.
                            </p>
                            <div class="card-stats">
                                <div class="stat-mini">
                                    <span class="stat-number-mini">{{ Auth::user()->created_at->diffInDays() }}</span>
                                    <span class="stat-label-mini">Dias</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer-executive">
                            <a href="{{ route('profile.edit') }}" class="btn-executive btn-info-exec">
                                <i class="bi bi-pencil-square"></i>
                                <span>Editar Perfil</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda linha de cards executivos -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="executive-card warning-card" data-aos="fade-up" data-aos-delay="400">
                <div class="card-background">
                    <div class="card-overlay"></div>
                    <div class="card-content-executive">
                        <div class="card-header-executive">
                            <div class="card-icon-executive warning-icon">
                                <i class="bi bi-bar-chart-line-fill"></i>
                            </div>
                            <div class="card-badge">
                                <span>Analytics</span>
                            </div>
                        </div>
                        <div class="card-body-executive">
                            <h3 class="card-title-executive">Relatórios</h3>
                            <p class="card-description-executive">
                                Gere relatórios detalhados das suas viagens em PDF ou Excel.
                            </p>
                            <div class="card-stats">
                                <div class="stat-mini">
                                    <span class="stat-number-mini">{{ \App\Models\Viagem::where('user_id', Auth::id())->whereMonth('created_at', now()->month)->count() }}</span>
                                    <span class="stat-label-mini">Mês</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer-executive">
                            <a href="{{ route('relatorios.index') }}" class="btn-executive btn-warning-exec">
                                <i class="bi bi-file-earmark-bar-graph"></i>
                                <span>Acessar Relatórios</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="executive-card danger-card" data-aos="fade-up" data-aos-delay="500">
                <div class="card-background">
                    <div class="card-overlay"></div>
                    <div class="card-content-executive">
                        <div class="card-header-executive">
                            <div class="card-icon-executive danger-icon">
                                <i class="bi bi-question-circle-fill"></i>
                            </div>
                            <div class="card-badge">
                                <span>Suporte</span>
                            </div>
                        </div>
                        <div class="card-body-executive">
                            <h3 class="card-title-executive">Suporte</h3>
                            <p class="card-description-executive">
                                Precisa de ajuda? Entre em contato com nossa equipe de suporte.
                            </p>
                            <div class="card-stats">
                                <div class="stat-mini">
                                    <span class="stat-number-mini">24h</span>
                                    <span class="stat-label-mini">Resposta</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer-executive">
                            <button type="button" class="btn-executive btn-danger-exec" data-bs-toggle="modal" data-bs-target="#contatoModal">
                                <i class="bi bi-chat-dots"></i>
                                <span>Fale Conosco</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="executive-card secondary-card" data-aos="fade-up" data-aos-delay="600">
                <div class="card-background">
                    <div class="card-overlay"></div>
                    <div class="card-content-executive">
                        <div class="card-header-executive">
                            <div class="card-icon-executive secondary-icon">
                                <i class="bi bi-gear-fill"></i>
                            </div>
                            <div class="card-badge">
                                <span>Sistema</span>
                            </div>
                        </div>
                        <div class="card-body-executive">
                            <h3 class="card-title-executive">Configurações</h3>
                            <p class="card-description-executive">
                                Personalize suas preferências e configurações do sistema.
                            </p>
                            <div class="card-stats">
                                <div class="stat-mini">
                                    <span class="stat-number-mini">{{ Auth::user()->is_admin ? 'Admin' : 'User' }}</span>
                                    <span class="stat-label-mini">Nível</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer-executive">
                            <a href="{{ route('profile.edit') }}" class="btn-executive btn-secondary-exec">
                                <i class="bi bi-sliders"></i>
                                <span>Configurar Sistema</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Contato Modernizado -->
    <div class="modal fade" id="contatoModal" tabindex="-1" aria-labelledby="contatoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modern-modal">
                <div class="modal-header modern-modal-header">
                    <h5 class="modal-title fw-bold" id="contatoModalLabel">
                        <i class="bi bi-headset me-2"></i>Fale Conosco
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form action="{{ route('sugestoes.store') }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <input type="hidden" name="tipo" value="contato">
                        <div class="mb-4">
                            <label for="mensagem_contato" class="form-label fw-semibold">
                                <i class="bi bi-chat-text me-2"></i>Sua mensagem
                            </label>
                            <textarea name="mensagem" id="mensagem_contato" class="form-control modern-input" rows="5" placeholder="Descreva sua dúvida, problema ou sugestão..." required></textarea>
                        </div>
                        <div class="alert alert-info modern-alert">
                            <i class="bi bi-info-circle me-2"></i>
                            Nossa equipe responderá em até 24 horas úteis.
                        </div>
                    </div>
                    <div class="modal-footer modern-modal-footer">
                        <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-success modern-btn">
                            <i class="bi bi-send me-2"></i>Enviar Mensagem
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Seção de Estatísticas -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-lg-8">
            <div class="stats-card" data-aos="fade-up" data-aos-delay="100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up-arrow me-2"></i>Resumo das Suas Viagens
                    </h5>
                </div>
                <div class="card-body">
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
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="stat-item">
                                <div class="stat-value">{{ $kmDia }}</div>
                                <div class="stat-label">KM Hoje</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-item">
                                <div class="stat-value">{{ $kmMes }}</div>
                                <div class="stat-label">KM no Mês</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-item">
                                <div class="stat-value">{{ $totalViagens }}</div>
                                <div class="stat-label">Total Viagens</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-item">
                                <div class="stat-value">{{ $viagensMes->count() }}</div>
                                <div class="stat-label">Viagens no Mês</div>
                            </div>
                        </div>
                    </div>

                    @if($ultimaViagem)
                    <div class="last-trip-info">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-clock-history me-2"></i>Última Viagem
                        </h6>
                        <div class="trip-details">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <strong>Data:</strong> {{ $ultimaViagem->data instanceof \Carbon\Carbon ? $ultimaViagem->data->format('d/m/Y') : $ultimaViagem->data }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Destino:</strong> {{ $ultimaViagem->destino }}
                                </div>
                                <div class="col-md-6">
                                    <strong>KM Saída:</strong> {{ $ultimaViagem->km_saida }}
                                </div>
                                <div class="col-md-6">
                                    <strong>KM Chegada:</strong> {{ $ultimaViagem->km_chegada }}
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('viagens.edit', $ultimaViagem->id) }}" class="btn btn-warning btn-sm modern-btn">
                                    <i class="bi bi-pencil me-2"></i>Editar
                                </a>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="bi bi-geo-alt text-muted mb-3"></i>
                        <p class="text-muted mb-3">Nenhuma viagem cadastrada ainda.</p>
                        <a href="{{ route('viagens.create') }}" class="btn btn-primary modern-btn">
                            <i class="bi bi-plus-circle me-2"></i>Cadastrar Primeira Viagem
                        </a>
                    </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('viagens.index') }}" class="btn btn-outline-primary modern-btn">
                            <i class="bi bi-eye me-2"></i>Ver Todas as Viagens
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="stats-card" data-aos="fade-up" data-aos-delay="200">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightbulb-fill me-2"></i>Sugestões
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Envie suas ideias para melhorar o sistema.</p>
                    
                    @if(session('success'))
                        <div class="alert alert-success modern-alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('sugestoes.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="mensagem" class="form-control modern-input" rows="4" placeholder="Digite sua sugestão..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary modern-btn w-100">
                            <i class="bi bi-send me-2"></i>Enviar Sugestão
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Gráfico de Evolução -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="chart-card" data-aos="fade-up" data-aos-delay="300">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>Evolução das Viagens
                    </h5>
                    <span class="badge bg-primary">Últimos 12 meses</span>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="viagensChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos modernos para o dashboard */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    flex-direction: column;
}

.loading-spinner {
    text-align: center;
}

.dashboard-header {
    padding: 2rem 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-size: 200% 200%;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.header-actions {
    margin-top: 1rem;
}

.dashboard-card {
    background: #ffffff;
    border-radius: 20px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    height: 100%;
    overflow: hidden;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.card-content {
    padding: 2rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.card-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: white;
    transition: all 0.3s ease;
}

.card-icon i {
    font-size: 2.5rem;
}

.dashboard-card:hover .card-icon {
    transform: scale(1.1);
}

.card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.card-text {
    color: #7f8c8d;
    margin-bottom: 1.5rem;
    flex-grow: 1;
}

.modern-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
}

.modern-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.stats-card, .chart-card {
    background: #ffffff;
    border-radius: 20px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.stats-card:hover, .chart-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-body {
    padding: 2rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.stat-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.last-trip-info {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 15px;
    margin-top: 1.5rem;
}

.trip-details {
    background: white;
    padding: 1rem;
    border-radius: 10px;
    margin-top: 1rem;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state i {
    font-size: 4rem;
    display: block;
}

.modern-modal {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

.modern-modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.5rem 2rem;
}

.modern-modal-footer {
    border: none;
    padding: 1.5rem 2rem;
    background: #f8f9fa;
}

.modern-input {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.modern-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.modern-alert {
    border-radius: 10px;
    border: none;
    padding: 1rem 1.5rem;
}

.welcome-icon {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-size: 3rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.welcome-feature-card {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 15px;
    text-align: center;
    transition: all 0.3s ease;
}

.welcome-feature-card:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.feature-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
}

.chart-container {
    position: relative;
    height: 400px;
    padding: 1rem;
}

.fade-in {
    animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Animações AOS personalizadas */
[data-aos="fade-up"] {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

[data-aos="fade-up"].aos-animate {
    opacity: 1;
    transform: translateY(0);
}

/* Responsividade */
@media (max-width: 768px) {
    .dashboard-header {
        padding: 1rem 0;
    }
    
    .card-content {
        padding: 1.5rem;
    }
    
    .card-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .card-icon i {
        font-size: 2rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
    
    .chart-container {
        height: 300px;
    }
}

/* Melhorias de acessibilidade */
.modern-btn:focus,
.modern-input:focus {
    outline: 2px solid #667eea;
    outline-offset: 2px;
}

/* Modo escuro (se necessário) */
@media (prefers-color-scheme: dark) {
    .dashboard-card,
    .stats-card,
    .chart-card {
        background: #2c3e50;
        color: white;
    }
    
    .card-title {
        color: white;
    }
    
    .card-text {
        color: #bdc3c7;
    }
    
    .stat-item {
        background: #34495e;
    }
    
    .stat-value {
        color: white;
    }
    
    .stat-label {
        color: #bdc3c7;
    }
}

/* Correção específica para botões do dashboard - TEXTO ULTRA NÍTIDO */
.dashboard-btn-fix {
    color: #ffffff !important;
    font-weight: 900 !important;
    letter-spacing: 1px !important;
    opacity: 1 !important;
    visibility: visible !important;
    text-indent: 0 !important;
    font-size: 1.1rem !important;
    line-height: 1.4 !important;
    text-shadow: 0 0 1px rgba(0,0,0,0.8) !important;
    text-decoration: none !important;
    -webkit-font-smoothing: antialiased !important;
    -moz-osx-font-smoothing: grayscale !important;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
}

.dashboard-btn-fix i {
    color: #ffffff !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.btn-primary.dashboard-btn-fix {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
    color: #ffffff !important;
}

.btn-success.dashboard-btn-fix {
    background-color: #198754 !important;
    border-color: #198754 !important;
    color: #ffffff !important;
}

.btn-primary.dashboard-btn-fix:hover {
    background-color: #0b5ed7 !important;
    border-color: #0a58ca !important;
    color: #ffffff !important;
}

.btn-success.dashboard-btn-fix:hover {
    background-color: #157347 !important;
    border-color: #146c43 !important;
    color: #ffffff !important;
}

/* Garantir que funcione no modo escuro também */
.dark .dashboard-btn-fix {
    color: #ffffff !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.dark .dashboard-btn-fix i {
    color: #ffffff !important;
}

/* Forçar texto visível em qualquer situação */
.header-actions .dashboard-btn-fix {
    color: #ffffff !important;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3) !important;
}

.header-actions .btn-primary.dashboard-btn-fix {
    background: #0d6efd !important;
    border: 2px solid #0d6efd !important;
    color: #ffffff !important;
}

.header-actions .btn-success.dashboard-btn-fix {
    background: #198754 !important;
    border: 2px solid #198754 !important;
    color: #ffffff !important;
}

/* Garantir que o texto não seja transparente */
.dashboard-btn-fix * {
    color: inherit !important;
    opacity: 1 !important;
}

/* ===== CABEÇALHO PROFISSIONAL E ROBUSTO ===== */
.professional-header {
    margin-bottom: 2rem;
}

.header-background {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 25px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
}

.header-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.1);
    z-index: 1;
}

.header-content {
    position: relative;
    z-index: 2;
    padding: 3rem 2rem;
    color: white;
}

.header-info {
    text-align: left;
}

.welcome-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.header-title {
    font-size: 3.5rem;
    font-weight: 900;
    margin-bottom: 1rem;
    line-height: 1.1;
}

.title-main {
    display: block;
    color: #ffffff;
}

.title-subtitle {
    display: block;
    font-size: 1.5rem;
    font-weight: 400;
    color: rgba(255, 255, 255, 0.8);
    margin-top: 0.5rem;
}

.header-description {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2rem;
    font-weight: 500;
}

.header-stats {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.stat-item-header {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 900;
    color: #ffffff;
    line-height: 1;
}

.stat-label {
    display: block;
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 0.25rem;
}

.stat-divider {
    width: 2px;
    height: 40px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 1px;
}

.header-actions-professional {
    text-align: right;
}

.action-group {
    margin-bottom: 2rem;
}

.btn-professional {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 15px;
    padding: 1rem 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    text-decoration: none;
    color: white !important;
    backdrop-filter: blur(10px);
    cursor: pointer;
    width: 100%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
}

.btn-professional:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    color: white !important;
}

.btn-primary-pro {
    border-color: rgba(13, 110, 253, 0.5);
}

.btn-primary-pro:hover {
    background: rgba(13, 110, 253, 0.3);
    border-color: rgba(13, 110, 253, 0.7);
}

.btn-success-pro {
    border-color: rgba(25, 135, 84, 0.5);
}

.btn-success-pro:hover {
    background: rgba(25, 135, 84, 0.3);
    border-color: rgba(25, 135, 84, 0.7);
}

.btn-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.btn-content {
    flex: 1;
    text-align: left;
}

.btn-title {
    display: block;
    font-size: 1.1rem;
    font-weight: 900 !important;
    color: #ffffff !important;
    margin-bottom: 0.25rem;
    letter-spacing: 0.5px !important;
}

.btn-subtitle {
    display: block;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.8) !important;
    font-weight: 400;
}

.quick-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.quick-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    min-width: 80px;
    backdrop-filter: blur(10px);
    cursor: pointer;
}

.quick-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-2px);
    color: white;
}

.quick-btn i {
    font-size: 1.2rem;
}

.quick-btn span {
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
}

/* Responsividade para o cabeçalho profissional */
@media (max-width: 992px) {
    .header-content {
        padding: 2rem 1.5rem;
    }
    
    .header-title {
        font-size: 2.5rem;
    }
    
    .title-subtitle {
        font-size: 1.2rem;
    }
    
    .header-description {
        font-size: 1rem;
    }
    
    .header-actions-professional {
        text-align: center;
        margin-top: 2rem;
    }
    
    .header-stats {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .header-content {
        padding: 1.5rem 1rem;
    }
    
    .header-title {
        font-size: 2rem;
    }
    
    .title-subtitle {
        font-size: 1rem;
    }
    
    .btn-professional {
        padding: 0.75rem 1rem;
    }
    
    .btn-icon {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
    
    .btn-title {
        font-size: 1rem;
    }
    
    .btn-subtitle {
        font-size: 0.8rem;
    }
    
    .quick-actions {
        justify-content: center;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
}

/* ===== CARDS EXECUTIVOS PROFISSIONAIS ===== */
.executive-card {
    height: 100%;
    border-radius: 25px;
    overflow: hidden;
    position: relative;
    transition: all 0.4s ease;
    cursor: pointer;
}

.executive-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
}

.card-background {
    position: relative;
    height: 100%;
    min-height: 320px;
    border-radius: 25px;
    overflow: hidden;
}

.success-card .card-background {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.primary-card .card-background {
    background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
}

.info-card .card-background {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
}

.warning-card .card-background {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.danger-card .card-background {
    background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
}

.secondary-card .card-background {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.15);
    z-index: 1;
}

.card-content-executive {
    position: relative;
    z-index: 2;
    padding: 2rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    color: white;
}

.card-header-executive {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.card-icon-executive {
    width: 70px;
    height: 70px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    font-size: 2rem;
    color: white;
    transition: all 0.3s ease;
}

.executive-card:hover .card-icon-executive {
    transform: scale(1.1) rotate(5deg);
    background: rgba(255, 255, 255, 0.3);
}

.card-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.card-body-executive {
    flex: 1;
    margin-bottom: 1.5rem;
}

.card-title-executive {
    font-size: 1.8rem;
    font-weight: 900;
    color: white;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.card-description-executive {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.5;
    margin-bottom: 1.5rem;
}

.card-stats {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.stat-mini {
    background: rgba(255, 255, 255, 0.15);
    padding: 0.75rem 1rem;
    border-radius: 12px;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    min-width: 80px;
}

.stat-number-mini {
    display: block;
    font-size: 1.5rem;
    font-weight: 900;
    color: white;
    line-height: 1;
}

.stat-label-mini {
    display: block;
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 0.25rem;
}

.card-footer-executive {
    margin-top: auto;
}

.btn-executive {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 15px;
    padding: 1rem 1.5rem;
    color: white;
    text-decoration: none;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    width: 100%;
    cursor: pointer;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-executive:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    color: white;
}

.btn-executive i {
    font-size: 1.2rem;
}

.btn-success-exec:hover {
    background: rgba(40, 167, 69, 0.3);
    border-color: rgba(40, 167, 69, 0.6);
}

.btn-primary-exec:hover {
    background: rgba(0, 123, 255, 0.3);
    border-color: rgba(0, 123, 255, 0.6);
}

.btn-info-exec:hover {
    background: rgba(23, 162, 184, 0.3);
    border-color: rgba(23, 162, 184, 0.6);
}

.btn-warning-exec:hover {
    background: rgba(255, 193, 7, 0.3);
    border-color: rgba(255, 193, 7, 0.6);
}

.btn-danger-exec:hover {
    background: rgba(220, 53, 69, 0.3);
    border-color: rgba(220, 53, 69, 0.6);
}

.btn-secondary-exec:hover {
    background: rgba(108, 117, 125, 0.3);
    border-color: rgba(108, 117, 125, 0.6);
}

/* Responsividade para cards executivos */
@media (max-width: 768px) {
    .card-content-executive {
        padding: 1.5rem;
    }
    
    .card-icon-executive {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .card-title-executive {
        font-size: 1.5rem;
    }
    
    .card-description-executive {
        font-size: 0.9rem;
    }
    
    .btn-executive {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }
    
    .stat-number-mini {
        font-size: 1.2rem;
    }
    
    .card-background {
        min-height: 280px;
    }
}

/* Animações especiais para cards executivos */
@keyframes cardPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

.executive-card:hover {
    animation: cardPulse 0.6s ease-in-out;
}

/* Efeitos de glassmorphism aprimorados */
.card-icon-executive,
.card-badge,
.stat-mini,
.btn-executive {
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
}

/* Modo escuro para cards executivos */
@media (prefers-color-scheme: dark) {
    .success-card .card-background {
        background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    }
    
    .primary-card .card-background {
        background: linear-gradient(135deg, #004085 0%, #002752 100%);
    }
    
    .info-card .card-background {
        background: linear-gradient(135deg, #0c5460 0%, #062c33 100%);
    }
    
    .warning-card .card-background {
        background: linear-gradient(135deg, #856404 0%, #533f03 100%);
    }
    
    .danger-card .card-background {
        background: linear-gradient(135deg, #721c24 0%, #491217 100%);
    }
    
    .secondary-card .card-background {
        background: linear-gradient(135deg, #383d41 0%, #212529 100%);
    }
}
</style>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar AOS
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });

    // Configurar Chart.js
    const ctx = document.getElementById('viagensChart');
    if (ctx) {
        const viagensChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels ?? []),
                datasets: [{
                    label: 'Viagens por mês',
                    data: @json($data ?? []),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: false 
                    },
                    tooltip: { 
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#667eea',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        precision: 0,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                },
                elements: {
                    line: {
                        borderWidth: 3
                    }
                }
            }
        });
    }

    // Sistema de loading
    function showLoading() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    }
    
    function hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }

    // Popup de boas-vindas
    const dontShowAgain = localStorage.getItem('welcomePopupDontShow');
    if (dontShowAgain !== 'true') {
        setTimeout(function() {
            const modalElement = document.getElementById('welcomeModal');
            if (modalElement) {
                const welcomeModal = new bootstrap.Modal(modalElement);
                welcomeModal.show();
            }
        }, 1500);
    }

    // Controle do popup
    const modalElement = document.getElementById('welcomeModal');
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', function() {
            const checkbox = document.getElementById('dontShowAgain');
            if (checkbox && checkbox.checked) {
                localStorage.setItem('welcomePopupDontShow', 'true');
            }
        });
    }

    // Função para resetar popup (para desenvolvimento)
    window.resetWelcomePopup = function() {
        localStorage.removeItem('welcomePopupDontShow');
        console.log('Popup resetado');
    };

    // Adicionar efeitos de hover nos cards
    document.querySelectorAll('.dashboard-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Animação dos números (contadores)
    function animateNumbers() {
        const statValues = document.querySelectorAll('.stat-value');
        statValues.forEach(stat => {
            const target = parseInt(stat.textContent);
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                stat.textContent = Math.floor(current);
            }, 16);
        });
    }

    // Executar animação dos números após carregamento
    setTimeout(animateNumbers, 500);

    // Validação de formulários
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const textarea = this.querySelector('textarea[name="mensagem"]');
            if (textarea && textarea.value.trim().length < 10) {
                e.preventDefault();
                alert('Por favor, digite uma mensagem com pelo menos 10 caracteres.');
                textarea.focus();
            }
        });
    });

    // Melhorias de acessibilidade
    document.querySelectorAll('.modern-btn').forEach(btn => {
        btn.addEventListener('focus', function() {
            this.style.transform = 'scale(1.02)';
        });
        
        btn.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
