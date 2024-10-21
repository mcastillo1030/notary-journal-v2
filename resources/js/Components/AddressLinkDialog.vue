<script setup>
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Message from 'primevue/message';
import { onMounted, ref, inject } from 'vue';
import { axiosUpdate } from '@/Services/notaryApi';
import { useModelApiResponses } from '@/Composables/useModelApiResponses';
import { useDialogStore } from '@/Stores/useDialogStore';
import { storeToRefs } from 'pinia';

// injectables
const showSuccessToast = inject('showSuccessToast');
const showErrorToast = inject('showErrorToast');
const showUnknownErrorToast = inject('showUnknownErrorToast');

const dialogStore = useDialogStore();
const { addressLinkDialogVisible: visible } = storeToRefs(dialogStore);
const addresses = ref([]);

const reset = () => {
    dialogStore.closeAddressLinkDialog();
    addresses.value = [];
};
const { handleAxiosResponse, handleAxiosError } = useModelApiResponses({
    onPartialReload: (message) => {
        showSuccessToast({ message });
        reset();
    },
    onResponseError: (message) => showErrorToast({ message }),
    onErrorResponseHasMessage: (message) => showErrorToast({ message }),
    onUnknownError: showUnknownErrorToast,
});

const save = () => {
    console.log('save');
    return;
    // axiosUpdate({
    //     model: 'address',
    //     routeParams: {
    //         address: address.value.id,
    //     },
    //     params: {
    //         ...address.value,
    //     },
    //     onSuccess: handleAxiosResponse,
    //     onError: handleAxiosError,
    // });
};

onMounted(() => {
    document.addEventListener('address.link', (event) => {
        const { personId } = event.detail;
        console.log(personId);
        // addresses.value = event.detail; fetch from api
        dialogStore.openAddressLinkDialog();
    });
});
</script>

<template>
    <Dialog
        v-model:visible="visible"
        modal
        header="Link an Address"
        :style="{ width: '100%', maxWidth: '45rem', maxHeight: '80vh' }"
    >
        <div class="flex flex-col gap-4">
            <label for="line_1" class="font-semibold">Address 1</label>
            <InputText
                id="line_1"
                class="flex-auto grow-0"
                autocomplete="off"
                v-model="address.line_1"
                :invalid="!!errors.line_1"
            />
            <transition-group
                name="line-1-message"
                tag="div"
                class="flex flex-col"
            >
                <Message
                    v-for="(error, index) in errors.line_1"
                    :key="index"
                    severity="error"
                    :pt:root:class="{ 'mt-2': index > 0 }"
                    >{{ error }}</Message
                >
            </transition-group>
        </div>
        <div class="mt-1 flex flex-col gap-4">
            <label for="line_2" class="font-semibold">Address 2</label>
            <InputText
                id="line_2"
                class="flex-auto grow-0"
                autocomplete="off"
                v-model="address.line_2"
                :invalid="!!errors.line_2"
            />
            <transition-group
                name="line-2-message"
                tag="div"
                class="flex flex-col"
            >
                <Message
                    v-for="(error, index) in errors.line_2"
                    :key="index"
                    severity="error"
                    :pt:root:class="{ 'mt-2': index > 0 }"
                    >{{ error }}</Message
                >
            </transition-group>
        </div>
        <div class="mt-1 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div
                class="mb-2 flex flex-col gap-4 sm:col-span-6 sm:col-start-1 lg:col-span-2"
            >
                <label for="city" class="font-semibold">City</label>
                <InputText
                    id="city"
                    class="flex-auto grow-0"
                    autocomplete="off"
                    v-model="address.city"
                    :invalid="!!errors.city"
                />
                <transition-group
                    name="city-message"
                    tag="div"
                    class="flex flex-col"
                >
                    <Message
                        v-for="(error, index) in errors.city"
                        :key="index"
                        severity="error"
                        :pt:root:class="{ 'mt-2': index > 0 }"
                        >{{ error }}</Message
                    >
                </transition-group>
            </div>

            <div class="mb-2 flex flex-col gap-4 sm:col-span-3 lg:col-span-2">
                <label for="state" class="font-semibold">State</label>
                <InputText
                    id="state"
                    class="flex-auto grow-0"
                    autocomplete="off"
                    v-model="address.state"
                    :invalid="!!errors.state"
                />
                <transition-group
                    name="state-message"
                    tag="div"
                    class="flex flex-col"
                >
                    <Message
                        v-for="(error, index) in errors.state"
                        :key="index"
                        severity="error"
                        :pt:root:class="{ 'mt-2': index > 0 }"
                        >{{ error }}</Message
                    >
                </transition-group>
            </div>

            <div class="mb-2 flex flex-col gap-4 sm:col-span-3 lg:col-span-2">
                <label for="zip" class="font-semibold">Zip</label>
                <InputText
                    id="zip"
                    class="flex-auto grow-0"
                    autocomplete="off"
                    v-model="address.zip"
                    :invalid="!!errors.zip"
                />
                <transition-group
                    name="zip-message"
                    tag="div"
                    class="flex flex-col"
                >
                    <Message
                        v-for="(error, index) in errors.zip"
                        :key="index"
                        :pt:root:class="{ 'mt-2': index > 0 }"
                        severity="error"
                        >{{ error }}</Message
                    >
                </transition-group>
            </div>
        </div>
        <div class="mt-3 flex justify-end gap-2">
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
