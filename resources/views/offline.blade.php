@extends('layouts.app-original')

@section('title', 'Modo Offline - Diário de Bordo')

@push('styles')
<style>
.offline-container {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.offline-card {
    max-width: 600px;
    text-align: center;
    padding: 2rem;
}

.offline-icon {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 1.5rem;
}

.connection-status {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    margin: 1rem 0;
}

.status-offline {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.status-online {
    background: #d1edff;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.offline-actions {
    margin-top: 2rem;
}

.offline-feature {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin: 1rem 0;
    text-align: left;
}

.dark .offline-feature {
    background: #374151;
    color: #f9fafb;
}

.feature-icon {
    font-size: 2rem;
    color: #3b82f6;
    margin-bottom: 1rem;
}

.sync-indicator {
    display: none;
    align-items: center;
    color: #28a745;
    margin-top: 1rem;
}

.sync-indicator.syncing {
    display: flex;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="offline-container">
        <div class="offline-card">
            <div class="offline-icon">
                <i class="bi bi-wifi-off" id="connectionIcon"></i>
            </div>
            
            <h1 class="mb-3">Modo Offline</h1>
            <p class="text-muted mb-4">
                Você está navegando no modo offline. Algumas funcionalidades estão limitadas, 
                mas você ainda pode acessar dados em cache e realizar ações offline.
            </p>
            
            <div class="connection-status status-offline" id="connectionStatus">
                <i class="bi bi-wifi-off me-2"></i>
                <span>Conexão perdida</span>
            </div>
            
            <div class="offline-actions">
                <button class="btn btn-primary me-2" onclick="checkConnection()">
                    <i class="bi bi-arrow-clockwise"></i> Verificar Conexão
                </button>
                <button class="btn btn-outline-secondary" onclick="goToCache()">
                    <i class="bi bi-archive"></i> Ver Dados em Cache
                </button>
            </div>
            
            <div class="sync-indicator" id="syncIndicator">
                <div class="spinner-border spinner-border-sm me-2" role="status">
                    <span class="visually-hidden">Sincronizando...</span>
                </div>
                <span>Sincronizando dados...</span>
            </div>
        </div>
    </div>
    
    <!-- Recursos disponíveis offline -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-4">Recursos Disponíveis Offline</h3>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="offline-feature">
                    <div class="feature-icon">
                        <i class="bi bi-suitcase-lg"></i>
                    </div>
                    <h5>Viagens Salvas</h5>
                    <p>Visualize viagens previamente carregadas e crie novas viagens que serão sincronizadas quando a conexão retornar.</p>
                    <a href="/viagens" class="btn btn-sm btn-outline-primary">Ver Viagens</a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="offline-feature">
                    <div class="feature-icon">
                        <i class="bi bi-bar-chart"></i>
                    </div>
                    <h5>Analytics em Cache</h5>
                    <p>Acesse dados de analytics e relatórios previamente carregados para análise offline.</p>
                    <a href="/dashboard/analytics" class="btn btn-sm btn-outline-primary">Ver Analytics</a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="offline-feature">
                    <div class="feature-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5>Busca Local</h5>
                    <p>Use a busca avançada para encontrar informações nos dados já carregados em cache.</p>
                    <button class="btn btn-sm btn-outline-primary" onclick="openAdvancedSearch()">Buscar</button>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Dica:</strong> Todas as ações realizadas offline serão automaticamente 
                    sincronizadas quando a conexão for restaurada.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initOfflinePage();
});

function initOfflinePage() {
    // Verificar status da conexão
    updateConnectionStatus();
    
    // Escutar mudanças na conexão
    window.addEventListener('online', handleOnline);
    window.addEventListener('offline', handleOffline);
    
    // Verificar se há dados para sincronizar
    checkPendingSync();
}

function updateConnectionStatus() {
    const isOnline = navigator.onLine;
    const statusElement = document.getElementById('connectionStatus');
    const iconElement = document.getElementById('connectionIcon');
    
    if (isOnline) {
        statusElement.className = 'connection-status status-online';
        statusElement.innerHTML = '<i class="bi bi-wifi me-2"></i><span>Conectado</span>';
        iconElement.className = 'bi bi-wifi';
        
        // Tentar sincronizar automaticamente
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.ready.then(registration => {
                registration.sync.register('sync-offline-actions');
            });
        }
    } else {
        statusElement.className = 'connection-status status-offline';
        statusElement.innerHTML = '<i class="bi bi-wifi-off me-2"></i><span>Conexão perdida</span>';
        iconElement.className = 'bi bi-wifi-off';
    }
}

function handleOnline() {
    updateConnectionStatus();
    showSyncIndicator();
    
    // Redirecionar para dashboard após sincronização
    setTimeout(() => {
        if (confirm('Conexão restaurada! Deseja voltar ao dashboard?')) {
            window.location.href = '/dashboard';
        }
    }, 2000);
}

function handleOffline() {
    updateConnectionStatus();
}

function checkConnection() {
    showSyncIndicator();
    
    // Simular verificação de conexão
    fetch('/api/ping', { 
        method: 'HEAD',
        cache: 'no-cache'
    })
    .then(() => {
        updateConnectionStatus();
        hideSyncIndicator();
    })
    .catch(() => {
        updateConnectionStatus();
        hideSyncIndicator();
    });
}

function showSyncIndicator() {
    document.getElementById('syncIndicator').classList.add('syncing');
}

function hideSyncIndicator() {
    document.getElementById('syncIndicator').classList.remove('syncing');
}

function goToCache() {
    // Abrir modal com dados em cache ou navegar para página específica
    alert('Funcionalidade em desenvolvimento: Ver dados em cache');
}

function openAdvancedSearch() {
    // Ativar busca avançada
    if (typeof window.advancedSearchManager !== 'undefined') {
        window.advancedSearchManager.show();
    } else {
        alert('Busca avançada não disponível no momento');
    }
}

function checkPendingSync() {
    // Verificar se há dados pendentes para sincronização
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.ready.then(registration => {
            // Enviar mensagem para SW verificar dados pendentes
            if (registration.active) {
                registration.active.postMessage({
                    type: 'CHECK_PENDING_SYNC'
                });
            }
        });
    }
}

// Escutar mensagens do Service Worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.addEventListener('message', event => {
        if (event.data.type === 'SYNC_COMPLETED') {
            hideSyncIndicator();
            
            // Mostrar notificação de sucesso
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-success border-0';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle me-2"></i>
                        Dados sincronizados com sucesso!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            document.body.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Remover toast após mostrar
            toast.addEventListener('hidden.bs.toast', () => {
                document.body.removeChild(toast);
            });
        }
    });
}
</script>
@endpush
