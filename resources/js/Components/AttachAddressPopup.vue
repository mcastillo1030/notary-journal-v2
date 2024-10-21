<script setup>
import Popover from 'primevue/popover';
import Button from 'primevue/button';
import MultiSelect from 'primevue/multiselect';
import { ref, inject } from 'vue';
import { axiosAttach, axiosList } from '@/Services/notaryApi';
import { router } from '@inertiajs/vue3';

const op = ref();
const addresses = ref([]);
const loading = ref(false);
const selectAll = ref(false);
const selectedAddresses = ref([]);
const showErrorToast = inject('showErrorToast');
const showSuccessToast = inject('showSuccessToast');

const addAddress = () => {
    document.dispatchEvent(new CustomEvent('address.create'));
    op.value.hide();
};

const onSelectAllChange = (event) => {
    selectedAddresses.value = event.checked ? addresses.value : [];
    selectAll.value = event.checked;
};

const togglePopover = (event) => {
    op.value.toggle(event);

    if (op.value.visible) {
        loading.value = true;
        axiosList({
            model: 'address',
            params: {
                not_assigned_to: route().params.person,
            },
            onSuccess: (response) => {
                addresses.value = response.data.addresses || [];
            },
            onError: (message) => showErrorToast({ message }),
            onFinally: () => (loading.value = false),
        });
    }
};

const clearSelected = () => {
    selectedAddresses.value = [];
    selectAll.value = false;
};

const attachAddresses = () => {
    if (!selectedAddresses.value.length) {
        showErrorToast({ message: 'Please select at least one address.' });
        return;
    }

    axiosAttach({
        model: 'person',
        routeParams: {
            person: route().params.person,
        },
        params: {
            attachable_type: 'address',
            attachable_ids: selectedAddresses.value.map(
                (address) => address.id,
            ),
        },
        onSuccess: (response) => {
            router.reload({
                only: ['addresses'],
                onFinish: () => {
                    showSuccessToast({ message: response.data.message });
                    op.value.hide();
                    selectedAddresses.value = [];
                },
            });
        },
        onError: (message) => showErrorToast({ message }),
    });
};
</script>

<template>
    <Button
        label="Add Address"
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
                v-model="selectedAddresses"
                :options="addresses"
                optionLabel="name"
                filter
                :filterFields="['line_1', 'line_2', 'city', 'state', 'zip']"
                :selectAll="selectAll"
                @selectall-change="onSelectAllChange($event)"
                placeholder="Select Addresses"
                :maxSelectedLabels="0"
                class="w-full md:w-80"
                :loading="loading"
            >
                <template #option="slotProps">
                    <div class="flex flex-col">
                        <span>{{ slotProps.option.line_1 }}</span>
                        <span v-if="slotProps.option.line_2">{{
                            slotProps.option.line_2
                        }}</span>
                        <span
                            >{{ slotProps.option.city }},
                            {{ slotProps.option.state }}
                            {{ slotProps.option.zip }}</span
                        >
                    </div>
                </template>
                <template #dropdownicon>
                    <i class="pi pi-map-marker" />
                </template>
                <template #filtericon>
                    <i class="pi pi-search" />
                </template>
                <template #header>
                    <div class="px-3 py-2 font-medium">Available Addresses</div>
                </template>
                <template #footer>
                    <div class="flex justify-between p-3">
                        <Button
                            label="Add New"
                            severity="secondary"
                            text
                            size="small"
                            icon="pi pi-plus"
                            @click="addAddress"
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
                label="Link Selected"
                icon="pi pi-check"
                class="mt-2"
                size="small"
                @click="attachAddresses"
                :disabled="!selectedAddresses.length"
            />
        </div>
    </Popover>
</template>
