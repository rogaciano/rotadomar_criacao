/**
 * Push Notifications Module
 * Registra subscription no servidor para receber notificações push
 */

// VAPID public key - deve ser gerada no servidor e configurada aqui
// Gerar com: php artisan tinker → web_push_generate_keys() ou openssl
const VAPID_PUBLIC_KEY = localStorage.getItem('vapid_public_key') || '';

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

async function initPush() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        console.log('Push not supported');
        return;
    }

    if (!VAPID_PUBLIC_KEY) {
        console.log('VAPID public key not configured');
        return;
    }

    try {
        const registration = await navigator.serviceWorker.ready;

        // Check existing subscription
        let subscription = await registration.pushManager.getSubscription();

        if (!subscription) {
            // Request permission
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                console.log('Push permission denied');
                return;
            }

            // Subscribe
            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY),
            });
        }

        // Send subscription to server
        await API.pushSubscribe(subscription);
        console.log('Push subscription registered');
    } catch (error) {
        console.error('Push init error:', error);
    }
}
