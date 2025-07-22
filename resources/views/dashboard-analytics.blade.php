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
    .fade-in-delay-4 { animation-delay: 0.4s; }

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

    .card-modern {
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        border-radius: 16px;
    }

    .kpi-card {
        border: none;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        min-height: 180px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .kpi-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
    }

    .kpi-number {
        font-size: 2.8rem;
        font-weight: 800;
        margin: 0.5rem 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .kpi-label {
        font-size: 0.95rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .metric-icon {
        font-size: 3.5rem;
        opacity: 0.8;
        margin-bottom: 1rem;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    }

    .chart-container {
        position: relative;
        height: 350px;
        margin: 1rem 0;
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .gradient-bg-1 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .gradient-bg-2 {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .gradient-bg-3 {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .gradient-bg-4 {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
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

    @media (max-width: 768px) {
        .kpi-card {
            min-height: 150px;
            padding: 1.5rem;
        }
        
        .kpi-number {
            font-size: 2.2rem;
        }
        
        .metric-icon {
            font-size: 2.5rem;
        }
    }
</style>

<div class="container py-4">
    <!-- Header Moderno -->
    <div class="d-flex align-items-center gap-3 mb-4 fade-in">
        <div class="rounded-circle d-flex align-items-center justify-content-center hover-lift" style="width:56px;height:56px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;box-shadow: 0 4px 15px rgba(102,126,234,0.3);">
            <i class="bi bi-graph-up-arrow fs-2"></i>
        </div>
        <div>
            <h2 class="mb-0 fw-bold" style="color:#667eea;">Analytics</h2>
            <p class="text-muted mb-0 small">Análise inteligente das suas viagens</p>
        </div>
        <div class="ms-auto">
            <button id="refreshDashboard" class="btn btn-outline-primary btn-gradient d-flex align-items-center gap-2">
                <i class="bi bi-arrow-clockwise"></i>
                <span>Atualizar</span>
            </button>
        </div>
    </div>

    <!-- KPIs Essenciais -->
    <div class="row g-4 mb-4 fade-in fade-in-delay-1">
        <div class="col-lg-3 col-md-6">
            <div class="kpi-card gradient-bg-1 hover-lift">
                <i class="bi bi-truck-front-fill metric-icon"></i>
                <div id="totalTrips" class="kpi-number" data-kpi="totalTrips">0</div>
                <div class="kpi-label">Total de Viagens</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="kpi-card gradient-bg-2 hover-lift">
                <i class="bi bi-speedometer2 metric-icon"></i>
                <div id="totalDistance" class="kpi-number" data-kpi="totalDistance">0</div>
                <div class="kpi-label">Quilômetros Percorridos</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="kpi-card gradient-bg-3 hover-lift">
                <i class="bi bi-calendar-event metric-icon"></i>
                <div id="monthlyTrips" class="kpi-number" data-kpi="monthlyTrips">0</div>
                <div class="kpi-label">Viagens Este Mês</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="kpi-card gradient-bg-4 hover-lift">
                <i class="bi bi-people-fill metric-icon"></i>
                <div id="activeDrivers" class="kpi-number" data-kpi="activeDrivers">0</div>
                <div class="kpi-label">Condutores Ativos</div>
            </div>
        </div>
    </div>

    <!-- Gráficos Principais -->
    <div class="row g-4 mb-4 fade-in fade-in-delay-2">
        <div class="col-lg-8">
            <div class="card card-modern hover-lift">
                <div class="card-header" style="background:linear-gradient(90deg,#667eea 0%,#764ba2 100%);color:#fff;border-radius:16px 16px 0 0;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-bar-chart-line-fill me-2"></i>Evolução das Viagens
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-modern hover-lift">
                <div class="card-header" style="background:linear-gradient(90deg,#f093fb 0%,#f5576c 100%);color:#fff;border-radius:16px 16px 0 0;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-pie-chart-fill me-2"></i>Destinos Populares
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="destinationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas Detalhadas -->
    <div class="row g-4 mb-4 fade-in fade-in-delay-3">
        <div class="col-lg-6">
            <div class="card card-modern hover-lift">
                <div class="card-header" style="background:linear-gradient(90deg,#43e97b 0%,#38f9d7 100%);color:#fff;border-radius:16px 16px 0 0;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-graph-up-arrow me-2"></i>Estatísticas Mensais
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);">
                                <i class="bi bi-truck fs-2 text-primary mb-2"></i>
                                <h6 class="text-primary mb-1">Média Diária</h6>
                                <h4 class="mb-0 fw-bold text-primary" id="avgDaily">0</h4>
                                <small class="text-muted">viagens/dia</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #e8f5e8 0%, #f8f9fa 100%);">
                                <i class="bi bi-speedometer fs-2 text-success mb-2"></i>
                                <h6 class="text-success mb-1">Média KM</h6>
                                <h4 class="mb-0 fw-bold text-success" id="avgKm">0</h4>
                                <small class="text-muted">km/viagem</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-modern hover-lift">
                <div class="card-header" style="background:linear-gradient(90deg,#4facfe 0%,#00f2fe 100%);color:#fff;border-radius:16px 16px 0 0;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-lightning-fill me-2"></i>Ações Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('viagens.create') }}" class="btn btn-primary btn-gradient d-flex align-items-center justify-content-center gap-2 fw-bold">
                            <i class="bi bi-plus-circle-fill"></i>
                            <span>Nova Viagem</span>
                        </a>
                        <a href="{{ route('viagens.index') }}" class="btn btn-outline-primary btn-gradient d-flex align-items-center justify-content-center gap-2 fw-bold">
                            <i class="bi bi-list-ul"></i>
                            <span>Ver Todas as Viagens</span>
                        </a>
                        <a href="{{ route('relatorios.index') }}" class="btn btn-outline-secondary btn-gradient d-flex align-items-center justify-content-center gap-2 fw-bold">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            <span>Gerar Relatórios</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Atividade Recente -->
    <div class="row g-4 fade-in fade-in-delay-4">
        <div class="col-12">
            <div class="card card-modern hover-lift">
                <div class="card-header" style="background:linear-gradient(90deg,#667eea 0%,#764ba2 100%);color:#fff;border-radius:16px 16px 0 0;">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-clock-history me-2"></i>Atividade Recente
                        </h5>
                        <small class="opacity-75">Últimas 10 viagens</small>
                    </div>
                </div>
                <div class="card-body">
                    <div id="recentActivityList" class="timeline-container">
                        <!-- Atividade recente será carregada via JavaScript -->
                        <div class="text-center text-muted py-4">
                            <div class="pulse-animation">
                                <i class="bi bi-hourglass-split fs-2 text-primary mb-2"></i>
                            </div>
                            <p class="mb-0">Carregando atividades recentes...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
// Dados simulados para os KPIs
const kpiData = {
    totalTrips: 156,
    totalDistance: 12450,
    monthlyTrips: 23,
    activeDrivers: 8,
    avgDaily: 5,
    avgKm: 80
};

// Função para animar números
function animateNumber(element, endValue, duration = 2000, suffix = '') {
    const startValue = 0;
    const increment = endValue / (duration / 16);
    let currentValue = startValue;
    
    const timer = setInterval(() => {
        currentValue += increment;
        if (currentValue >= endValue) {
            currentValue = endValue;
            clearInterval(timer);
        }
        element.textContent = Math.floor(currentValue).toLocaleString() + suffix;
    }, 16);
}

// Função para atualizar KPIs
function updateKPIs() {
    setTimeout(() => {
        animateNumber(document.getElementById('totalTrips'), kpiData.totalTrips);
        animateNumber(document.getElementById('totalDistance'), kpiData.totalDistance, 2000, ' km');
        animateNumber(document.getElementById('monthlyTrips'), kpiData.monthlyTrips);
        animateNumber(document.getElementById('activeDrivers'), kpiData.activeDrivers);
        animateNumber(document.getElementById('avgDaily'), kpiData.avgDaily);
        animateNumber(document.getElementById('avgKm'), kpiData.avgKm, 2000, ' km');
    }, 500);
}

// Gráfico de viagens mensais
function createMonthlyChart() {
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            datasets: [{
                label: 'Viagens',
                data: [12, 19, 15, 25, 22, 18, 24, 28, 23, 20, 26, 23],
                borderColor: 'rgb(102, 126, 234)',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(102, 126, 234)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Gráfico de destinos populares
function createDestinationsChart() {
    const ctx = document.getElementById('destinationsChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['São Paulo', 'Rio de Janeiro', 'Belo Horizonte', 'Curitiba', 'Salvador', 'Outros'],
            datasets: [{
                data: [35, 25, 15, 10, 8, 7],
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
                    '#f093fb',
                    '#f5576c',
                    '#4facfe',
                    '#00f2fe'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
}

// Função para carregar atividade recente
function loadRecentActivity() {
    const activities = [
        {
            id: 1,
            type: 'viagem',
            description: 'Viagem para São Paulo concluída',
            user: 'João Silva',
            time: '2 horas atrás',
            icon: 'bi-check-circle-fill',
            color: 'success'
        },
        {
            id: 2,
            type: 'viagem',
            description: 'Nova viagem para Rio de Janeiro iniciada',
            user: 'Maria Santos',
            time: '4 horas atrás',
            icon: 'bi-play-circle-fill',
            color: 'primary'
        },
        {
            id: 3,
            type: 'relatorio',
            description: 'Relatório mensal gerado',
            user: 'Sistema',
            time: '6 horas atrás',
            icon: 'bi-file-earmark-text',
            color: 'info'
        }
    ];

    const activityContainer = document.getElementById('recentActivityList');
    const activityHTML = activities.map(activity => `
        <div class="d-flex align-items-center gap-3 p-3 mb-2 rounded-3 hover-lift" style="background: rgba(102, 126, 234, 0.05); transition: all 0.3s ease;">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: var(--bs-${activity.color}); color: white;">
                <i class="bi ${activity.icon}"></i>
            </div>
            <div class="flex-grow-1">
                <p class="mb-1 fw-medium">${activity.description}</p>
                <small class="text-muted">${activity.user} • ${activity.time}</small>
            </div>
        </div>
    `).join('');

    setTimeout(() => {
        activityContainer.innerHTML = activityHTML;
    }, 1000);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    updateKPIs();
    
    // Delay para carregar gráficos após as animações
    setTimeout(() => {
        createMonthlyChart();
        createDestinationsChart();
        loadRecentActivity();
    }, 1000);
    
    // Botão de atualizar
    const refreshBtn = document.getElementById('refreshDashboard');
    refreshBtn.addEventListener('click', function() {
        this.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i><span>Atualizando...</span>';
        this.disabled = true;
        
        setTimeout(() => {
            updateKPIs();
            loadRecentActivity();
            this.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i><span>Atualizar</span>';
            this.disabled = false;
        }, 2000);
    });
});
</script>
@endsection
