<script setup>
import Card from 'primevue/card';
import Button from 'primevue/button';
import Menu from 'primevue/menu';
import Textarea from 'primevue/textarea';
import { ref, inject } from 'vue';
import { useModelApiResponses } from '@/Composables/useModelApiResponses';
import { axiosUpdate } from '@/Services/notaryApi';

const isEditing = ref(false);
const noteContent = ref('');
const menu = ref();
const confirmDestroy = inject('confirmDestroy');
const showSuccessToast = inject('showSuccessToast');
const showErrorToast = inject('showErrorToast');
const showUnknownErrorToast = inject('showUnknownErrorToast');
const { handleAxiosResponse, handleAxiosError } = useModelApiResponses({
    onPartialReload: (message) => showSuccessToast({ message }),
    onResponseError: (message) => showErrorToast({ message }),
    onUnknownError: showUnknownErrorToast,
});

const itemActions = (note, noteableId, noteableType) => {
    if (!note || !noteableId || !noteableType) {
        return [];
    }

    return [
        {
            label: 'Edit',
            icon: 'pi pi-pencil',
            command: () => {
                noteContent.value = note.content;
                isEditing.value = true;
            },
        },
        {
            label: 'Delete',
            icon: 'pi pi-trash',
            command: () =>
                destroy({
                    id: note.id,
                    noteable: {
                        id: noteableId,
                        type: noteableType,
                    },
                }),
        },
    ];
};

const toggle = (event) => {
    menu.value.toggle(event);
};

const cancel = () => {
    isEditing.value = false;
    noteContent.value = '';
};

const update = ({ id, content, noteable }) => {
    if (!id || !content || !noteable.type || !noteable.id) {
        return;
    }

    axiosUpdate({
        model: 'note',
        routeParams: {
            [noteable.type]: noteable.id,
            note: id,
        },
        params: {
            content,
        },
        onSuccess: handleAxiosResponse,
        onError: handleAxiosError,
    });

    isEditing.value = false;
    noteContent.value = '';
};

const destroy = ({ id, noteable }) => {
    if (!id || !noteable.type || !noteable.id) {
        return;
    }

    confirmDestroy({
        model: 'note',
        params: {
            [noteable.type]: noteable.id,
            note: id,
        },
        onSuccess: handleAxiosResponse,
        onError: handleAxiosError,
    });
};

defineProps({
    note: Object,
    noteableId: Number,
    noteableType: String,
});
</script>

<template>
    <Card class="nj-note" pt:content="relative pr-6">
        <template #content>
            <div
                v-if="!isEditing"
                class="absolute right-0 top-0 flex justify-end"
            >
                <Button
                    type="button"
                    icon="pi pi-ellipsis-v"
                    @click="toggle"
                    aria-haspopup="true"
                    :aria-controls="`nj-note-menu-${note.id}`"
                    size="small"
                    severity="secondary"
                    pt:root="nj-note__options-btn"
                />
                <Menu
                    ref="menu"
                    :id="`nj-note-menu-${note.id}`"
                    :model="itemActions(note, noteableId, noteableType)"
                    :popup="true"
                />
            </div>
            <div v-if="!isEditing" class="m-0">
                {{ note.content }}
            </div>
            <Textarea v-else v-model="noteContent" class="w-full" autoResize />
            <span v-if="!isEditing" class="text-sm text-gray-400">{{
                note.updated_at
            }}</span>
            <!-- buttons -->
            <div v-if="isEditing" class="pt-4">
                <Button
                    icon="pi pi-times"
                    text
                    severity="secondary"
                    size="small"
                    label="Cancel"
                    @click="cancel"
                />
                <Button
                    icon="pi pi-trash"
                    text
                    severity="danger"
                    size="small"
                    label="Delete"
                    @click="
                        () =>
                            destroy({
                                id: note.id,
                                noteable: {
                                    id: noteableId,
                                    type: noteableType,
                                },
                            })
                    "
                />
                <Button
                    icon="pi pi-check"
                    text
                    size="small"
                    label="Save"
                    @click="
                        () =>
                            update({
                                id: note.id,
                                content: noteContent,
                                noteable: {
                                    id: noteableId,
                                    type: noteableType,
                                },
                            })
                    "
                />
            </div>
        </template>
    </Card>
</template>

<style>
.nj-dark .nj-note {
    --p-card-background: rgb(39 39 42 / var(--tw-bg-opacity));
}

.nj-note__options-btn {
    --p-button-sm-padding-x: 0;
    --p-button-sm-padding-y: 0.125rem;
    --p-button-icon-only-width: 1rem;
}
</style>
