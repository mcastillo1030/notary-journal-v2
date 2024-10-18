<script setup>
import Button from 'primevue/button';
import Menu from 'primevue/menu';
import { inject, ref } from 'vue';
import { useModelApiResponses } from '@/Composables/useModelApiResponses';

const menu = ref();
const addressItem = ref();
const confirmDestroy = inject('confirmDestroy');
const showSuccessToast = inject('showSuccessToast');
const showErrorToast = inject('showErrorToast');
const showUnknownErrorToast = inject('showUnknownErrorToast');
const { handleAxiosResponse, handleAxiosError } = useModelApiResponses({
    onPartialReload: (message) => showSuccessToast({ message }),
    onResponseError: (message) => showErrorToast({ message }),
    onUnknownError: showUnknownErrorToast,
});
const items = ref([
    {
        label: 'Options',
        items: [
            {
                label: 'Edit',
                icon: 'pi pi-pencil',
                command: () =>
                    document.dispatchEvent(
                        new CustomEvent('address.update', {
                            detail: JSON.parse(addressItem.value.dataset.edit),
                        }),
                    ),
            },
            {
                label: 'Delete',
                icon: 'pi pi-trash',
                command: () =>
                    confirmDestroy({
                        model: 'address',
                        params: { address: addressItem.value.dataset.id },
                        onSuccess: handleAxiosResponse,
                        onError: handleAxiosError,
                    }),
            },
        ],
    },
]);

const getEditableAddressAttributes = (address) => {
    return {
        id: address.id,
        line_1: address.line_1,
        line_2: address.line_2,
        city: address.city,
        state: address.state,
        zip: address.zip,
    };
};

const toggle = (event) => {
    menu.value.toggle(event);
};

defineProps({
    address: Object,
});
</script>

<template>
    <div
        ref="addressItem"
        class="nj-address-wm absolute right-4 top-1/2 flex -translate-y-1/2 justify-end"
        :data-id="address.id"
        :data-edit="JSON.stringify(getEditableAddressAttributes(address))"
    >
        <Button
            type="button"
            icon="pi pi-ellipsis-v"
            @click="toggle"
            aria-haspopup="true"
            :aria-controls="`nj-address-wm-menu-${address.id}`"
            size="small"
            severity="secondary"
            pt:root="nj-address-wm__options-btn"
        />
        <Menu
            ref="menu"
            :id="`nj-address-wm-menu-${address.id}`"
            :model="items"
            :popup="true"
        />
    </div>
    <address class="not-italic">
        <span>{{ address.line_1 }}</span>
        <br />
        <span v-if="address.line_2">{{ address.line_2 }}</span>
        <br v-if="address.line_2" />
        {{ address.city }},
        {{ address.state }}
        {{ address.zip }}
    </address>
</template>

<style scoped>
.nj-address-wm__options-btn {
    --p-button-sm-padding-x: 0;
    --p-button-sm-padding-y: 0.125rem;
    --p-button-icon-only-width: 1rem;
}
</style>
