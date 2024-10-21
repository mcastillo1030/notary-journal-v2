import { routeNames } from '@/Services/notaryApi';
import { router } from '@inertiajs/vue3';
import { toValue } from 'vue';

const pluralize = (str) => {
    if (str === 'person') {
        return 'people';
    }

    if (str === 'address') {
        return 'addresses';
    }

    return str + 's';
};

const getReloadOnly = (model) => {
    // pluralize the model name if it's not 'note' && the current route is not [model].show
    return route().current(routeNames[model].show)
        ? [model]
        : [pluralize(model)];
};

const checkNeedsIndex = (data) => {
    return (
        (data.type === 'person' || data.type === 'address') &&
        data.destroyed &&
        route().current(routeNames[data.type].show)
    );
};

export const useModelApiResponses = (cbs) => {
    const args = toValue(cbs);
    const callbacks = {
        onIndexReload: () => {},
        onPartialReload: () => {},
        onSuccessRedirect: () => {},
        onResponseError: (err) => console.error(err),
        onResponseHasErrors: (errs) => console.error(errs),
        onResponseComplete: () => {},
        onErrorResponseHasErrors: (errs) => console.error(errs),
        onErrorResponseHasMessage: (msg) => console.error(msg),
        onUnknownError: () => console.error('Unknown error.'),
        onErrorResponseComplete: () => {},
    };

    for (const [key, value] of Object.entries(callbacks)) {
        callbacks[key] = args[key] ?? value;
    }

    const partialReload = ({ model = '', message = '' }) => {
        if (!model) {
            return;
        }

        router.reload({
            only: getReloadOnly(model),
            onFinish: () => {
                callbacks.onPartialReload(message);
            },
        });
    };

    const handleAxiosResponse = (response) => {
        if (!response.data) {
            return;
        }

        const isSuccess =
            response.data.destroyed ||
            response.data.created ||
            response.data.updated;

        if (isSuccess) {
            const isIndex = route().current(
                routeNames[response.data.type].index,
            );

            if (isIndex) {
                router.reload({
                    onFinish: callbacks.onIndexReload(response.data.message),
                });
            } else {
                const needsIndex = checkNeedsIndex(response.data);
                needsIndex &&
                    router.visit(route(routeNames[response.data.type].index), {
                        onFinish: () => {
                            callbacks.onSuccessRedirect(response.data.message);
                        },
                    });
                !needsIndex &&
                    partialReload({
                        model: response.data.type,
                        message: response.data.message,
                    });
            }
        } else {
            response.data.errors &&
                callbacks.onResponseHasErrors(response.data.errors);
            callbacks.onResponseError(response.data.message);
        }

        callbacks.onResponseComplete();
    };

    const handleAxiosError = (error) => {
        if (
            error.response &&
            error.response.status === 422 &&
            error.response.data.errors
        ) {
            callbacks.onErrorResponseHasErrors(error.response.data.errors);
            callbacks.onErrorResponseHasMessage(error.response.data.message);
        } else {
            callbacks.onUnknownError();
        }

        callbacks.onErrorResponseComplete();
    };

    return {
        handleAxiosResponse,
        handleAxiosError,
    };
};
