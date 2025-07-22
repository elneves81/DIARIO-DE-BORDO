<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @vite(['resources/css/components/dark-mode.css', 'resources/js/modules/dark-mode.js'])
        @vite(['resources/css/components/executive-navbar.css'])
        @vite(['resources/js/modules/notifications.js'])
        @vite(['resources/js/modules/advanced-search.js'])
        @vite(['resources/js/modules/dashboard-analytics.js'])
        @vite(['resources/js/modules/ux-enhancements.js'])
        
        <!-- Styles customizados -->
        @stack('styles')
        
        <!-- Melhorias de legibilidade e contraste -->
        <style>
            /* Melhorar legibilidade do dashboard */
            .dashboard-header h2 {
                color: #2c3e50 !important;
                text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
                font-weight: 700;
            }
            
            .dashboard-header p {
                color: #34495e !important;
                font-weight: 500;
            }
            
            /* Melhorar contraste dos botões da navbar */
            .navbar button {
                background-color: rgba(255,255,255,0.1) !important;
                border: 1px solid rgba(255,255,255,0.2) !important;
                transition: all 0.3s ease !important;
            }
            
            .navbar button:hover {
                background-color: rgba(255,255,255,0.2) !important;
                border-color: rgba(255,255,255,0.3) !important;
                transform: translateY(-1px);
            }
            
            /* Melhorar visibilidade dos ícones */
            .navbar button svg {
                filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));
            }
            
            /* Melhorar legibilidade dos cards */
            .card-title {
                color: #2c3e50 !important;
                font-weight: 600 !important;
            }
            
            .card-text {
                color: #5a6c7d !important;
                line-height: 1.6 !important;
            }
            
            /* Melhorar contraste dos botões */
            .btn {
                font-weight: 600 !important;
                letter-spacing: 0.5px !important;
            }
            
            /* Modo escuro melhorado */
            .dark .dashboard-header h2 {
                color: #ecf0f1 !important;
            }
            
            .dark .dashboard-header p {
                color: #bdc3c7 !important;
            }
            
            .dark .card-title {
                color: #ecf0f1 !important;
            }
            
            .dark .card-text {
                color: #bdc3c7 !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation-executive')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
        
        <!-- Notifications Modal -->
        <div id="notificationsModal" class="fixed inset-0 z-50 d-none" style="display:none;">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" id="notificationsBackdrop"></div>
            <div class="fixed inset-0 d-flex align-items-center justify-content-center p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-100">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h5 fw-bold text-dark mb-0">Configurações de Notificações</h3>
                            <button id="closeNotificationsModal" class="btn btn-link text-secondary p-0" style="font-size:1.5rem;">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="mb-3">
                            <div id="notificationStatus" class="alert alert-info p-2 mb-2">
                                <div id="notificationStatusText" class="small">Gerencie suas preferências de notificação.</div>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="enableNotifications">
                                <label class="form-check-label" for="enableNotifications">Ativar notificações push</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notifyStatusChanges">
                                <label class="form-check-label" for="notifyStatusChanges">Mudanças de status de viagem</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notifyNewMessages">
                                <label class="form-check-label" for="notifyNewMessages">Novas mensagens do sistema</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="notifyReminders">
                                <label class="form-check-label" for="notifyReminders">Lembretes de viagem</label>
                            </div>
                            <button id="testNotification" class="btn btn-primary w-100 mb-2">Enviar Notificação de Teste</button>
                            <button id="requestNotificationPermission" class="btn btn-success w-100">Solicitar Permissão</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            function showNotificationsModal() {
                const modal = document.getElementById('notificationsModal');
                if (modal) {
                    modal.classList.remove('d-none');
                    modal.style.display = 'block';
                }
            }
            function hideNotificationsModal() {
                const modal = document.getElementById('notificationsModal');
                if (modal) {
                    modal.classList.add('d-none');
                    modal.style.display = 'none';
                }
            }
            const notifBtn = document.getElementById('notificationsToggle');
            if (notifBtn) {
                notifBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    showNotificationsModal();
                });
            }
            const notifBtnMobile = document.getElementById('notificationsToggleMobile');
            if (notifBtnMobile) {
                notifBtnMobile.addEventListener('click', function(e) {
                    e.preventDefault();
                    showNotificationsModal();
                });
            }
            const closeBtn = document.getElementById('closeNotificationsModal');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    hideNotificationsModal();
                });
            }
            const backdrop = document.getElementById('notificationsBackdrop');
            if (backdrop) {
                backdrop.addEventListener('click', function() {
                    hideNotificationsModal();
                });
            }
        });
        </script>

        <!-- Script para corrigir modo escuro -->
        <script>
            // Função para resetar o modo escuro se estiver travado
            function resetDarkModeIfStuck() {
                // Se o localStorage tem um valor inválido, resetar
                const darkMode = localStorage.getItem('darkMode');
                if (darkMode !== 'true' && darkMode !== 'false') {
                    localStorage.setItem('darkMode', 'false');
                    document.documentElement.classList.remove('dark');
                    document.body.classList.remove('dark');
                }
                
                // Se não há valor no localStorage, definir como claro
                if (!darkMode) {
                    localStorage.setItem('darkMode', 'false');
                    document.documentElement.classList.remove('dark');
                    document.body.classList.remove('dark');
                }
            }
            
            // Executar imediatamente
            resetDarkModeIfStuck();
            
            // Função global para resetar manualmente
            window.forceResetDarkMode = function() {
                localStorage.removeItem('darkMode');
                localStorage.setItem('darkMode', 'false');
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark');
                console.log('Modo escuro forçadamente resetado para claro');
                location.reload();
            };
        </script>

        <!-- Scripts customizados -->
        @yield('scripts')
        @stack('scripts')
    </body>
</html>
