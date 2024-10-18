<script setup>
// Imports
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import NotesColumn from '@/Components/NotesColumn.vue';
import PersonDetail from '@/Components/PersonDetail.vue';
import AddNoteDialog from '@/Components/AddNoteDialog.vue';
import AddressDialog from '@/Components/AddressDialog.vue';

// Lifecycle
defineProps({
    person: Object,
    addresses: Array,
    notes: Array,
});
</script>

<template>
    <Head title="People" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-zinc-800 dark:text-zinc-200"
            >
                Person Detail
            </h2>
        </template>

        <AddNoteDialog :noteableId="person.id" noteableType="person" />

        <AddressDialog />

        <div class="py-12">
            <article
                class="mx-auto grid max-w-7xl grid-cols-1 gap-6 sm:px-6 lg:grid-cols-3 lg:items-start lg:gap-8 lg:px-8"
            >
                <PersonDetail :person="person" :addresses="addresses" />
                <NotesColumn
                    :notes="notes"
                    :noteableId="person.id"
                    noteableType="person"
                    @addNoteClick="addNoteDialogVisible = true"
                />
            </article>
        </div>
    </AuthenticatedLayout>
</template>
