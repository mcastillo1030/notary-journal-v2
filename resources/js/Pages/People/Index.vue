<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import SplitButton from 'primevue/splitbutton';
import IconField from 'primevue/iconfield';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/inputicon';
import { FilterMatchMode } from '@primevue/core/api';
import { ref } from 'vue';

const dt = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const getActions = ({ id }) => [
    {
        label: 'Update',
        command: () => router.get(`/people/${id}/edit`),
    },
    {
        separator: true,
    },
    {
        label: 'Delete',
        command: () => console.log('Delete', id),
    },
];

const viewAction = ({ id }) => router.get(`/people/${id}`);

defineProps({
    people: Array,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                People
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <DataTable
                            ref="dt"
                            :value="people"
                            stripedRows
                            dataKey="id"
                            stateStorage="session"
                            stateKey="nj-state-people-index"
                            tableStyle="min-width: 50rem"
                            :paginator="true"
                            :rows="10"
                            :filters="filters"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
                        >
                            <template #header>
                                <IconField>
                                    <InputIcon>
                                        <i class="pi pi-search" />
                                    </InputIcon>
                                    <InputText
                                        v-model="filters['global'].value"
                                        placeholder="Search..."
                                    />
                                </IconField>
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
                            <Column
                                field="first_name"
                                header="First Name"
                            ></Column>
                            <Column
                                field="last_name"
                                header="Last Name"
                            ></Column>
                            <Column header="Contact">
                                <template #body="{ data }">
                                    <div class="flex flex-col">
                                        <span v-if="data.email">{{
                                            data.email
                                        }}</span>
                                        <span v-if="data.phone">{{
                                            data.phone
                                        }}</span>
                                        <span v-if="!data.email && !data.phone"
                                            >-</span
                                        >
                                    </div>
                                </template>
                            </Column>
                            <template #empty> No customers found. </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
