// Dashboard Analytics com gráficos interativos
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboardAnalytics();
});

function initializeDashboardAnalytics() {
    // Verificar se Chart.js está disponível
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js não está carregado. Carregando via CDN...');
        loadChartJS().then(() => {
            renderCharts();
        });
    } else {
        renderCharts();
    }
    
    // Inicializar outros componentes do dashboard
    initializeRealTimeUpdates();
    initializeInteractiveCards();
    initializeDataRefresh();
}

function loadChartJS() {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js';
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

function renderCharts() {
    // Buscar dados reais do backend
    loadDashboardData().then(dashboardData => {
        // Gráfico de viagens por status
        renderStatusChart(dashboardData.charts.statusData);
        
        // Gráfico de viagens por mês
        renderMonthlyChart(dashboardData.charts.monthlyData);
        
        // Gráfico de destinos mais visitados
        renderDestinationsChart(dashboardData.charts.destinationsData);
        
        // Gráfico de gastos por categoria
        renderExpensesChart(dashboardData.charts.expensesData);
        
        // Gráfico de linha temporal
        renderTimelineChart(dashboardData.charts.timelineData);
        
        // KPIs animados
        renderKPIs(dashboardData.kpis);
        
        // Atividade recente
        renderRecentActivity(dashboardData.recentActivity);
        
        // Atualizar timestamp
        updateLastRefresh(dashboardData.lastUpdated);
    }).catch(error => {
        console.error('Erro ao carregar dados do dashboard:', error);
        showErrorFallback();
    });
}

// Buscar dados reais do backend
async function loadDashboardData() {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.warn('CSRF token não encontrado, usando dados de fallback');
            return getFallbackData();
        }

        const response = await fetch('/analytics/dashboard', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        // Verificar se a resposta é JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            console.warn('Resposta não é JSON, possivelmente redirecionamento para login. Usando dados de fallback.');
            return getFallbackData();
        }
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Erro ao buscar dados do dashboard:', error);
        // Retornar dados de fallback se a API falhar
        return getFallbackData();
    }
}

// Dados de fallback em caso de erro na API
function getFallbackData() {
    return {
        kpis: {
            totalTrips: 0,
            totalDistance: 0,
            totalExpenses: 0,
            avgTripDuration: 0,
            approvalRate: 0,
            monthlyGrowth: 0
        },
        charts: {
            statusData: { approved: 0, pending: 0, rejected: 0, draft: 0 },
            monthlyData: { labels: [], datasets: [] },
            destinationsData: { labels: ['Sem dados'], data: [1] },
            expensesData: { labels: ['Sem dados'], data: [100] },
            timelineData: { labels: [], cumulative: [], monthly: [] }
        },
        recentActivity: [],
        lastUpdated: new Date().toISOString()
    };
}

function renderStatusChart(data) {
    const ctx = document.getElementById('statusChart');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Aprovadas', 'Pendentes', 'Rejeitadas', 'Rascunho'],
            datasets: [{
                data: [data.approved, data.pending, data.rejected, data.draft],
                backgroundColor: [
                    '#10b981', // verde
                    '#f59e0b', // amarelo
                    '#ef4444', // vermelho
                    '#6b7280'  // cinza
                ],
                borderWidth: 0,
                hoverOffset: 8
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
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                duration: 1500
            }
        }
    });
}

function renderMonthlyChart(data) {
    const ctx = document.getElementById('monthlyChart');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: data.datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });
}

function renderDestinationsChart(data) {
    const ctx = document.getElementById('destinationsChart');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.data,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(236, 72, 153, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });
}

function renderExpensesChart(data) {
    const ctx = document.getElementById('expensesChart');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.data,
                backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + '%';
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                duration: 1500
            }
        }
    });
}

function renderTimelineChart(data) {
    const ctx = document.getElementById('timelineChart');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Acumulado',
                    data: data.cumulative,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Mensal',
                    data: data.monthly,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });
}

function renderKPIs(kpis) {
    // Animar contadores
    animateCounter('totalTrips', kpis.totalTrips);
    animateCounter('totalDistance', kpis.totalDistance, 'km');
    animateCounter('totalExpenses', kpis.totalExpenses, 'R$');
    animateCounter('avgTripDuration', kpis.avgTripDuration, 'dias', 1);
    animateCounter('approvalRate', kpis.approvalRate, '%');
    animateCounter('monthlyGrowth', kpis.monthlyGrowth, '%');
    
    // Renderizar progress bars
    renderProgressBar('approvalProgress', kpis.approvalRate);
    renderProgressBar('growthProgress', kpis.monthlyGrowth);
}

function animateCounter(elementId, target, suffix = '', decimals = 0) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const duration = 2000;
    const stepTime = 50;
    const steps = duration / stepTime;
    const increment = target / steps;
    let current = 0;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        
        let displayValue = current;
        if (decimals > 0) {
            displayValue = current.toFixed(decimals);
        } else {
            displayValue = Math.floor(current);
        }
        
        if (suffix === 'R$') {
            element.textContent = suffix + ' ' + displayValue.toLocaleString('pt-BR');
        } else if (suffix === 'km') {
            element.textContent = displayValue.toLocaleString('pt-BR') + ' ' + suffix;
        } else {
            element.textContent = displayValue + (suffix ? ' ' + suffix : '');
        }
    }, stepTime);
}

function renderProgressBar(elementId, percentage) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    setTimeout(() => {
        element.style.width = percentage + '%';
        element.style.transition = 'width 1.5s ease-in-out';
    }, 500);
}

function initializeRealTimeUpdates() {
    // Atualizar dados a cada 5 minutos
    setInterval(() => {
        refreshDashboardData();
    }, 5 * 60 * 1000);
}

function initializeInteractiveCards() {
    // Adicionar interatividade aos cards do dashboard
    const cards = document.querySelectorAll('.dashboard-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        });
    });
}

function initializeDataRefresh() {
    const refreshBtn = document.getElementById('refreshDashboard');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Atualizando...';
            
            // Simular requisição
            setTimeout(() => {
                refreshDashboardData();
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Atualizar';
            }, 2000);
        });
    }
}

function refreshDashboardData() {
    console.log('Atualizando dados do dashboard...');
    
    // Destruir gráficos existentes
    Chart.helpers.each(Chart.instances, function(instance) {
        instance.destroy();
    });
    
    // Recarregar dados e renderizar novamente
    setTimeout(() => {
        renderCharts();
    }, 500);
}

function renderRecentActivity(activities) {
    const activityList = document.getElementById('recentActivityList');
    if (!activityList || !Array.isArray(activities)) return;
    
    activityList.innerHTML = '';
    
    if (activities.length === 0) {
        activityList.innerHTML = '<div class="text-center text-muted py-3">Nenhuma atividade recente</div>';
        return;
    }
    
    activities.forEach(activity => {
        const activityItem = document.createElement('div');
        activityItem.className = 'd-flex align-items-center mb-3';
        activityItem.innerHTML = `
            <div class="flex-shrink-0">
                <div class="activity-icon ${activity.icon}" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-geo-alt text-white"></i>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="mb-1">${activity.title}</h6>
                <p class="text-muted mb-0 small">${activity.subtitle} - ${activity.user}</p>
            </div>
        `;
        activityList.appendChild(activityItem);
    });
}

function showErrorFallback() {
    const dashboard = document.querySelector('.dashboard-analytics');
    if (dashboard) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-warning';
        errorDiv.innerHTML = `
            <h6><i class="bi bi-exclamation-triangle"></i> Aviso</h6>
            <p>Não foi possível carregar os dados mais recentes. Exibindo dados em cache.</p>
            <button class="btn btn-sm btn-outline-warning" onclick="renderCharts()">
                <i class="bi bi-arrow-clockwise"></i> Tentar novamente
            </button>
        `;
        dashboard.prepend(errorDiv);
    }
}

function updateLastRefresh(timestamp) {
    const lastRefreshElement = document.getElementById('lastRefresh');
    if (lastRefreshElement && timestamp) {
        const date = new Date(timestamp);
        lastRefreshElement.textContent = `Última atualização: ${date.toLocaleString('pt-BR')}`;
    }
}

// Função getDashboardData que estava faltando
async function getDashboardData() {
    return await loadDashboardData();
}

// Exportar funções para uso global
window.DashboardAnalytics = {
    refresh: refreshDashboardData,
    getData: getDashboardData,
    renderCharts: renderCharts,
    loadData: loadDashboardData
};
