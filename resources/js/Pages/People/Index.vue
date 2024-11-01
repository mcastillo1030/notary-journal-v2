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

// state
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
const getActions = (person) => [
    {
        label: 'Update',
        command: () =>
            document.dispatchEvent(
                new CustomEvent('person.update', { detail: person }),
            ),
    },
    {
        separator: true,
    },
    {
        label: 'Delete',
        command: () =>
            confirmDestroy({
                model: 'person',
                params: { person: person.id },
                onSuccess: handleAxiosResponse,
                onError: handleAxiosError,
            }),
    },
];
const viewAction = ({ id }) => router.get(`/people/${id}`);
const addAction = () =>
    document.dispatchEvent(new CustomEvent('person.create'));

// lifecycle
defineProps({
    people: Array,
});
</script>

<template>
    <Head title="People" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-zinc-800 dark:text-zinc-200"
            >
                People
            </h2>
        </template>

        <div class="nj-people-index py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-zinc-800"
                >
                    <div class="dar:border p-1 dark:border-zinc-800">
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
                            :pt:pcPaginator="{
                                style: {
                                    'border-bottom': '0',
                                },
                            }"
                        >
                            <template #header>
                                <Toolbar>
                                    <template #start>
                                        <Button
                                            icon="pi pi-external-link"
                                            class="hmr-2"
                                            size="small"
                                            label="Export"
                                            severity="secondary"
                                            @click="dt.exportCSV()"
                                        />
                                    </template>
                                    <template #center>
                                        <IconField class="w-full">
                                            <InputIcon>
                                                <i class="pi pi-search" />
                                            </InputIcon>
                                            <InputText
                                                v-model="
                                                    filters['global'].value
                                                "
                                                fluid
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
                            <template #empty> No people found. </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.nj-people-index .p-datatable-paginator-bottom {
    border-block-end: 0;
}

.nj-people-index .p-toolbar-start {
    display: none;
}

@media screen and (min-width: 640px) {
    .nj-people-index .p-toolbar-start {
        display: flex;
    }

    .nj-people-index .p-toolbar-center {
        flex-basis: 60%;
    }
}
</style>
