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
    // Dados de exemplo - em produção viriam do backend
    const dashboardData = getDashboardData();
    
    // Gráfico de viagens por status
    renderStatusChart(dashboardData.statusData);
    
    // Gráfico de viagens por mês
    renderMonthlyChart(dashboardData.monthlyData);
    
    // Gráfico de destinos mais visitados
    renderDestinationsChart(dashboardData.destinationsData);
    
    // Gráfico de gastos por categoria
    renderExpensesChart(dashboardData.expensesData);
    
    // Gráfico de linha temporal
    renderTimelineChart(dashboardData.timelineData);
    
    // KPIs animados
    renderKPIs(dashboardData.kpis);
}

function getDashboardData() {
    // Simular dados do backend - substituir por requisição AJAX real
    return {
        statusData: {
            approved: 15,
            pending: 8,
            rejected: 3,
            draft: 5
        },
        monthlyData: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            datasets: [
                {
                    label: 'Viagens Realizadas',
                    data: [3, 5, 2, 8, 6, 4],
                    backgroundColor: 'rgba(59, 130, 246, 0.8)'
                },
                {
                    label: 'Viagens Pendentes',
                    data: [1, 2, 4, 1, 3, 2],
                    backgroundColor: 'rgba(245, 158, 11, 0.8)'
                }
            ]
        },
        destinationsData: {
            labels: ['São Paulo', 'Rio de Janeiro', 'Brasília', 'Salvador', 'Recife'],
            data: [12, 8, 6, 4, 3]
        },
        expensesData: {
            labels: ['Hospedagem', 'Transporte', 'Alimentação', 'Combustível', 'Outros'],
            data: [35, 25, 20, 15, 5]
        },
        timelineData: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            cumulative: [3, 8, 10, 18, 24, 28],
            monthly: [3, 5, 2, 8, 6, 4]
        },
        kpis: {
            totalTrips: 31,
            totalDistance: 15420,
            totalExpenses: 52000,
            avgTripDuration: 3.2,
            approvalRate: 89,
            monthlyGrowth: 12
        }
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
    // Em produção, fazer requisição AJAX para buscar novos dados
    console.log('Atualizando dados do dashboard...');
    
    // Simular atualização
    const newData = getDashboardData();
    
    // Re-renderizar gráficos com novos dados
    Chart.helpers.each(Chart.instances, function(instance) {
        instance.destroy();
    });
    
    setTimeout(() => {
        renderCharts();
    }, 500);
}

// Exportar funções para uso global
window.DashboardAnalytics = {
    refresh: refreshDashboardData,
    getData: getDashboardData,
    renderCharts: renderCharts
};
