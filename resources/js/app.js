import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import mqttService from './Services/mqttService';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Initialize MQTT connection
mqttService.connect();

// Handle MQTT messages
mqttService.onMessage(({ topic, payload }) => {
    if (topic === 'video/processed') {
        // Show notification
        if (typeof window.showNotification === 'function') {
            window.showNotification({
                type: payload.status ? 'success' : 'error',
                title: payload.title || 'Video Processing',
                message: payload.message || 'Video has been processed',
                url: payload.url || null
            });
        }
    }
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
