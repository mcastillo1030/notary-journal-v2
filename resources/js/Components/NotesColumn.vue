<script setup>
import NoteCard from './NoteCard.vue';
import Button from 'primevue/button';
import { useDialogStore } from '@/Stores/useDialogStore';

const dialogStore = useDialogStore();

defineProps({
    notes: Array,
    noteableId: Number,
    noteableType: String,
});
</script>
<template>
    <aside class="nj-notes-column px-3 md:px-0">
        <header class="mb-4 flex justify-between">
            <h2 class="text-lg">Notes</h2>
            <Button
                icon="pi pi-plus"
                size="small"
                class="p-button-sm nj-notes-column__add-btn"
                @click="dialogStore.openNoteDialog()"
            />
        </header>
        <ul class="flex flex-col gap-3">
            <li v-if="!notes.length">No notes.</li>
            <li
                v-else
                v-for="note in notes"
                :key="note.id"
                class="bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
            >
                <NoteCard
                    :note="note"
                    :noteable-id="noteableId"
                    :noteable-type="noteableType"
                />
            </li>
        </ul>
    </aside>
</template>

<style scoped>
.nj-notes-column__add-btn {
    --p-button-sm-padding-y: 0;
    --p-button-sm-padding-x: 0;
}
</style>
