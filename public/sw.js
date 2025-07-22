const CACHE_NAME = 'diario-bordo-v2.0';
const DATA_CACHE_NAME = 'diario-bordo-data-v2.0';
const OFFLINE_CACHE_NAME = 'diario-bordo-offline-v2.0';

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
    '/js/dashboard-analytics.js',
    '/img/logoD.png',
    '/manifest.json',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css',
    'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js'
];

// URLs para cache offline específico
const offlinePages = [
    '/offline',
    '/viagens/offline',
    '/dashboard/offline'
];

// Estratégias de cache avançadas
const CACHE_STRATEGIES = {
    CACHE_FIRST: 'cache-first',
    NETWORK_FIRST: 'network-first',
    STALE_WHILE_REVALIDATE: 'stale-while-revalidate',
    CACHE_ONLY: 'cache-only',
    NETWORK_ONLY: 'network-only'
};

// Configuração avançada de rotas
const ROUTE_CONFIG = {
    static: { 
        strategy: CACHE_STRATEGIES.CACHE_FIRST, 
        maxAge: 30 * 24 * 60 * 60 * 1000, // 30 dias
        maxEntries: 50
    },
    api: { 
        strategy: CACHE_STRATEGIES.NETWORK_FIRST, 
        maxAge: 5 * 60 * 1000, // 5 minutos
        maxEntries: 20
    },
    pages: { 
        strategy: CACHE_STRATEGIES.STALE_WHILE_REVALIDATE, 
        maxAge: 24 * 60 * 60 * 1000, // 1 dia
        maxEntries: 30
    },
    offline: {
        strategy: CACHE_STRATEGIES.CACHE_ONLY,
        maxAge: 7 * 24 * 60 * 60 * 1000 // 7 dias
    }
};

// Instalação do service worker com cache múltiplo
self.addEventListener('install', event => {
    console.log('Service Worker: Installing v2.0...');
    
    event.waitUntil(
        Promise.all([
            // Cache principal
            caches.open(CACHE_NAME).then(cache => {
                console.log('Service Worker: Caching main files');
                return cache.addAll(urlsToCache);
            }),
            // Cache offline
            caches.open(OFFLINE_CACHE_NAME).then(cache => {
                console.log('Service Worker: Caching offline pages');
                return cache.addAll(offlinePages.map(url => new Request(url, { mode: 'navigate' })));
            }),
            // Cache de dados
            caches.open(DATA_CACHE_NAME).then(cache => {
                console.log('Service Worker: Data cache ready');
                return Promise.resolve();
            })
        ])
        .then(() => {
            console.log('Service Worker: Installation complete');
            return self.skipWaiting();
        })
        .catch(error => {
            console.error('Service Worker: Installation failed', error);
        })
    );
});

// Ativação do service worker com limpeza de caches antigos
self.addEventListener('activate', event => {
    console.log('Service Worker: Activating v2.0...');
    
    const currentCaches = [CACHE_NAME, DATA_CACHE_NAME, OFFLINE_CACHE_NAME];
    
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (!currentCaches.includes(cacheName)) {
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

// Sincronização em background avançada
self.addEventListener('sync', event => {
    console.log('Service Worker: Background sync triggered', event.tag);
    
    switch (event.tag) {
        case 'sync-viagens':
            event.waitUntil(syncViagens());
            break;
        case 'sync-notifications':
            event.waitUntil(syncNotifications());
            break;
        case 'sync-analytics':
            event.waitUntil(syncAnalytics());
            break;
        case 'sync-offline-actions':
            event.waitUntil(syncOfflineActions());
            break;
    }
});

// Funções de sincronização avançadas
async function syncViagens() {
    try {
        console.log('Service Worker: Syncing viagens...');
        
        // Buscar viagens offline armazenadas
        const offlineViagens = await getOfflineViagens();
        
        // Enviar viagens offline para o servidor
        for (const viagem of offlineViagens) {
            try {
                const response = await fetch('/api/viagens', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': await getCSRFToken()
                    },
                    body: JSON.stringify(viagem)
                });
                
                if (response.ok) {
                    await removeOfflineViagem(viagem.id);
                    console.log('Viagem sincronizada:', viagem.id);
                }
            } catch (error) {
                console.error('Erro ao sincronizar viagem:', error);
            }
        }
        
        console.log('Viagens sync completed');
    } catch (error) {
        console.error('Viagens sync failed:', error);
    }
}

async function syncNotifications() {
    try {
        console.log('Service Worker: Syncing notifications...');
        
        const response = await fetch('/api/analytics/notifications');
        if (response.ok) {
            const notifications = await response.json();
            
            // Atualizar cache de notificações
            const cache = await caches.open(DATA_CACHE_NAME);
            await cache.put('/api/analytics/notifications', new Response(JSON.stringify(notifications)));
            
            // Mostrar notificações não lidas
            const unreadNotifications = notifications.filter(n => !n.read);
            for (const notification of unreadNotifications.slice(0, 3)) {
                await self.registration.showNotification(notification.title, {
                    body: notification.body,
                    icon: '/img/icon-192x192.png',
                    badge: '/img/icon-192x192.png',
                    data: notification.data
                });
            }
        }
        
        console.log('Notifications sync completed');
    } catch (error) {
        console.error('Notifications sync failed:', error);
    }
}

async function syncAnalytics() {
    try {
        console.log('Service Worker: Syncing analytics...');
        
        const response = await fetch('/api/analytics/dashboard');
        if (response.ok) {
            const analyticsData = await response.json();
            
            // Atualizar cache de analytics
            const cache = await caches.open(DATA_CACHE_NAME);
            await cache.put('/api/analytics/dashboard', new Response(JSON.stringify(analyticsData)));
            
            // Notificar clientes sobre dados atualizados
            const clients = await self.clients.matchAll();
            clients.forEach(client => {
                client.postMessage({
                    type: 'ANALYTICS_UPDATED',
                    data: analyticsData
                });
            });
        }
        
        console.log('Analytics sync completed');
    } catch (error) {
        console.error('Analytics sync failed:', error);
    }
}

async function syncOfflineActions() {
    try {
        console.log('Service Worker: Syncing offline actions...');
        
        const offlineActions = await getOfflineActions();
        
        for (const action of offlineActions) {
            try {
                const response = await fetch(action.url, {
                    method: action.method,
                    headers: action.headers,
                    body: action.body
                });
                
                if (response.ok) {
                    await removeOfflineAction(action.id);
                    console.log('Offline action synced:', action.id);
                }
            } catch (error) {
                console.error('Error syncing offline action:', error);
            }
        }
        
        console.log('Offline actions sync completed');
    } catch (error) {
        console.error('Offline actions sync failed:', error);
    }
}

// Funções auxiliares para gerenciamento offline
async function getOfflineViagens() {
    try {
        const db = await openIndexedDB();
        const transaction = db.transaction(['offline_viagens'], 'readonly');
        const store = transaction.objectStore('offline_viagens');
        const request = store.getAll();
        
        return new Promise((resolve, reject) => {
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    } catch (error) {
        console.error('Erro ao buscar viagens offline:', error);
        return [];
    }
}

async function removeOfflineViagem(id) {
    try {
        const db = await openIndexedDB();
        const transaction = db.transaction(['offline_viagens'], 'readwrite');
        const store = transaction.objectStore('offline_viagens');
        return store.delete(id);
    } catch (error) {
        console.error('Erro ao remover viagem offline:', error);
    }
}

async function getOfflineActions() {
    try {
        const db = await openIndexedDB();
        const transaction = db.transaction(['offline_actions'], 'readonly');
        const store = transaction.objectStore('offline_actions');
        const request = store.getAll();
        
        return new Promise((resolve, reject) => {
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    } catch (error) {
        console.error('Erro ao buscar ações offline:', error);
        return [];
    }
}

async function removeOfflineAction(id) {
    try {
        const db = await openIndexedDB();
        const transaction = db.transaction(['offline_actions'], 'readwrite');
        const store = transaction.objectStore('offline_actions');
        return store.delete(id);
    } catch (error) {
        console.error('Erro ao remover ação offline:', error);
    }
}

async function openIndexedDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('DiarioBordoDB', 1);
        
        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);
        
        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            
            // Criar object stores se não existirem
            if (!db.objectStoreNames.contains('offline_viagens')) {
                const viagensStore = db.createObjectStore('offline_viagens', { keyPath: 'id' });
                viagensStore.createIndex('timestamp', 'timestamp', { unique: false });
            }
            
            if (!db.objectStoreNames.contains('offline_actions')) {
                const actionsStore = db.createObjectStore('offline_actions', { keyPath: 'id' });
                actionsStore.createIndex('timestamp', 'timestamp', { unique: false });
            }
            
            if (!db.objectStoreNames.contains('cached_data')) {
                const dataStore = db.createObjectStore('cached_data', { keyPath: 'key' });
                dataStore.createIndex('timestamp', 'timestamp', { unique: false });
            }
        };
    });
}

async function getCSRFToken() {
    const cache = await caches.open(DATA_CACHE_NAME);
    const response = await cache.match('/csrf-token');
    
    if (response) {
        const data = await response.json();
        return data.token;
    }
    
    // Fallback para buscar token do DOM
    try {
        const response = await fetch('/');
        const html = await response.text();
        const match = html.match(/<meta name="csrf-token" content="([^"]+)"/);
        return match ? match[1] : null;
    } catch (error) {
        console.error('Erro ao obter CSRF token:', error);
        return null;
    }
}

// Gerenciamento de conexão
self.addEventListener('online', () => {
    console.log('Service Worker: Back online, syncing data...');
    self.registration.sync.register('sync-offline-actions');
    self.registration.sync.register('sync-viagens');
    self.registration.sync.register('sync-notifications');
});

self.addEventListener('offline', () => {
    console.log('Service Worker: Gone offline, enabling offline mode...');
});

// Mensagens do cliente
self.addEventListener('message', event => {
    console.log('Service Worker: Message received', event.data);
    
    if (event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    
    if (event.data.type === 'CACHE_VIAGEM') {
        cacheOfflineViagem(event.data.viagem);
    }
    
    if (event.data.type === 'CACHE_ACTION') {
        cacheOfflineAction(event.data.action);
    }
    
    if (event.data.type === 'FORCE_SYNC') {
        self.registration.sync.register(event.data.syncTag || 'sync-offline-actions');
    }
});

async function cacheOfflineViagem(viagem) {
    try {
        const db = await openIndexedDB();
        const transaction = db.transaction(['offline_viagens'], 'readwrite');
        const store = transaction.objectStore('offline_viagens');
        
        const viagemData = {
            ...viagem,
            id: Date.now().toString(),
            timestamp: Date.now(),
            synced: false
        };
        
        await store.add(viagemData);
        console.log('Viagem salva offline:', viagemData.id);
    } catch (error) {
        console.error('Erro ao salvar viagem offline:', error);
    }
}

async function cacheOfflineAction(action) {
    try {
        const db = await openIndexedDB();
        const transaction = db.transaction(['offline_actions'], 'readwrite');
        const store = transaction.objectStore('offline_actions');
        
        const actionData = {
            ...action,
            id: Date.now().toString(),
            timestamp: Date.now(),
            synced: false
        };
        
        await store.add(actionData);
        console.log('Ação salva offline:', actionData.id);
    } catch (error) {
        console.error('Erro ao salvar ação offline:', error);
    }
}

// ...existing code...
