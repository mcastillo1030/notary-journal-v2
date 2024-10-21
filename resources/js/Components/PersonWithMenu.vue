<script setup>
import Button from 'primevue/button';
import Menu from 'primevue/menu';
import { ref, inject } from 'vue';
import { router } from '@inertiajs/vue3';
import { useModelApiResponses } from '@/Composables/useModelApiResponses';

const menu = ref();
const personItem = ref();
const confirmUnlink = inject('confirmUnlink');
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
                        new CustomEvent('person.update', {
                            detail: JSON.parse(personItem.value.dataset.edit),
                        }),
                    ),
            },
            {
                label: 'Go to',
                icon: 'pi pi-eye',
                command: () =>
                    router.visit(`/people/${personItem.value.dataset.id}`),
            },
            {
                label: 'Unlink',
                icon: 'pi pi-ban',
                command: () =>
                    confirmUnlink({
                        model: 'address',
                        routeParams: {
                            address: route().params.address,
                        },
                        params: {
                            attachable_type: 'person',
                            attachable_ids: [personItem.value.dataset.id],
                        },
                        onSuccess: handleAxiosResponse,
                        onError: handleAxiosError,
                    }),
            },
        ],
    },
]);

const getEditablePersonAttributes = (person) => {
    return {
        id: person.id,
        first_name: person.first_name,
        last_name: person.last_name,
        email: person.email,
        phone: person.phone,
    };
};

const toggle = (event) => {
    menu.value.toggle(event);
};

defineProps({
    person: Object,
});
</script>

<template>
    <div
        ref="personItem"
        class="nj-person-wm absolute right-4 top-1/2 flex -translate-y-1/2 justify-end"
        :data-id="person.id"
        :data-edit="JSON.stringify(getEditablePersonAttributes(person))"
    >
        <Button
            type="button"
            icon="pi pi-ellipsis-v"
            @click="toggle"
            aria-haspopup="true"
            :aria-controls="`nj-person-wm-menu-${person.id}`"
            size="small"
            severity="secondary"
            pt:root="nj-person-wm__options-btn"
        />
        <Menu
            ref="menu"
            :id="`nj-person-wm-menu-${person.id}`"
            :model="items"
            :popup="true"
        />
    </div>
    <div>
        <p>{{ person.first_name }} {{ person.last_name }}</p>
        <p v-if="person.email">{{ person.email }}</p>
        <p v-if="person.phone">{{ person.phone }}</p>
    </div>
</template>

<style scoped>
.nj-person-wm__options-btn {
    --p-button-sm-padding-x: 0;
    --p-button-sm-padding-y: 0.125rem;
    --p-button-icon-only-width: 1rem;
}
</style>
