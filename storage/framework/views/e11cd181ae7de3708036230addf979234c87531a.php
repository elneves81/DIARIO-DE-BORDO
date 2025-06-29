

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/theme-enhancements.css')); ?>">
<style>
.dashboard-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.kpi-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.kpi-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.kpi-card:hover::before {
    opacity: 1;
}

.kpi-number {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0.5rem 0;
}

.kpi-label {
    font-size: 0.9rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.chart-container {
    position: relative;
    height: 300px;
    margin: 1rem 0;
}

.progress-animated {
    transition: width 1.5s ease-in-out;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.metric-icon {
    font-size: 3rem;
    opacity: 0.8;
    margin-bottom: 1rem;
}

.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    border-radius: 15px;
    margin-bottom: 2rem;
}

.status-badge {
    font-size: 0.8rem;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-weight: 600;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-3">
    <!-- Header do Dashboard -->
    <div class="dashboard-header text-center">
        <h1 class="fw-bold mb-2">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard Analytics
        </h1>
        <p class="mb-3 opacity-90">Análise completa das suas viagens e relatórios</p>
        <div class="d-flex justify-content-center gap-3">
            <button id="refreshDashboard" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-clockwise me-1"></i>Atualizar
            </button>
            <span class="badge bg-success">
                <i class="bi bi-circle-fill me-1"></i>Online
            </span>
        </div>
    </div>

    <!-- KPIs Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="kpi-card">
                <i class="bi bi-airplane-fill metric-icon"></i>
                <div id="totalTrips" class="kpi-number">0</div>
                <div class="kpi-label">Total de Viagens</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="kpi-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="bi bi-geo-alt-fill metric-icon"></i>
                <div id="totalDistance" class="kpi-number">0</div>
                <div class="kpi-label">Distância Total</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="kpi-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i class="bi bi-currency-dollar metric-icon"></i>
                <div id="totalExpenses" class="kpi-number">0</div>
                <div class="kpi-label">Gastos Totais</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="kpi-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="bi bi-clock-fill metric-icon"></i>
                <div id="avgTripDuration" class="kpi-number">0</div>
                <div class="kpi-label">Duração Média</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="kpi-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <i class="bi bi-check-circle-fill metric-icon"></i>
                <div id="approvalRate" class="kpi-number">0</div>
                <div class="kpi-label">Taxa Aprovação</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="kpi-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333;">
                <i class="bi bi-graph-up-arrow metric-icon"></i>
                <div id="monthlyGrowth" class="kpi-number">0</div>
                <div class="kpi-label">Crescimento Mensal</div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card dashboard-card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart-fill me-2"></i>Status das Viagens
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card dashboard-card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart-fill me-2"></i>Viagens por Mês
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card dashboard-card">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-geo-fill me-2"></i>Destinos Populares
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="destinationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card dashboard-card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-wallet-fill me-2"></i>Gastos por Categoria
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="expensesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card dashboard-card">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>Evolução Temporal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="timelineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning-fill text-warning me-2"></i>Métricas de Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted">Taxa de Aprovação</h6>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div id="approvalProgress" class="progress-bar progress-animated" style="width: 0%"></div>
                                </div>
                                <small class="text-success fw-bold">89% dos pedidos</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted">Crescimento Mensal</h6>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div id="growthProgress" class="progress-bar progress-animated bg-info" style="width: 0%"></div>
                                </div>
                                <small class="text-info fw-bold">+12% este mês</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bell-fill text-primary me-2"></i>Ações Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('viagens.create')); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Nova Viagem
                        </a>
                        <a href="<?php echo e(route('viagens.index')); ?>" class="btn btn-outline-primary">
                            <i class="bi bi-list-ul me-2"></i>Ver Todas as Viagens
                        </a>
                        <a href="<?php echo e(route('relatorios.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-file-earmark-text me-2"></i>Relatórios
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>Atividade Recente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Viagem para São Paulo aprovada</h6>
                                <small class="text-muted">Há 2 horas</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Nova solicitação de viagem criada</h6>
                                <small class="text-muted">Há 4 horas</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Relatório de despesas enviado</h6>
                                <small class="text-muted">Ontem</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/dashboard-analytics.js']); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/dark-mode.js']); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/notifications.js']); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/advanced-search.js']); ?>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e5e7eb;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #e5e7eb;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
}

.dark .timeline-content {
    background: #374151;
    color: #f9fafb;
    border-left-color: #60a5fa;
}

.dark .timeline::before {
    background: #4b5563;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app-original', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Elber\Documents\GitHub\prototipoSite\diario-bordo\resources\views/dashboard-analytics.blade.php ENDPATH**/ ?>