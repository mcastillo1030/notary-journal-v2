import { routeNames } from '@/Services/notaryApi';
import { router } from '@inertiajs/vue3';
import { toValue } from 'vue';

const checkNeedsIndex = (data) => {
    return data.type === 'person' && data.destroyed;
};

const checkIsIndex = (type) => {
    if (type !== 'note') {
        return route().current(routeNames[type].index);
    }

    return false;
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

        const propsMap = {
            person: 'person',
            address: 'addresses',
            note: 'notes',
        };

        router.reload({
            only: [propsMap[model]],
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
            const isIndex = checkIsIndex(response.data.type);

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
