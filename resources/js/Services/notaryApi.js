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

const getModelRoutes = (model, params) => {
    let route = {};

    if (model === 'note') {
        const parentModel = Object.keys(params).filter((k) => k !== 'note')[0];
        route = routeNames[model][parentModel];
    } else {
        route = routeNames[model];
    }

    return route;
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
    },
    address: {
        index: 'addresses.index',
        store: 'addresses.store',
        show: 'addresses.show',
        update: 'addresses.update',
        destroy: 'addresses.destroy',
    },
    note: {
        person: {
            index: 'people.notes.index',
            store: 'people.notes.store',
            show: 'people.notes.show',
            update: 'people.notes.update',
            destroy: 'people.notes.destroy',
        },
    },
};

export const axiosCreate = ({
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
        method: 'post',
        route: route(getModelRoutes(model, routeParams).store, routeParams),
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
        route: route(getModelRoutes(model, routeParams).update, routeParams),
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
        route: route(getModelRoutes(model, routeParams).destroy, routeParams),
        onSuccess,
        onError,
        onFinally,
    });
};
