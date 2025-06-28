// Sistema de Notificações Push
class PushNotificationManager {
    constructor() {
        this.init();
    }

    async init() {
        // Verificar se o navegador suporta notificações
        if (!('Notification' in window)) {
            console.log('Este navegador não suporta notificações');
            return;
        }

        // Verificar se Service Worker está registrado
        if ('serviceWorker' in navigator) {
            this.setupServiceWorker();
        }

        this.setupNotificationButton();
        this.checkPermission();
    }

    async setupServiceWorker() {
        try {
            const registration = await navigator.serviceWorker.ready;
            this.registration = registration;
            console.log('Service Worker ready for notifications');
        } catch (error) {
            console.error('Erro ao configurar Service Worker:', error);
        }
    }

    setupNotificationButton() {
        // Criar botão de notificações se não existir
        if (!document.getElementById('notification-toggle')) {
            const nav = document.querySelector('.navbar-nav');
            if (nav) {
                const notificationButton = document.createElement('li');
                notificationButton.className = 'nav-item position-relative';
                notificationButton.innerHTML = `
                    <button id="notification-toggle" class="btn btn-link nav-link border-0 bg-transparent position-relative" title="Notificações">
                        <i class="bi bi-bell" id="notification-icon"></i>
                        <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                            0
                        </span>
                    </button>
                `;
                nav.appendChild(notificationButton);
            }
        }

        // Adicionar event listener
        const toggleBtn = document.getElementById('notification-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggleNotifications());
        }
    }

    async checkPermission() {
        const permission = Notification.permission;
        this.updateNotificationIcon(permission);

        if (permission === 'granted') {
            this.subscribeToNotifications();
        }
    }

    async toggleNotifications() {
        const permission = Notification.permission;

        if (permission === 'default') {
            const result = await Notification.requestPermission();
            this.updateNotificationIcon(result);
            
            if (result === 'granted') {
                this.subscribeToNotifications();
                this.showWelcomeNotification();
            }
        } else if (permission === 'granted') {
            // Mostrar configurações de notificação
            this.showNotificationSettings();
        } else {
            this.showPermissionDeniedMessage();
        }
    }

    updateNotificationIcon(permission) {
        const icon = document.getElementById('notification-icon');
        if (icon) {
            switch (permission) {
                case 'granted':
                    icon.className = 'bi bi-bell-fill text-success';
                    break;
                case 'denied':
                    icon.className = 'bi bi-bell-slash text-danger';
                    break;
                default:
                    icon.className = 'bi bi-bell text-muted';
            }
        }
    }

    async subscribeToNotifications() {
        try {
            if (!this.registration) return;

            // Verificar se já está inscrito
            const existingSubscription = await this.registration.pushManager.getSubscription();
            if (existingSubscription) {
                console.log('Já inscrito nas notificações');
                return;
            }

            // Chave pública VAPID (você deve gerar uma para seu projeto)
            const vapidPublicKey = 'BEl62iUYgUivxIkv69yViEuiBIa40HI6YUsgVaq2L34GrUWRaQgDCLkxJmQvVjNOSOMHcgN23';

            const subscription = await this.registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array(vapidPublicKey)
            });

            // Enviar subscription para o servidor
            await this.sendSubscriptionToServer(subscription);
            
            console.log('Inscrito nas notificações:', subscription);
        } catch (error) {
            console.error('Erro ao se inscrever nas notificações:', error);
        }
    }

    async sendSubscriptionToServer(subscription) {
        try {
            const response = await fetch('/api/notifications/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    subscription: subscription,
                    user_id: window.userId || null
                })
            });

            if (!response.ok) {
                throw new Error('Erro ao registrar subscription no servidor');
            }

            console.log('Subscription registrada no servidor');
        } catch (error) {
            console.error('Erro ao registrar subscription:', error);
        }
    }

    showWelcomeNotification() {
        if (Notification.permission === 'granted') {
            const notification = new Notification('Notificações Ativadas! 🔔', {
                body: 'Você receberá alertas sobre suas viagens e atualizações do sistema.',
                icon: '/img/icon-192.png',
                badge: '/img/badge-72x72.png',
                tag: 'welcome',
                renotify: false,
                requireInteraction: false,
                silent: false,
                vibrate: [200, 100, 200]
            });

            notification.addEventListener('click', () => {
                window.focus();
                notification.close();
            });

            // Auto-fechar após 5 segundos
            setTimeout(() => notification.close(), 5000);
        }
    }

    showNotificationSettings() {
        // Criar modal de configurações
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-bell-fill me-2"></i>Configurações de Notificação
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            Notificações estão ativas!
                        </div>
                        
                        <h6>Tipos de Notificação:</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="notif-viagens" checked>
                            <label class="form-check-label" for="notif-viagens">
                                Atualizações de viagens
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="notif-relatorios" checked>
                            <label class="form-check-label" for="notif-relatorios">
                                Relatórios prontos
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="notif-sistema">
                            <label class="form-check-label" for="notif-sistema">
                                Atualizações do sistema
                            </label>
                        </div>
                        
                        <hr>
                        
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="notificationManager.testNotification()">
                            <i class="bi bi-bell me-1"></i>Testar Notificação
                        </button>
                        
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="notificationManager.disableNotifications()">
                            <i class="bi bi-bell-slash me-1"></i>Desativar
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="notificationManager.saveSettings()">Salvar</button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();

        // Remover modal quando fechado
        modal.addEventListener('hidden.bs.modal', () => {
            document.body.removeChild(modal);
        });
    }

    testNotification() {
        if (Notification.permission === 'granted') {
            const notification = new Notification('Teste de Notificação 🧪', {
                body: 'Esta é uma notificação de teste do Diário de Bordo.',
                icon: '/img/icon-192.png',
                tag: 'test',
                renotify: true,
                vibrate: [100, 50, 100, 50, 100]
            });

            notification.addEventListener('click', () => {
                window.focus();
                notification.close();
            });

            setTimeout(() => notification.close(), 4000);
        }
    }

    async disableNotifications() {
        try {
            if (this.registration) {
                const subscription = await this.registration.pushManager.getSubscription();
                if (subscription) {
                    await subscription.unsubscribe();
                    
                    // Notificar o servidor
                    await fetch('/api/notifications/unsubscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                }
            }

            this.updateNotificationIcon('default');
            
            // Fechar modal se estiver aberto
            const modals = document.querySelectorAll('.modal.show');
            modals.forEach(modal => {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            });

            alert('Notificações desativadas com sucesso!');
        } catch (error) {
            console.error('Erro ao desativar notificações:', error);
        }
    }

    saveSettings() {
        const settings = {
            viagens: document.getElementById('notif-viagens')?.checked || false,
            relatorios: document.getElementById('notif-relatorios')?.checked || false,
            sistema: document.getElementById('notif-sistema')?.checked || false
        };

        localStorage.setItem('notificationSettings', JSON.stringify(settings));
        
        // Fechar modal
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) bsModal.hide();
        });

        alert('Configurações salvas!');
    }

    showPermissionDeniedMessage() {
        alert('Para reativar as notificações, você precisa alterar as configurações do seu navegador.');
    }

    // Função utilitária para converter VAPID key
    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    // Função para mostrar notificação específica do sistema
    static showSystemNotification(title, body, options = {}) {
        if (Notification.permission === 'granted') {
            const notification = new Notification(title, {
                body: body,
                icon: '/img/icon-192.png',
                badge: '/img/badge-72x72.png',
                tag: options.tag || 'system',
                renotify: options.renotify || false,
                requireInteraction: options.requireInteraction || false,
                vibrate: [200, 100, 200],
                ...options
            });

            notification.addEventListener('click', () => {
                window.focus();
                if (options.url) {
                    window.location.href = options.url;
                }
                notification.close();
            });

            // Auto-fechar se especificado
            if (options.autoClose !== false) {
                setTimeout(() => notification.close(), options.duration || 5000);
            }

            return notification;
        }
        return null;
    }
}

// Inicializar quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    window.notificationManager = new PushNotificationManager();
});

// Exportar para uso global
window.PushNotificationManager = PushNotificationManager;
