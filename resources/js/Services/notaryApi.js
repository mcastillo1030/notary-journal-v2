import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const page = usePage();

const axiosRequest = ({
    method,
    route,
    params = {},
    onSuccess = () => {},
    onError = () => {},
    onFinally = () => {},
}) => {
    if (!method || !route) {
        return;
    }

    if (method === 'get' && Object.keys(params).length > 0) {
        route += '?' + new URLSearchParams(params).toString();
    }

    axios[method](route, params, useAxiosCsrf())
        .then(onSuccess)
        .catch(onError)
        .finally(onFinally);
};

export const useAxiosCsrf = () => {
    return {
        headers: {
            'X-CSRF-TOKEN': page.props.csrf,
        },
    };
};

const hasInvalidParams = (model, params = {}) => {
    return !model || (model === 'note' && Object.keys(params).length === 0);
};

export const routeNames = {
    person: {
        index: 'people.index',
        store: 'people.storeAjax',
        show: 'people.show',
        update: 'people.updateAjax',
        destroy: 'people.destroyAjax',
        list: 'people.list',
        attach: 'people.attach',
        detach: 'people.detach',
    },
    address: {
        index: 'addresses.index',
        store: 'addresses.store',
        show: 'addresses.show',
        update: 'addresses.update',
        destroy: 'addresses.destroy',
        list: 'addresses.list',
        attach: 'addresses.attach',
        detach: 'addresses.detach',
    },
    note: {
        index: 'notes.index', // unused
        store: 'notes.store',
        show: 'notes.show', // unused
        update: 'notes.update',
        destroy: 'notes.destroy',
    },
};

export const axiosCreate = ({
    model = null,
    params = {},
    onSuccess = () => {},
    onError = () => {},
    onFinally = () => {},
}) => {
    if (hasInvalidParams(model, params)) {
        return;
    }

    axiosRequest({
        method: 'post',
        route: route(routeNames[model].store),
        params,
        onSuccess,
        onError,
        onFinally,
    });
};

export const axiosUpdate = ({
    model = null,
    routeParams = {},
    params = {},
    onSuccess = () => {},
    onError = () => {},
    onFinally = () => {},
}) => {
    if (hasInvalidParams(model, routeParams)) {
        return;
    }

    axiosRequest({
        method: 'patch',
        route: route(routeNames[model].update, routeParams),
        params,
        onSuccess,
        onError,
        onFinally,
    });
};

export const axiosDestroy = ({
    model = null,
    routeParams = {},
    onSuccess = () => {},
    onError = () => {},
    onFinally = () => {},
}) => {
    if (hasInvalidParams(model, routeParams)) {
        return;
    }

    axiosRequest({
        method: 'delete',
        route: route(routeNames[model].destroy, routeParams),
        onSuccess,
        onError,
        onFinally,
    });
};

export const axiosList = ({
    model = null,
    params = {},
    onSuccess = () => {},
    onError = () => {},
    onFinally = () => {},
}) => {
    if (!model) {
        return;
    }

    axiosRequest({
        method: 'get',
        route: route(routeNames[model].list),
        params,
        onSuccess,
        onError,
        onFinally,
    });
};

export const axiosAttach = ({
    model = null,
    routeParams = {},
    params = {},
    onSuccess = () => {},
    onError = () => {},
    onFinally = () => {},
}) => {
    if (!model || Object.keys(params).length === 0) {
        return;
    }

    axiosRequest({
        method: 'patch',
        route: route(routeNames[model].attach, routeParams),
        params,
        onSuccess,
        onError,
        onFinally,
    });
};

export const axiosDetach = ({
    model = null,
    routeParams = {},
    params = {},
    onSuccess = () => {},
    onError = () => {},
    onFinally = () => {},
}) => {
    if (!model || Object.keys(params).length === 0) {
        return;
    }

    console.log(routeParams);

    axiosRequest({
        method: 'patch',
        route: route(routeNames[model].detach, routeParams),
        params,
        onSuccess,
        onError,
        onFinally,
    });
};
