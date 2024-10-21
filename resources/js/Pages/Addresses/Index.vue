<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import SplitButton from 'primevue/splitbutton';
import IconField from 'primevue/iconfield';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/inputicon';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import { FilterMatchMode } from '@primevue/core/api';
import { ref, inject } from 'vue';
import { useModelApiResponses } from '@/Composables/useModelApiResponses';

// injectables
const confirmDestroy = inject('confirmDestroy');
const showSuccessToast = inject('showSuccessToast');
const showErrorToast = inject('showErrorToast');
const showUnknownErrorToast = inject('showUnknownErrorToast');

const dt = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const { handleAxiosResponse, handleAxiosError } = useModelApiResponses({
    onIndexReload: (message) => showSuccessToast({ message }),
    onResponseError: (message) => showErrorToast({ message }),
    onUnknownError: showUnknownErrorToast,
});

// methods
const getActions = (address) => [
    {
        label: 'Update',
        command: () =>
            document.dispatchEvent(
                new CustomEvent('address.update', { detail: address }),
            ),
    },
    {
        separator: true,
    },
    {
        label: 'Delete',
        command: () =>
            confirmDestroy({
                model: 'address',
                params: { address: address.id },
                onSuccess: handleAxiosResponse,
                onError: handleAxiosError,
            }),
    },
];
const viewAction = ({ id }) => router.get(`/addresses/${id}`);
const addAction = () =>
    document.dispatchEvent(new CustomEvent('address.create'));

// lifecycle
defineProps({
    addresses: Array,
});
</script>

<template>
    <Head title="Addresses" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-zinc-800 dark:text-zinc-200"
            >
                People
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-zinc-800"
                >
                    <div class="dar:border p-1 dark:border-zinc-800">
                        <DataTable
                            ref="dt"
                            :value="addresses"
                            stripedRows
                            dataKey="id"
                            stateStorage="session"
                            stateKey="nj-state-addresses-index"
                            tableStyle="min-width: 50rem"
                            :paginator="true"
                            :rows="10"
                            :filters="filters"
                            :globalFilterFields="[
                                'line_1',
                                'line_2',
                                'city',
                                'state',
                                'zip',
                            ]"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
                        >
                            <template #header>
                                <Toolbar>
                                    <template #start>
                                        <Button
                                            icon="pi pi-external-link"
                                            class="mr-2"
                                            size="small"
                                            label="Export"
                                            severity="secondary"
                                            @click="dt.exportCSV()"
                                        />
                                    </template>
                                    <template #center>
                                        <IconField>
                                            <InputIcon>
                                                <i class="pi pi-search" />
                                            </InputIcon>
                                            <InputText
                                                v-model="
                                                    filters['global'].value
                                                "
                                                placeholder="Search..."
                                            />
                                        </IconField>
                                    </template>
                                    <template #end>
                                        <Button
                                            icon="pi pi-plus"
                                            class="mr-2"
                                            size="small"
                                            label="Add"
                                            @click="addAction"
                                        />
                                    </template>
                                </Toolbar>
                            </template>
                            <Column field="id">
                                <template #body="{ data }">
                                    <SplitButton
                                        label="View"
                                        size="small"
                                        @click="viewAction(data)"
                                        :model="getActions(data)"
                                    />
                                </template>
                            </Column>
                            <Column header="Street Address">
                                <template #body="{ data }">
                                    <div class="flex flex-col">
                                        <span>{{ data.line_1 }}</span>
                                        <span v-if="data.line_2">{{
                                            data.line_2
                                        }}</span>
                                    </div>
                                </template>
                            </Column>
                            <Column field="city" header="City"></Column>
                            <Column field="state" header="State"></Column>
                            <Column field="zip" header="Zip"></Column>
                            <template #empty> No addresses found. </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
