const CACHE_NAME = 'jrn-portfolio-v2';
const STATIC_ASSETS = [
  '/',
  '/manifest.json',
  '/offline.html',
  '/css/style.css',
  '/js/script.js',
  '/js/dashboard.js',
  '/js/pwa-helper.js',
  '/images/image-of-japheth-ralph.jpg'
];

const API_CACHE_NAME = 'jrn-api-v2';
const DASHBOARD_CACHE_NAME = 'jrn-dashboard-v2';

// Install event - cache static assets
self.addEventListener('install', (event) => {
  console.log('[Service Worker] Installing...');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('[Service Worker] Caching static assets');
        return cache.addAll(STATIC_ASSETS);
      })
      .then(() => self.skipWaiting())
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
  console.log('[Service Worker] Activating...');
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME && 
              cacheName !== API_CACHE_NAME && 
              cacheName !== DASHBOARD_CACHE_NAME) {
            console.log('[Service Worker] Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch event - caching strategies
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip non-GET requests
  if (request.method !== 'GET') {
    return;
  }

  if (['/dashboard', '/profile', '/forgot-password', '/reset-password'].includes(url.pathname)) {
    return;
  }

  // API requests - Network first with cache fallback
  if (url.pathname.startsWith('/api') || url.pathname === '/profile') {
    event.respondWith(
      fetch(request)
        .then((response) => {
          // Cache successful responses
          if (response.status === 200) {
            const cache = caches.open(API_CACHE_NAME);
            cache.then((c) => c.put(request, response.clone()));
          }
          return response;
        })
        .catch(() => {
          // Return cached API response or offline error
          return caches.match(request).then((cached) => {
            if (cached) {
              return cached;
            }
            return new Response(
              JSON.stringify({ error: 'Offline - cached data unavailable' }),
              { status: 503, headers: { 'Content-Type': 'application/json' } }
            );
          });
        })
    );
    return;
  }

  // Dashboard page - Network first with cache fallback
  if (url.pathname === '/dashboard' || request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .then((response) => {
          if (response.status === 200) {
            const cache = caches.open(DASHBOARD_CACHE_NAME);
            cache.then((c) => c.put(request, response.clone()));
          }
          return response;
        })
        .catch(() => {
          return caches.match(request).then((cached) => {
            if (cached) {
              return cached;
            }
            // Return offline page for navigation requests
            if (request.mode === 'navigate') {
              return caches.match('/offline.html').catch(() => {
                return new Response(
                  '<html><body><h1>Offline</h1><p>You are offline. Please check your connection.</p></body></html>',
                  { headers: { 'Content-Type': 'text/html' } }
                );
              });
            }
            return new Response('Not found', { status: 404 });
          });
        })
    );
    return;
  }

  // Static assets - Cache first
  if (request.destination === 'style' || 
      request.destination === 'script' || 
      request.destination === 'image' ||
      request.destination === 'font') {
    event.respondWith(
      caches.match(request).then((cached) => {
        if (cached) {
          return cached;
        }
        return fetch(request)
          .then((response) => {
            if (response.status === 200) {
              const cache = caches.open(CACHE_NAME);
              cache.then((c) => c.put(request, response.clone()));
            }
            return response;
          })
          .catch(() => new Response('Asset not found', { status: 404 }));
      })
    );
    return;
  }

  // All other requests - Network first with cache fallback
  event.respondWith(
    fetch(request)
      .then((response) => {
        if (response.status === 200) {
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(request, response.clone());
          });
        }
        return response;
      })
      .catch(() => {
        return caches.match(request).then((cached) => {
          return cached || new Response('Network request failed', { status: 503 });
        });
      })
  );
});

// Background sync for future use
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-data') {
    event.waitUntil(
      fetch('/api/sync')
        .then((response) => {
          console.log('[Service Worker] Background sync completed');
          return response;
        })
        .catch((error) => {
          console.error('[Service Worker] Background sync failed:', error);
        })
    );
  }
});

// Push notifications for future use
self.addEventListener('push', (event) => {
  const data = event.data ? event.data.json() : {};
  const options = {
    body: data.body || 'New notification',
    icon: '/images/image-of-japheth-ralph.jpg',
    badge: '/images/image-of-japheth-ralph.jpg',
    tag: 'jrn-portfolio',
    requireInteraction: false
  };

  event.waitUntil(
    self.registration.showNotification(data.title || 'JRN Portfolio', options)
  );
});

// Message handler for skip waiting and notifications
self.addEventListener('message', (event) => {
  if (!event.data) return;

  if (event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }

  if (event.data.type === 'SHOW_NOTIFICATION') {
    const { title, options } = event.data;
    self.registration.showNotification(title, options);
  }
});

// Notification click handler
self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true })
      .then((clientList) => {
        // Focus existing window if available
        for (const client of clientList) {
          if (client.url === '/' && 'focus' in client) {
            return client.focus();
          }
        }
        // Open new window if needed
        if (clients.openWindow) {
          return clients.openWindow('/dashboard');
        }
      })
  );
});
