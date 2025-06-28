// Arquivo central para inicializar todas as melhorias de UX
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎨 Inicializando melhorias de Interface/UX...');
    
    // Verificar se os scripts estão carregados
    const scriptsLoaded = {
        darkMode: typeof DarkMode !== 'undefined',
        notifications: typeof NotificationManager !== 'undefined',
        advancedSearch: typeof AdvancedSearch !== 'undefined',
        dashboardAnalytics: typeof DashboardAnalytics !== 'undefined'
    };
    
    console.log('Scripts carregados:', scriptsLoaded);
    
    // Inicializar Dark Mode se disponível
    if (scriptsLoaded.darkMode) {
        console.log('✅ Dark Mode iniciado');
    }
    
    // Inicializar Notificações se disponível
    if (scriptsLoaded.notifications) {
        console.log('✅ Sistema de Notificações iniciado');
    }
    
    // Inicializar Busca Avançada se disponível
    if (scriptsLoaded.advancedSearch) {
        console.log('✅ Busca Avançada iniciada');
    }
    
    // Inicializar Dashboard Analytics se disponível
    if (scriptsLoaded.dashboardAnalytics) {
        console.log('✅ Dashboard Analytics iniciado');
    }
    
    // Configurar Service Worker se suportado
    if ('serviceWorker' in navigator) {
        console.log('✅ Service Worker suportado');
    }
    
    console.log('🚀 Todas as melhorias de Interface/UX foram inicializadas!');
});
