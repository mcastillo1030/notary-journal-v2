<script setup>
// Imports
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import NotesColumn from '@/Components/NotesColumn.vue';
import AddressDetail from '@/Components/AddressDetail.vue';
import AddNoteDialog from '@/Components/AddNoteDialog.vue';
import ResourceHeader from '@/Components/ResourceHeader.vue';
import PeopleCard from '@/Components/PeopleCard.vue';

// Lifecycle
defineProps({
    address: Object,
    people: Array,
    notes: Array,
});
</script>

<template>
    <Head title="Address Detail" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-zinc-800 dark:text-zinc-200"
            >
                Address Detail
            </h2>
        </template>

        <ResourceHeader
            :title="`Address Record #${address.id}`"
            :withCounter="true"
            :counterValue="people.length"
            counterLabel="Number of people associated"
        />

        <AddNoteDialog :noteableId="address.id" noteableType="address" />

        <div class="py-12">
            <article
                class="mx-auto grid max-w-7xl grid-cols-1 gap-6 sm:px-6 lg:grid-cols-3 lg:gap-8 lg:px-8"
            >
                <div class="flex flex-col gap-y-6 lg:col-span-2">
                    <AddressDetail :address="address" />
                    <PeopleCard :people="people" />
                </div>
                <NotesColumn
                    :notes="notes"
                    :noteableId="address.id"
                    noteableType="address"
                />
            </article>
        </div>
    </AuthenticatedLayout>
</template>
