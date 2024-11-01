<script setup>
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Message from 'primevue/message';
import Select from 'primevue/select';
import AutoComplete from 'primevue/autocomplete';
import { onMounted, ref, inject, computed } from 'vue';
import { axiosCreate, axiosUpdate } from '@/Services/notaryApi';
import axios from 'axios';
import { useModelApiResponses } from '@/Composables/useModelApiResponses';
import { useDialogStore } from '@/Stores/useDialogStore';
import { storeToRefs } from 'pinia';
import { usePage } from '@inertiajs/vue3';

// injectables
const showSuccessToast = inject('showSuccessToast');
const showErrorToast = inject('showErrorToast');
const showUnknownErrorToast = inject('showUnknownErrorToast');

const page = usePage();
const constants = computed(() => page.props.constants);
const dialogStore = useDialogStore();
const { idDialogVisible: visible } = storeToRefs(dialogStore);
const identification = ref({
    id: null,
    type: null,
    document_name: null,
    document_number: null,
    issue_date: null,
    expiration_date: null,
});
const errors = ref({});
const action = ref('create');
const selectedType = ref(null);
const selectedName = ref(null);
const nameSuggestions = ref([]);
const options = computed(() =>
    Object.entries(constants.value.identification.types).map(([key, value]) => {
        return {
            label: value,
            value: key,
        };
    }),
);
const selectedIsDocument = computed(
    () => selectedType.value && selectedType.value.includes('document'),
);

const reset = () => {
    // visible.value = false;
    dialogStore.closeIdDialog();
    identification.value = {
        id: null,
        type: null,
        document_name: null,
        document_number: null,
        issue_date: null,
        expiration_date: null,
    };
    selectedType.value = null;
    selectedName.value = null;
    errors.value = {};
    action.value = 'create';
};

const showToastAndReset = (message) => {
    showSuccessToast({ message });
    reset();
};

// api responses
const { handleAxiosResponse, handleAxiosError } = useModelApiResponses({
    onPartialReload: showToastAndReset,
    onIndexReload: showToastAndReset,
    onResponseHasErrors: (errs) => (errors.value = errs),
    onResponseError: (message) => showErrorToast({ message }),
    onErrorResponseHasErrors: (errs) => (errors.value = errs),
    onErrorResponseHasMessage: (message) => showErrorToast({ message }),
    onUnknownError: showUnknownErrorToast,
});

const save = () => {
    if (action.value === 'create') {
        axiosCreate({
            model: 'identification',
            params: {
                ...identification.value,
                type: selectedType.value,
                document_name: selectedName.value,
                person_id: route().params.person,
            },
            onSuccess: handleAxiosResponse,
            onError: handleAxiosError,
        });
        // console.log('create an id for person');
    } else {
        console.log('update an id for person');
        axiosUpdate({
            model: 'identification',
            routeParams: {
                identification: identification.value.id,
            },
            params: {
                ...identification.value,
                type: selectedType.value,
                document_name: selectedName.value,
            },
            onSuccess: handleAxiosResponse,
            onError: handleAxiosError,
        });
        // console.log('update an id for person');
    }
};

const onSearch = (evt) => {
    axios
        .get(
            route('identifications.list-names') +
                (evt.query ? `?q=${evt.query}` : ''),
        )
        .then((response) => (nameSuggestions.value = response.data))
        .catch((error) => console.error(error));
};

onMounted(() => {
    document.addEventListener('identification.create', () => {
        action.value = 'create';
        dialogStore.openIdDialog();
    });
    document.addEventListener('identification.update', (event) => {
        action.value = 'edit';
        identification.value = event.detail;
        selectedType.value = identification.value.type;
        selectedName.value = identification.value.document_name;
        dialogStore.openIdDialog();
    });
});
</script>

<template>
    <Dialog
        v-model:visible="visible"
        modal
        :header="`${action === 'edit' ? 'Edit Identification Details' : 'Create New Identification'}`"
        :style="{ width: '100%', maxWidth: '35rem' }"
    >
        <div class="mb-4 flex flex-col">
            <label for="type" class="font-semibold">Identification Type</label>
            <Select
                v-model="selectedType"
                :options="options"
                showClear
                option-label="label"
                option-value="value"
                placeholder="Select a type"
            />
            <transition-group
                name="type-message"
                tag="div"
                class="mt-3 flex flex-col"
            >
                <Message
                    v-for="(error, index) in errors.type"
                    :key="index"
                    severity="error"
                    >{{ error }}</Message
                >
            </transition-group>
        </div>
        <div v-if="selectedIsDocument" class="mb-4 grid-cols-2 gap-3 sm:grid">
            <div class="flex flex-col">
                <label for="document_name" class="font-semibold"
                    >Document</label
                >
                <AutoComplete
                    v-model="selectedName"
                    :suggestions="nameSuggestions"
                    :complete-on-focus="true"
                    @complete="onSearch"
                    field="document_name"
                    placeholder="Document name"
                    fluid
                />
                <transition-group
                    name="name-message"
                    tag="div"
                    class="mt-3 flex flex-col"
                >
                    <Message
                        v-for="(error, index) in errors.document_name"
                        :key="index"
                        severity="error"
                        >{{ error }}</Message
                    >
                </transition-group>
            </div>
            <div class="flex flex-col">
                <label for="document_number" class="font-semibold"
                    >Number</label
                >
                <InputText
                    id="document_number"
                    class="flex-auto grow-0"
                    placeholder="Document number"
                    autocomplete="off"
                    v-model="identification.document_number"
                    :invalid="!!errors.document_number"
                />
                <transition-group
                    name="number-message"
                    tag="div"
                    class="mt-3 flex flex-col"
                >
                    <Message
                        v-for="(error, index) in errors.document_number"
                        :key="index"
                        severity="error"
                        >{{ error }}</Message
                    >
                </transition-group>
            </div>
        </div>
        <div v-if="selectedIsDocument" class="mb-4 grid-cols-2 gap-3 sm:grid">
            <div class="flex flex-col">
                <label for="issue_date" class="font-semibold">Issued</label>
                <InputText
                    id="issue_date"
                    class="flex-auto grow-0"
                    autocomplete="off"
                    placeholder="Issue date"
                    v-model="identification.issue_date"
                    :invalid="!!errors.issue_date"
                />
                <transition-group
                    name="number-message"
                    tag="div"
                    class="mt-3 flex flex-col"
                >
                    <Message
                        v-for="(error, index) in errors.issue_date"
                        :key="index"
                        severity="error"
                        >{{ error }}</Message
                    >
                </transition-group>
            </div>
            <div class="flex flex-col">
                <label for="issue_date" class="font-semibold">Expires</label>
                <InputText
                    id="issue_date"
                    class="flex-auto grow-0"
                    autocomplete="off"
                    placeholder="Expiration date"
                    v-model="identification.expiration_date"
                    :invalid="!!errors.expiration_date"
                />
                <transition-group
                    name="number-message"
                    tag="div"
                    class="mt-3 flex flex-col"
                >
                    <Message
                        v-for="(error, index) in errors.expiration_date"
                        :key="index"
                        severity="error"
                        >{{ error }}</Message
                    >
                </transition-group>
            </div>
        </div>
        <div class="flex justify-end gap-2">
            <Button
                type="button"
                label="Cancel"
                severity="secondary"
                @click="reset"
            ></Button>
            <Button type="button" label="Save" @click="save"></Button>
        </div>
    </Dialog>
</template>
