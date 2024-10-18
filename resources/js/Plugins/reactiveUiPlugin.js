/**
 * This plugin provides global functions for reactive UI components
 * imported from PrimeVue. It is used to provide a global confirmDelete
 * and various toast functions to the app.
 *
 * @param {App} app
 * @returns {void}
 * @see https://v3.vuejs.org/guide/plugins.html
 */
import { axiosDestroy } from '@/Services/notaryApi';

export default {
    install: (app) => {
        // Connect to the global confirm service
        const { $confirm, $toast } = app.config.globalProperties;

        const showToast = (severity, summary, detail, delay) => {
            $toast.add({
                severity,
                summary,
                detail,
                life: delay,
            });
        };

        // Provide the confirmDelete function to the app
        app.provide(
            'confirmDestroy',
            ({ model, params, onSuccess, onError }) => {
                $confirm.require({
                    message: `Do you want to delete this ${model}?`,
                    header: 'Danger Zone',
                    icon: 'pi pi-info-circle',
                    rejectLabel: 'Cancel',
                    rejectProps: {
                        label: 'Cancel',
                        severity: 'secondary',
                        outlined: true,
                    },
                    acceptProps: {
                        label: 'Delete',
                        severity: 'danger',
                    },
                    accept: () =>
                        axiosDestroy({
                            model,
                            routeParams: params,
                            onSuccess,
                            onError,
                        }),
                });
            },
        );

        // Provide the showSuccesstoast function to the app
        app.provide('showSuccessToast', ({ message, delay = 3000 }) =>
            showToast('success', 'Success', message, delay),
        );

        // Provide the showErrorToast function to the app
        app.provide('showErrorToast', ({ message, delay = 3000 }) =>
            showToast('error', 'Error', message, delay),
        );

        // Provide the showInfoToast function to the app
        app.provide('showInfoToast', ({ message, delay = 3000 }) =>
            showToast('info', 'Info', message, delay),
        );

        // Provide the showWarnToast function to the app
        app.provide('showWarnToast', ({ message, delay = 3000 }) =>
            showToast('warn', 'Warning', message, delay),
        );

        // Provide the showUnknownErrorToast function to the app
        app.provide(
            'showUnknownErrorToast',
            ({
                message = 'An unknown error occurred. Please try again.',
                delay = 3000,
            }) => showToast('error', 'Error', message, delay),
        );
    },
};
