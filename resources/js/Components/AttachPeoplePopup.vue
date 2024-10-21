<script setup>
import Popover from 'primevue/popover';
import Button from 'primevue/button';
import MultiSelect from 'primevue/multiselect';
import { ref, inject } from 'vue';
import { axiosAttach, axiosList } from '@/Services/notaryApi';
import { router } from '@inertiajs/vue3';

const op = ref();
const people = ref([]);
const loading = ref(false);
const selectAll = ref(false);
const selectedPeople = ref([]);
const showErrorToast = inject('showErrorToast');
const showSuccessToast = inject('showSuccessToast');

const addPerson = () => {
    document.dispatchEvent(new CustomEvent('people.create'));
    op.value.hide();
};

const onSelectAllChange = (event) => {
    selectedPeople.value = event.checked ? people.value : [];
    selectAll.value = event.checked;
};

const togglePopover = (event) => {
    op.value.toggle(event);

    if (op.value.visible) {
        loading.value = true;
        axiosList({
            model: 'person',
            params: {
                not_assigned_to: route().params.address,
            },
            onSuccess: (response) => {
                people.value = response.data.people || [];
            },
            onError: (message) => showErrorToast({ message }),
            onFinally: () => (loading.value = false),
        });
    }
};

const clearSelected = () => {
    selectedPeople.value = [];
    selectAll.value = false;
};

const attachPeople = () => {
    if (!selectedPeople.value.length) {
        showErrorToast({ message: 'Please select at least one person.' });
        return;
    }

    axiosAttach({
        model: 'address',
        routeParams: {
            address: route().params.address,
        },
        params: {
            attachable_type: 'person',
            attachable_ids: selectedPeople.value.map((person) => person.id),
        },
        onSuccess: (response) => {
            router.reload({
                only: ['people'],
                onFinish: () => {
                    showSuccessToast({ message: response.data.message });
                    op.value.hide();
                    selectedPeople.value = [];
                },
            });
        },
        onError: (message) => showErrorToast({ message }),
    });
};
</script>

<template>
    <Button
        label="Add People"
        icon="pi pi-plus"
        class="mt-4"
        size="small"
        severity="secondary"
        outlined
        @click="togglePopover"
    />
    <Popover ref="op">
        <div class="flex flex-col">
            <span class="mb-4 block text-sm">Choose from list below:</span>
            <MultiSelect
                v-model="selectedPeople"
                :options="people"
                optionLabel="name"
                filter
                :filterFields="['first_name', 'last_name', 'email']"
                :selectAll="selectAll"
                @selectall-change="onSelectAllChange($event)"
                placeholder="Select People"
                :maxSelectedLabels="0"
                class="w-full md:w-80"
                :loading="loading"
            >
                <template #option="slotProps">
                    <div class="flex flex-col">
                        <span
                            >{{ slotProps.option.first_name }}
                            {{ slotProps.option.last_name }}</span
                        >
                        <span v-if="slotProps.option.email">{{
                            slotProps.option.email
                        }}</span>
                        <span v-if="slotProps.option.phone">{{
                            slotProps.option.phone
                        }}</span>
                    </div>
                </template>
                <template #dropdownicon>
                    <i class="pi pi-user" />
                </template>
                <template #filtericon>
                    <i class="pi pi-search" />
                </template>
                <template #header>
                    <div class="px-3 py-2 font-medium">Available People</div>
                </template>
                <template #footer>
                    <div class="flex justify-between p-3">
                        <Button
                            label="Add New"
                            severity="secondary"
                            text
                            size="small"
                            icon="pi pi-plus"
                            @click="addPerson"
                        />
                        <Button
                            label="Remove All"
                            severity="danger"
                            text
                            size="small"
                            icon="pi pi-times"
                            @click="clearSelected"
                        />
                    </div>
                </template>
            </MultiSelect>
            <Button
                label="Attach Selected"
                icon="pi pi-check"
                class="mt-2"
                size="small"
                @click="attachPeople"
            />
        </div>
    </Popover>
</template>
