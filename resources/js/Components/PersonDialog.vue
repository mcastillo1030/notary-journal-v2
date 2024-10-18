<script setup>
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import InputMask from 'primevue/inputmask';
import Message from 'primevue/message';
import { onMounted, ref, inject } from 'vue';
import { axiosCreate, axiosUpdate } from '@/Services/notaryApi';
import { useModelApiResponses } from '@/Composables/useModelApiResponses';
import { useDialogStore } from '@/Stores/useDialogStore';
import { storeToRefs } from 'pinia';

// injectables
const showSuccessToast = inject('showSuccessToast');
const showErrorToast = inject('showErrorToast');
const showUnknownErrorToast = inject('showUnknownErrorToast');

const dialogStore = useDialogStore();
const { personDialogVisible: visible } = storeToRefs(dialogStore);
const person = ref({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    id: null,
});
const errors = ref({});
const action = ref('create');

const reset = () => {
    // visible.value = false;
    dialogStore.closePersonDialog();
    person.value = {
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        id: null,
    };
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
            model: 'person',
            params: person.value,
            onSuccess: handleAxiosResponse,
            onError: handleAxiosError,
        });
    } else {
        axiosUpdate({
            model: 'person',
            routeParams: {
                person: route().params.person ?? person.value.id,
            },
            params: person.value,
            onSuccess: handleAxiosResponse,
            onError: handleAxiosError,
        });
    }
};

onMounted(() => {
    document.addEventListener('person.create', () => {
        action.value = 'create';
        dialogStore.openPersonDialog();
    });
    document.addEventListener('person.update', (event) => {
        action.value = 'edit';
        person.value = event.detail;
        dialogStore.openPersonDialog();
    });
});
</script>

<template>
    <Dialog
        v-model:visible="visible"
        modal
        :header="`${action === 'edit' ? 'Edit Person Details' : 'Create New Person'}`"
        :style="{ width: '100%', maxWidth: '35rem' }"
    >
        <div class="mb-4 flex flex-col">
            <label for="first_name" class="font-semibold">First Name</label>
            <InputText
                id="first_name"
                class="flex-auto grow-0"
                autocomplete="off"
                v-model="person.first_name"
                :invalid="!!errors.first_name"
            />
            <transition-group
                name="p-message"
                tag="div"
                class="mt-3 flex flex-col"
            >
                <Message
                    v-for="(error, index) in errors.first_name"
                    :key="index"
                    severity="error"
                    >{{ error }}</Message
                >
            </transition-group>
        </div>
        <div class="mb-4 flex flex-col">
            <label for="last_name" class="font-semibold">Last Name</label>
            <InputText
                id="last_name"
                class="flex-auto grow-0"
                autocomplete="off"
                v-model="person.last_name"
                :invalid="!!errors.last_name"
            />
            <transition-group
                name="p-message"
                tag="div"
                class="mt-3 flex flex-col"
            >
                <Message
                    v-for="(error, index) in errors.last_name"
                    :key="index"
                    severity="error"
                    >{{ error }}</Message
                >
            </transition-group>
        </div>
        <div class="mb-4 flex flex-col">
            <label for="email" class="font-semibold">Email</label>
            <InputText
                id="email"
                type="email"
                class="flex-auto grow-0"
                autocomplete="off"
                v-model="person.email"
                :invalid="!!errors.email"
            />
            <transition-group
                name="p-message"
                tag="div"
                class="mt-3 flex flex-col"
            >
                <Message
                    v-for="(error, index) in errors.email"
                    :key="index"
                    severity="error"
                    >{{ error }}</Message
                >
            </transition-group>
        </div>
        <div class="mb-4 flex flex-col">
            <label for="phone" class="font-semibold">Phone</label>
            <InputMask
                id="phone"
                v-model="person.phone"
                mask="(999) 999-9999"
                class="flex-auto grow-0"
                autocomplete="off"
                placeholder="(999) 999-9999"
                fluid
                :invalid="!!errors.phone"
            />
            <transition-group
                name="p-message"
                tag="div"
                class="mt-3 flex flex-col"
            >
                <Message
                    v-for="(error, index) in errors.phone"
                    :key="index"
                    severity="error"
                    >{{ error }}</Message
                >
            </transition-group>
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
