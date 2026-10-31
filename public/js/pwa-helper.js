// PWA Helper - Service Worker Registration and Features

class PWAHelper {
  constructor() {
    this.isOnline = navigator.onLine;
    this.swRegistration = null;
    this.deferredPrompt = null;
    this.init();
  }

  init() {
    // Register service worker
    this.registerServiceWorker();

    // Listen for online/offline events
    window.addEventListener('online', () => this.handleOnline());
    window.addEventListener('offline', () => this.handleOffline());

    // Listen for install prompt
    window.addEventListener('beforeinstallprompt', (e) => this.handleBeforeInstallPrompt(e));

    // Listen for app installed
    window.addEventListener('appinstalled', () => this.handleAppInstalled());

    // Check for service worker updates
    this.checkForUpdates();
  }

  // Register service worker
  registerServiceWorker() {
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('/service-worker.js', { scope: '/' })
        .then((registration) => {
          this.swRegistration = registration;
          console.log('[PWA] Service Worker registered:', registration);

          // Check for updates periodically
          setInterval(() => {
            registration.update();
          }, 60000); // Check every minute

          // Listen for updates
          registration.addEventListener('updatefound', () => {
            const newWorker = registration.installing;
            if (newWorker) {
              newWorker.addEventListener('statechange', () => {
                if (newWorker.state === 'activated') {
                  this.promptUpdateAvailable();
                }
              });
            }
          });
        })
        .catch((error) => {
          console.error('[PWA] Service Worker registration failed:', error);
        });
    }
  }

  // Handle before install prompt
  handleBeforeInstallPrompt(e) {
    e.preventDefault();
    this.deferredPrompt = e;
    console.log('[PWA] Install prompt available');

    // Show install button if visible
    const installBtn = document.getElementById('installBtn');
    if (installBtn) {
      installBtn.hidden = false;
      installBtn.addEventListener('click', () => this.installApp());
    }
  }

  // Install the app
  async installApp() {
    if (!this.deferredPrompt) return;

    this.deferredPrompt.prompt();
    const { outcome } = await this.deferredPrompt.userChoice;
    console.log(`[PWA] User response to install prompt: ${outcome}`);

    this.deferredPrompt = null;

    const installBtn = document.getElementById('installBtn');
    if (installBtn) {
      installBtn.hidden = true;
    }
  }

  // Handle app installed
  handleAppInstalled() {
    console.log('[PWA] App installed successfully');
    this.deferredPrompt = null;

    const installBtn = document.getElementById('installBtn');
    if (installBtn) {
      installBtn.hidden = true;
    }

    // Show notification
    this.showNotification('App Installed', 'JRN Portfolio is now installed on your device.');
  }

  // Handle online status
  handleOnline() {
    this.isOnline = true;
    console.log('[PWA] App is online');

    // Update UI
    const offlineIndicator = document.getElementById('offlineIndicator');
    if (offlineIndicator) {
      offlineIndicator.style.display = 'none';
    }

    // Emit custom event
    window.dispatchEvent(new CustomEvent('pwa-online'));
  }

  // Handle offline status
  handleOffline() {
    this.isOnline = false;
    console.log('[PWA] App is offline');

    // Update UI
    const offlineIndicator = document.getElementById('offlineIndicator');
    if (offlineIndicator) {
      offlineIndicator.style.display = 'block';
    }

    // Emit custom event
    window.dispatchEvent(new CustomEvent('pwa-offline'));
  }

  // Check for updates
  checkForUpdates() {
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.ready.then((registration) => {
        registration.update();
      });
    }
  }

  // Prompt user about available update
  promptUpdateAvailable() {
    const updatePrompt = document.getElementById('updatePrompt');
    if (updatePrompt) {
      updatePrompt.style.display = 'block';

      const updateBtn = document.getElementById('updateBtn');
      if (updateBtn) {
        updateBtn.addEventListener('click', () => {
          if (this.swRegistration && this.swRegistration.waiting) {
            this.swRegistration.waiting.postMessage({ type: 'SKIP_WAITING' });
            window.location.reload();
          }
        });
      }

      const dismissBtn = document.getElementById('dismissUpdateBtn');
      if (dismissBtn) {
        dismissBtn.addEventListener('click', () => {
          updatePrompt.style.display = 'none';
        });
      }
    }
  }

  // Request notification permission
  async requestNotificationPermission() {
    if (!('Notification' in window)) {
      console.log('[PWA] Notifications not supported');
      return false;
    }

    if (Notification.permission === 'granted') {
      return true;
    }

    if (Notification.permission !== 'denied') {
      const permission = await Notification.requestPermission();
      return permission === 'granted';
    }

    return false;
  }

  // Show notification
  async showNotification(title, options = {}) {
    if (!('serviceWorker' in navigator && 'Notification' in window)) {
      console.log('[PWA] Notifications not supported');
      return;
    }

    const hasPermission = await this.requestNotificationPermission();
    if (!hasPermission) return;

    const defaultOptions = {
      icon: '/images/image-of-japheth-ralph.jpg',
      badge: '/images/image-of-japheth-ralph.jpg',
      tag: 'jrn-portfolio',
      ...options
    };

    if (navigator.serviceWorker.controller) {
      navigator.serviceWorker.controller.postMessage({
        type: 'SHOW_NOTIFICATION',
        title,
        options: defaultOptions
      });
    } else if (Notification.permission === 'granted') {
      new Notification(title, defaultOptions);
    }
  }

  // Get cache size (for debugging)
  async getCacheSize() {
    const cacheNames = await caches.keys();
    let size = 0;

    for (const cacheName of cacheNames) {
      const cache = await caches.open(cacheName);
      const keys = await cache.keys();

      for (const request of keys) {
        const response = await cache.match(request);
        if (response) {
          size += response.headers.get('content-length') || 0;
        }
      }
    }

    return size;
  }

  // Clear all caches (for debugging)
  async clearCaches() {
    const cacheNames = await caches.keys();
    await Promise.all(
      cacheNames.map((cacheName) => caches.delete(cacheName))
    );
    console.log('[PWA] All caches cleared');
  }

  // Request persistent storage
  async requestPersistentStorage() {
    if (navigator.storage && navigator.storage.persist) {
      const isPersistent = await navigator.storage.persist();
      console.log('[PWA] Persistent storage granted:', isPersistent);
      return isPersistent;
    }
    return false;
  }

  // Get storage info
  async getStorageInfo() {
    if (navigator.storage && navigator.storage.estimate) {
      const estimate = await navigator.storage.estimate();
      return {
        usage: estimate.usage,
        quota: estimate.quota,
        percentage: (estimate.usage / estimate.quota) * 100
      };
    }
    return null;
  }
}

// Initialize PWA Helper when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    window.pwaHelper = new PWAHelper();
  });
} else {
  window.pwaHelper = new PWAHelper();
}
