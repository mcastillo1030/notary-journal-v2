<script setup>
import Badge from 'primevue/badge';
import AddressList from '@/Components/AddressList.vue';
import Button from 'primevue/button';
import Menu from 'primevue/menu';
import { ref, inject } from 'vue';
import { useModelApiResponses } from '@/Composables/useModelApiResponses';

const menu = ref();
const personDetail = ref();
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
                        new CustomEvent('person.update', {
                            detail: JSON.parse(
                                personDetail.value.dataset.person,
                            ),
                        }),
                    ),
            },
            {
                label: 'Delete',
                icon: 'pi pi-trash',
                command: () =>
                    confirmDestroy({
                        model: 'person',
                        params: {
                            person: route().params.person,
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

const addAddress = () =>
    document.dispatchEvent(new CustomEvent('address.create'));

defineProps({
    person: Object,
    addresses: Array,
});
</script>

<template>
    <section
        class="nj-person-detail bg-white shadow-md sm:rounded-lg lg:col-span-2 dark:bg-zinc-800"
        :data-person="JSON.stringify(person)"
        ref="personDetail"
    >
        <div class="relative p-6 text-zinc-900 dark:text-zinc-100">
            <div class="absolute right-4 top-4 flex justify-end">
                <Button
                    type="button"
                    icon="pi pi-ellipsis-v"
                    @click="toggle"
                    aria-haspopup="true"
                    aria-controls="nj-person-detail-menu"
                    size="small"
                    severity="secondary"
                    pt:root="nj-person-detail__options-btn"
                />
                <Menu
                    ref="menu"
                    id="nj-person-detail-menu"
                    :model="items"
                    :popup="true"
                />
            </div>
            <div class="bg-surface-0 dark:bg-surface-950">
                <div
                    class="text-surface-900 dark:text-surface-0 mb-4 text-3xl font-medium"
                >
                    Person Record #{{ person.id }}
                </div>
                <div class="text-surface-500 dark:text-surface-300 mb-8">
                    Number of notarial acts associated:
                    <Badge value="0" severity="secondary" />
                </div>
                <ul class="m-0 list-none p-0">
                    <li
                        class="border-surface flex flex-wrap items-center border-t px-2 py-4"
                    >
                        <div
                            class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                        >
                            First Name
                        </div>
                        <div
                            class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                        >
                            {{ person.first_name }}
                        </div>
                    </li>
                    <li
                        class="border-surface flex flex-wrap items-center border-t px-2 py-4"
                    >
                        <div
                            class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                        >
                            Last Name
                        </div>
                        <div
                            class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                        >
                            {{ person.last_name }}
                        </div>
                    </li>
                    <li
                        class="border-surface flex flex-wrap items-center border-t px-2 py-4"
                    >
                        <div
                            class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                        >
                            Email
                        </div>
                        <div
                            class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                        >
                            <span v-if="!person.email">&mdash;</span>
                            <a
                                v-else
                                class="text-indigo-700 dark:text-indigo-300"
                                :href="`mailto:${person.email}`"
                                >{{ person.email }}</a
                            >
                        </div>
                    </li>
                    <li
                        class="border-surface flex flex-wrap items-center border-t px-2 py-4"
                    >
                        <div
                            class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                        >
                            Phone
                        </div>
                        <div
                            class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                        >
                            <span v-if="!person.phone">&mdash;</span>
                            <a
                                v-else
                                class="text-indigo-700 dark:text-indigo-300"
                                :href="`tel:${person.phone}`"
                                >{{ person.phone }}</a
                            >
                        </div>
                    </li>
                    <li
                        class="border-surface flex flex-wrap items-start border-t px-2 py-4"
                    >
                        <div
                            class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                        >
                            Addresses
                        </div>
                        <div
                            class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                        >
                            <AddressList :addresses="addresses" />
                            <Button
                                label="Add Address"
                                icon="pi pi-plus"
                                class="mt-4"
                                size="small"
                                severity="secondary"
                                @click="addAddress"
                            />
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</template>

<style scoped>
.nj-person-detail__options-btn {
    --p-button-sm-padding-x: 0;
    --p-button-sm-padding-y: 0.125rem;
    --p-button-icon-only-width: 1rem;
}
</style>
