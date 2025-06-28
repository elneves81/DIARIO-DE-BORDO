<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <meta name="description" content="Sistema de Diário de Bordo para gestão de viagens e relatórios">
        
        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#0dcaf0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="Diário de Bordo">
        <meta name="mobile-web-app-capable" content="yes">
        
        <!-- PWA Icons -->
        <link rel="icon" type="image/png" sizes="192x192" href="/img/icon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/img/icon-180x180.png">
        <link rel="manifest" href="/manifest.json">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <!-- Dark Mode CSS -->
        <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
        <link rel="stylesheet" href="{{ asset('css/theme-enhancements.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased h-full">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
            @include('layouts.navigation')

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

        <!-- Modals e Overlays -->
        
        <!-- Advanced Search Modal -->
        <div id="advancedSearchModal" class="fixed inset-0 z-50 hidden">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" id="advancedSearchBackdrop"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Busca Avançada</h3>
                            <button id="closeAdvancedSearch" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Search Form Container -->
                            <div id="searchFormContainer">
                                <!-- Form será injetado pelo JavaScript -->
                            </div>
                            
                            <!-- Active Filters -->
                            <div id="activeFilters" class="hidden">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filtros Ativos:</h4>
                                <div id="filterTags" class="flex flex-wrap gap-2"></div>
                            </div>
                            
                            <!-- Saved Filters -->
                            <div id="savedFilters" class="hidden">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filtros Salvos:</h4>
                                <div id="savedFiltersList" class="space-y-2"></div>
                            </div>
                            
                            <!-- Search History -->
                            <div id="searchHistory" class="hidden">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Histórico:</h4>
                                <div id="searchHistoryList" class="space-y-1"></div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between mt-6">
                            <div class="space-x-2">
                                <button id="saveCurrentFilter" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    Salvar Filtro
                                </button>
                                <button id="clearAllFilters" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                                    Limpar Tudo
                                </button>
                            </div>
                            <button id="applyAdvancedSearch" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                Aplicar Busca
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Modal -->
        <div id="notificationsModal" class="fixed inset-0 z-50 hidden">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" id="notificationsBackdrop"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Configurações de Notificações</h3>
                            <button id="closeNotificationsModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div id="notificationStatus" class="p-3 rounded-md">
                                <div id="notificationStatusText" class="text-sm"></div>
                            </div>
                            
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="checkbox" id="enableNotifications" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Ativar notificações push</span>
                                </label>
                                
                                <label class="flex items-center">
                                    <input type="checkbox" id="notifyStatusChanges" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Mudanças de status de viagem</span>
                                </label>
                                
                                <label class="flex items-center">
                                    <input type="checkbox" id="notifyNewMessages" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Novas mensagens do sistema</span>
                                </label>
                                
                                <label class="flex items-center">
                                    <input type="checkbox" id="notifyReminders" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Lembretes de viagem</span>
                                </label>
                            </div>
                            
                            <div class="space-y-2">
                                <button id="testNotification" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    Enviar Notificação de Teste
                                </button>
                                
                                <button id="requestNotificationPermission" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                    Solicitar Permissão
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        @stack('scripts')
        
        <!-- UX Enhancements -->
        @vite(['resources/js/ux-enhancements.js'])
        
        <!-- Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/sw.js')
                        .then(function(registration) {
                            console.log('ServiceWorker registration successful:', registration.scope);
                        })
                        .catch(function(error) {
                            console.log('ServiceWorker registration failed:', error);
                        });
                });
            }
        </script>
    </body>
</html>
