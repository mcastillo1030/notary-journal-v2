<script setup>
import Button from 'primevue/button';
import Menu from 'primevue/menu';
import { ref, inject } from 'vue';
import { useModelApiResponses } from '@/Composables/useModelApiResponses';

const menu = ref();
const identificationItem = ref();
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
                        new CustomEvent('identification.update', {
                            detail: JSON.parse(
                                identificationItem.value.dataset.edit,
                            ),
                        }),
                    ),
            },
            {
                label: 'Delete',
                icon: 'pi pi-trash',
                command: () =>
                    confirmDestroy({
                        model: 'identification',
                        params: {
                            identification: identificationItem.value.dataset.id,
                        },
                        onSuccess: handleAxiosResponse,
                        onError: handleAxiosError,
                    }),
            },
        ],
    },
]);

const getEditableIdAttributes = (identification) => {
    return {
        id: identification.id,
        type: identification.type,
        document_name: identification.document_name,
        document_number: identification.document_number,
        issue_date: identification.issue_date,
        expiration_date: identification.expiration_date,
    };
};

const toggle = (event) => {
    menu.value.toggle(event);
};

defineProps({
    identification: Object,
});
</script>

<template>
    <div
        ref="identificationItem"
        class="nj-id-wm absolute right-4 top-4 flex justify-end"
        :data-id="identification.id"
        :data-edit="JSON.stringify(getEditableIdAttributes(identification))"
    >
        <Button
            type="button"
            icon="pi pi-ellipsis-v"
            @click="toggle"
            aria-haspopup="true"
            :aria-controls="`nj-id-wm-menu-${identification.id}`"
            size="small"
            severity="secondary"
            pt:root="nj-id-wm__options-btn"
        />
        <Menu
            ref="menu"
            :id="`nj-id-wm-menu-${identification.id}`"
            :model="items"
            :popup="true"
        />
    </div>
    <span class="flex items-center gap-x-3">
        <span
            v-if="
                identification.type_label.includes('Document') ||
                identification.type_label.includes('ID')
            "
            class="pi pi-id-card"
        ></span>
        <span v-else class="pi pi-user"></span>
        <span class="font-black">{{ identification.type_label }}</span>
    </span>
    <div
        v-if="
            identification.type_label.includes('Document') ||
            identification.type_label.includes('ID')
        "
        class="mt-2 grid w-full grid-cols-none items-center gap-x-4 gap-y-1 border-t ps-7 pt-2 md:grid-cols-2 md:border-t-0 md:pt-0 xl:grid-cols-4 dark:border-zinc-500"
    >
        <div
            v-if="identification.document_name"
            class="flex items-center overflow-hidden font-medium"
        >
            <span class="pi pi-info-circle me-1 align-baseline"></span>
            <p :title="identification.document_name" class="truncate">
                {{ identification.document_name }}
            </p>
        </div>
        <span v-if="identification.document_number" class="text-sm font-light">
            <span class="pi pi-hashtag me-1 align-baseline"></span>
            {{ identification.document_number }}
        </span>
        <span class="text-sm font-thin" v-if="identification.issue_date">
            <span class="pi pi-verified me-1 align-baseline"></span>
            Iss: {{ identification.issue_date }}
        </span>
        <span class="text-sm font-thin" v-if="identification.expiration_date">
            <span class="pi pi-clock me-1 align-baseline"></span>
            Exp: {{ identification.expiration_date }}
        </span>
    </div>
</template>

<style scoped>
.nj-id-wm__options-btn {
    --p-button-sm-padding-x: 0;
    --p-button-sm-padding-y: 0.125rem;
    --p-button-icon-only-width: 1rem;
}
</style>
