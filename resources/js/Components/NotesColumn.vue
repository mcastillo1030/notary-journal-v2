<script setup>
import NoteCard from './NoteCard.vue';
import Button from 'primevue/button';
import Panel from 'primevue/panel';
import { useDialogStore } from '@/Stores/useDialogStore';

const dialogStore = useDialogStore();

defineProps({
    notes: Array,
    noteableId: Number,
    noteableType: String,
});
</script>
<template>
    <aside
        class="nj-notes-column order-3 px-4 sm:px-0 lg:order-2 lg:row-span-2"
    >
        <Panel header="Notes" toggleable class="nj-notes-column__panel">
            <div class="mb-4">
                <Button
                    icon="pi pi-plus"
                    size="small"
                    label="New"
                    class="p-button-sm nj-notes-column__add-btn"
                    @click="dialogStore.openNoteDialog()"
                />
            </div>
            <ul class="flex flex-col gap-3">
                <li
                    v-if="!notes.length"
                    class="rounded-md bg-gray-200 px-4 py-2 dark:bg-zinc-700"
                >
                    No notes available
                </li>
                <li v-else v-for="note in notes" :key="note.id">
                    <NoteCard
                        :note="note"
                        :noteable-id="noteableId"
                        :noteable-type="noteableType"
                    />
                </li>
            </ul>
        </Panel>
    </aside>
</template>

<style scoped>
.nj-notes-column__panel {
    --p-panel-background: transparent;
    border: 2px solid var(--p-panel-border-color);
}
</style>
