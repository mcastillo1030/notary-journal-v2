<script setup>
import Button from 'primevue/button';
import Menu from 'primevue/menu';
import { ref, inject } from 'vue';
import { useModelApiResponses } from '@/Composables/useModelApiResponses';

const menu = ref();
const addressDetail = ref();
const confirmDestroy = inject('confirmDestroy');
const showSuccessToast = inject('showSuccessToast');
const showErrorToast = inject('showErrorToast');
const showUnknownErrorToast = inject('showUnknownErrorToast');
const { handleAxiosResponse, handleAxiosError } = useModelApiResponses({
    onSuccessRedirect: (message) => showSuccessToast(message),
    onResponseError: (message) => showErrorToast(message),
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
                            detail: JSON.parse(
                                addressDetail.value.dataset.address,
                            ),
                        }),
                    ),
            },
            {
                label: 'Delete',
                icon: 'pi pi-trash',
                command: () =>
                    confirmDestroy({
                        model: 'address',
                        params: {
                            person: route().params.address,
                        },
                        onSuccess: handleAxiosResponse,
                        onError: handleAxiosError,
                    }),
            },
        ],
    },
]);

const toggle = (event) => {
    menu.value.toggle(event);
};

defineProps({
    address: Object,
});
</script>

<template>
    <section
        class="nj-address-detail bg-white shadow-md sm:rounded-lg dark:bg-zinc-800"
        :data-address="JSON.stringify(address)"
        ref="addressDetail"
    >
        <div class="relative p-6 text-zinc-900 dark:text-zinc-100">
            <div class="absolute right-4 top-4 flex justify-end">
                <Button
                    type="button"
                    icon="pi pi-ellipsis-v"
                    @click="toggle"
                    aria-haspopup="true"
                    aria-controls="nj-address-detail-menu"
                    size="small"
                    severity="secondary"
                    pt:root="nj-address-detail__options-btn"
                />
                <Menu
                    ref="menu"
                    id="nj-address-detail-menu"
                    :model="items"
                    :popup="true"
                />
            </div>
            <div class="bg-surface-0 dark:bg-surface-950">
                <h3
                    class="text-surface-900 dark:text-surface-0 mb-6 text-2xl font-medium"
                >
                    Details
                </h3>
                <ul class="m-0 list-none p-0">
                    <li
                        class="border-surface flex flex-wrap items-center border-t px-2 py-4"
                    >
                        <div
                            class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                        >
                            Address Line 1
                        </div>
                        <div
                            class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                        >
                            {{ address.line_1 }}
                        </div>
                    </li>
                    <li
                        class="border-surface flex flex-wrap items-center border-t px-2 py-4"
                    >
                        <div
                            class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                        >
                            Address Line 2
                        </div>
                        <div
                            class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                        >
                            <span v-if="!address.line_2">&mdash;</span>
                            <span v-else>{{ address.line_2 }}</span>
                        </div>
                    </li>
                    <li
                        class="border-surface flex flex-wrap items-center border-t px-2 py-4"
                    >
                        <div
                            class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                        >
                            City
                        </div>
                        <div
                            class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                        >
                            {{ address.city }}
                        </div>
                    </li>
                    <li
                        class="border-surface flex flex-wrap items-center border-t px-2 py-4"
                    >
                        <div
                            class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                        >
                            State
                        </div>
                        <div
                            class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                        >
                            {{ address.state }}
                        </div>
                    </li>
                    <li
                        class="border-surface flex flex-wrap items-center border-t px-2 py-4"
                    >
                        <div
                            class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                        >
                            Zip
                        </div>
                        <div
                            class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                        >
                            {{ address.zip }}
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</template>

<style scoped>
.nj-address-detail__options-btn {
    --p-button-sm-padding-x: 0;
    --p-button-sm-padding-y: 0.125rem;
    --p-button-icon-only-width: 1rem;
}
</style>
