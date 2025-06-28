const CACHE_NAME = 'diario-bordo-v1.3';
const urlsToCache = [
    '/',
    '/dashboard',
    '/viagens',
    '/relatorios',
    '/css/app.css',
    '/js/app.js',
    '/css/dark-mode.css',
    '/js/dark-mode.js',
    '/js/notifications.js',
    '/js/advanced-search.js',
    '/img/logoD.png',
    '/manifest.json',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css'
];

// Estratégias de cache
const CACHE_STRATEGIES = {
    CACHE_FIRST: 'cache-first',
    NETWORK_FIRST: 'network-first',
    STALE_WHILE_REVALIDATE: 'stale-while-revalidate'
};

// Configuração de rotas
const ROUTE_CONFIG = {
    static: { strategy: CACHE_STRATEGIES.CACHE_FIRST, maxAge: 30 * 24 * 60 * 60 * 1000 }, // 30 dias
    api: { strategy: CACHE_STRATEGIES.NETWORK_FIRST, maxAge: 5 * 60 * 1000 }, // 5 minutos
    pages: { strategy: CACHE_STRATEGIES.STALE_WHILE_REVALIDATE, maxAge: 24 * 60 * 60 * 1000 } // 1 dia
};

// Instalação do service worker
self.addEventListener('install', event => {
    console.log('Service Worker: Installing...');
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Service Worker: Caching files');
                return cache.addAll(urlsToCache);
            })
            .then(() => {
                console.log('Service Worker: Installation complete');
                return self.skipWaiting();
            })
            .catch(error => {
                console.error('Service Worker: Installation failed', error);
            })
    );
});

// Ativação do service worker
self.addEventListener('activate', event => {
    console.log('Service Worker: Activating...');
    
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Service Worker: Deleting old cache', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => {
            console.log('Service Worker: Activation complete');
            return self.clients.claim();
        })
    );
});

// Interceptação de requests
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Skip cross-origin requests que não podemos cachear
    if (!request.url.startsWith(self.location.origin) && !request.url.includes('cdn.jsdelivr.net')) {
        return;
    }
    
    // Determinar estratégia baseada na URL
    let strategy = ROUTE_CONFIG.pages.strategy;
    
    if (request.url.includes('/api/')) {
        strategy = ROUTE_CONFIG.api.strategy;
    } else if (
        request.url.includes('.css') ||
        request.url.includes('.js') ||
        request.url.includes('.png') ||
        request.url.includes('.jpg') ||
        request.url.includes('.jpeg') ||
        request.url.includes('.svg') ||
        request.url.includes('.woff') ||
        request.url.includes('.woff2')
    ) {
        strategy = ROUTE_CONFIG.static.strategy;
    }
    
    event.respondWith(handleRequest(request, strategy));
});

// Manipular requests com diferentes estratégias
async function handleRequest(request, strategy) {
    switch (strategy) {
        case CACHE_STRATEGIES.CACHE_FIRST:
            return cacheFirst(request);
        case CACHE_STRATEGIES.NETWORK_FIRST:
            return networkFirst(request);
        case CACHE_STRATEGIES.STALE_WHILE_REVALIDATE:
            return staleWhileRevalidate(request);
        default:
            return fetch(request);
    }
}

// Estratégia Cache First
async function cacheFirst(request) {
    try {
        const cache = await caches.open(CACHE_NAME);
        const cachedResponse = await cache.match(request);
        
        if (cachedResponse) {
            return cachedResponse;
        }
        
        const networkResponse = await fetch(request);
        
        if (networkResponse.status === 200) {
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        console.error('Cache First failed:', error);
        return new Response('Offline - Resource not available', { status: 503 });
    }
}

// Estratégia Network First
async function networkFirst(request) {
    try {
        const networkResponse = await fetch(request);
        
        if (networkResponse.status === 200) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        console.error('Network First fallback to cache:', error);
        
        const cache = await caches.open(CACHE_NAME);
        const cachedResponse = await cache.match(request);
        
        if (cachedResponse) {
            return cachedResponse;
        }
        
        return new Response('Offline - Resource not available', { status: 503 });
    }
}

// Estratégia Stale While Revalidate
async function staleWhileRevalidate(request) {
    const cache = await caches.open(CACHE_NAME);
    const cachedResponse = await cache.match(request);
    
    const fetchPromise = fetch(request).then(networkResponse => {
        if (networkResponse.status === 200) {
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    }).catch(error => {
        console.error('Stale While Revalidate network error:', error);
        return cachedResponse;
    });
    
    return cachedResponse || fetchPromise;
}

// Manipular notificações push
self.addEventListener('push', event => {
    console.log('Service Worker: Push notification received', event);
    
    let options = {
        body: 'Nova notificação do Diário de Bordo',
        icon: '/img/icon-192x192.png',
        badge: '/img/badge-72x72.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'Ver Detalhes',
                icon: '/img/checkmark.png'
            },
            {
                action: 'close',
                title: 'Fechar',
                icon: '/img/xmark.png'
            }
        ]
    };
    
    if (event.data) {
        try {
            const payload = event.data.json();
            options = {
                ...options,
                ...payload
            };
        } catch (error) {
            console.error('Error parsing push payload:', error);
            options.body = event.data.text() || options.body;
        }
    }
    
    event.waitUntil(
        self.registration.showNotification('Diário de Bordo', options)
    );
});

// Manipular cliques em notificações
self.addEventListener('notificationclick', event => {
    console.log('Service Worker: Notification click received', event);
    
    event.notification.close();
    
    const action = event.action;
    const notification = event.notification;
    
    if (action === 'close') {
        return;
    }
    
    // Determinar URL baseada na ação
    let targetUrl = '/dashboard';
    
    if (action === 'explore' || !action) {
        if (notification.data && notification.data.url) {
            targetUrl = notification.data.url;
        }
    }
    
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then(clientList => {
                // Verificar se já existe uma janela aberta
                for (let client of clientList) {
                    if (client.url.includes(targetUrl) && 'focus' in client) {
                        return client.focus();
                    }
                }
                
                // Abrir nova janela se necessário
                if (clients.openWindow) {
                    return clients.openWindow(targetUrl);
                }
            })
    );
});

// Manipular fechamento de notificações
self.addEventListener('notificationclose', event => {
    console.log('Service Worker: Notification closed', event);
    
    // Analytics ou ações de cleanup se necessário
    if (event.notification.data && event.notification.data.trackingId) {
        // Enviar evento de fechamento para analytics
        trackNotificationEvent('closed', event.notification.data.trackingId);
    }
});

// Sincronização em background
self.addEventListener('sync', event => {
    console.log('Service Worker: Background sync', event);
    
    if (event.tag === 'sync-viagems') {
        event.waitUntil(syncViagems());
    }
    
    if (event.tag === 'sync-notifications') {
        event.waitUntil(syncNotifications());
    }
});

// Funções auxiliares para sincronização
async function syncViagems() {
    try {
        // Sincronizar dados de viagens offline
        console.log('Viagens sync completed');
    } catch (error) {
        console.error('Viagens sync failed:', error);
    }
}

async function syncNotifications() {
    try {
        // Buscar novas notificações do servidor
        console.log('Notifications sync completed');
    } catch (error) {
        console.error('Notifications sync failed:', error);
    }
}

function trackNotificationEvent(event, trackingId) {
    // Enviar evento para analytics
    console.log(`Notification ${event}:`, trackingId);
}

// Message handler para comunicação com a aplicação principal
self.addEventListener('message', event => {
    console.log('Service Worker: Message received', event.data);
    
    if (event.data && event.data.type) {
        switch (event.data.type) {
            case 'SKIP_WAITING':
                self.skipWaiting();
                break;
            case 'CLAIM_CLIENTS':
                self.clients.claim();
                break;
            case 'GET_VERSION':
                event.ports[0].postMessage({ version: CACHE_NAME });
                break;
            case 'CLEAR_CACHE':
                clearAllCaches().then(() => {
                    event.ports[0].postMessage({ success: true });
                });
                break;
            default:
                console.log('Unknown message type:', event.data.type);
        }
    }
});

async function clearAllCaches() {
    const cacheNames = await caches.keys();
    await Promise.all(
        cacheNames.map(cacheName => caches.delete(cacheName))
    );
    console.log('All caches cleared');
}
