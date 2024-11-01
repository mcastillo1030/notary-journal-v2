import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { definePreset } from '@primevue/themes';
import Aura from '@primevue/themes/aura';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createPinia } from 'pinia';
import PrimeVue from 'primevue/config';
import ConfirmationService from 'primevue/confirmationservice';
import ToastService from 'primevue/toastservice';
import { createSSRApp, h } from 'vue';
import { RayPlugin } from 'vue-ray';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import reactiveUiPlugin from './Plugins/reactiveUiPlugin';
import { loadTheme } from './Utils/theme';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const pinia = createPinia();

const primaryPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: '{zinc.50}',
            100: '{zinc.100}',
            200: '{zinc.200}',
            300: '{zinc.300}',
            400: '{zinc.400}',
            500: '{zinc.500}',
            600: '{zinc.600}',
            700: '{zinc.700}',
            800: '{zinc.800}',
            900: '{zinc.900}',
            950: '{zinc.950}',
        },
        colorScheme: {
            light: {
                primary: {
                    color: '{zinc.950}',
                    inverseColor: '#ffffff',
                    hoverColor: '{zinc.900}',
                    activeColor: '{zinc.800}',
                },
                highlight: {
                    background: '{zinc.950}',
                    focusBackground: '{zinc.700}',
                    color: '#ffffff',
                    focusColor: '#ffffff',
                },
            },
            dark: {
                primary: {
                    color: '{zinc.50}',
                    inverseColor: '{zinc.950}',
                    hoverColor: '{zinc.100}',
                    activeColor: '{zinc.200}',
                },
                highlight: {
                    background: 'rgba(250, 250, 250, .16)',
                    focusBackground: 'rgba(250, 250, 250, .24)',
                    color: 'rgba(255,255,255,.87)',
                    focusColor: 'rgba(255,255,255,.87)',
                },
            },
        },
    },
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        loadTheme();

        return createSSRApp({ render: () => h(App, props) })
            .use(plugin)
            .use(RayPlugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: primaryPreset,
                    options: {
                        darkModeSelector: '.nj-dark',
                    },
                },
            })
            .use(ConfirmationService)
            .use(ToastService)
            .use(pinia)
            .use(reactiveUiPlugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
