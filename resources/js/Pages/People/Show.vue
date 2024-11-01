<script setup>
// Imports
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import NotesColumn from '@/Components/NotesColumn.vue';
import PersonDetail from '@/Components/PersonDetail.vue';
import AddNoteDialog from '@/Components/AddNoteDialog.vue';
import AddressDialog from '@/Components/AddressEditDialog.vue';
import ResourceHeader from '@/Components/ResourceHeader.vue';
import AddressCard from '@/Components/AddressesCard.vue';
import IdentificationsCard from '@/Components/IdentificationsCard.vue';
import IdentificationDialog from '@/Components/IdentificationDialog.vue';

// Lifecycle
defineProps({
    person: Object,
    addresses: Array,
    notes: Array,
    identifications: Array,
});
</script>

<template>
    <Head title="Person Detail" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-zinc-800 dark:text-zinc-200"
            >
                Person Detail
            </h2>
        </template>

        <ResourceHeader
            :title="`Person Record #${person.id}`"
            :withCounter="true"
            :counterValue="0"
            counterLabel="Number of acts associated"
        />

        <AddNoteDialog :noteableId="person.id" noteableType="person" />

        <AddressDialog />

        <IdentificationDialog />

        <div class="py-12">
            <article
                class="mx-auto grid max-w-7xl grid-cols-1 gap-6 sm:px-6 lg:grid-cols-3 lg:gap-8 lg:px-8"
            >
                <div class="flex flex-col gap-y-6 lg:col-span-2">
                    <PersonDetail :person="person" />
                    <AddressCard :addresses="addresses" />
                    <IdentificationsCard :identifications="identifications" />
                </div>
                <NotesColumn
                    :notes="notes"
                    :noteableId="person.id"
                    noteableType="person"
                />
            </article>
        </div>
    </AuthenticatedLayout>
</template>
