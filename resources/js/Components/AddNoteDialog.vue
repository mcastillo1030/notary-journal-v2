<script setup>
import Dialog from 'primevue/dialog';
import Textarea from 'primevue/textarea';
import Button from 'primevue/button';
import Message from 'primevue/message';
import { ref, inject } from 'vue';
import { useDialogStore } from '@/Stores/useDialogStore';
import { axiosCreate } from '@/Services/notaryApi';
import { storeToRefs } from 'pinia';
import { useModelApiResponses } from '@/Composables/useModelApiResponses';

const noteContent = ref('');
const errors = ref({});
const dialogStore = useDialogStore();
const { noteDialogVisible } = storeToRefs(dialogStore);
const showSuccessToast = inject('showSuccessToast');
const showErrorToast = inject('showErrorToast');
const showUnknownErrorToast = inject('showUnknownErrorToast');

const reset = () => {
    noteContent.value = '';
    dialogStore.closeNoteDialog();
    // reset errors
    errors.value = {};
};

const { handleAxiosResponse, handleAxiosError } = useModelApiResponses({
    onPartialReload: (message) => {
        showSuccessToast({ message });
        reset();
    },
    onResponseError: (message) => showErrorToast({ message }),
    onUnknownError: showUnknownErrorToast,
    onErrorResponseHasMessage: (message) => showErrorToast({ message }),
    onErrorResponseHasErrors: (errs) => (errors.value = errs),
});

const handleSaveNote = (e) => {
    const { noteableId, noteableType } = e.target.closest('button').dataset;

    if (!noteableId || !noteableType) {
        return;
    }

    if (!noteContent.value) {
        errors.value = {
            content: ['Note content is required.'],
        };
        return;
    }

    // const
    axiosCreate({
        model: 'note',
        params: {
            content: noteContent.value,
            noteable_id: noteableId,
            noteable_type: noteableType,
        },
        onSuccess: handleAxiosResponse,
        onError: handleAxiosError,
    });
};

defineProps({
    noteableId: Number,
    noteableType: String,
});
</script>

<template>
    <Dialog
        v-model:visible="noteDialogVisible"
        modal
        header="Add Note"
        :style="{ width: '25rem' }"
        :closable="false"
    >
        <span class="text-surface-500 dark:text-surface-400 mb-2 block"
            >Add a note to this record.</span
        >
        <Textarea
            v-model="noteContent"
            class="w-full"
            autoResize
            placeholder="Enter your note here..."
            :invalid="!!errors.content"
        ></Textarea>
        <transition-group
            name="n-message"
            tag="div"
            class="mb-8 mt-3 flex flex-col"
        >
            <Message
                v-for="(error, index) in errors.content ||
                errors.noteable_id ||
                errors.noteable_type"
                :key="index"
                severity="error"
                >{{ error }}</Message
            >
        </transition-group>
        <div class="flex justify-end gap-2">
            <Button
                type="button"
                label="Cancel"
                severity="secondary"
                @click="reset"
            ></Button>
            <Button
                type="button"
                label="Save"
                :data-noteable-id="noteableId"
                :data-noteable-type="noteableType"
                @click="handleSaveNote"
            ></Button>
        </div>
    </Dialog>
</template>
