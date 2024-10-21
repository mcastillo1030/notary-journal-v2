<script setup>
import PeopleList from './PeopleList.vue';
import AttachPeoplePopup from './AttachPeoplePopup.vue';

defineProps({
    people: Array,
});
</script>

<template>
    <section
        class="nj-address-card bg-white shadow-md sm:rounded-lg dark:bg-zinc-800"
    >
        <div class="relative p-6 text-zinc-900 dark:text-zinc-100">
            <div class="bg-surface-0 dark:bg-surface-950">
                <header class="mb-6">
                    <h3
                        class="text-surface-900 dark:text-surface-0 text-2xl font-medium"
                    >
                        People
                    </h3>
                    <p class="text-gray-500 dark:text-gray-300">
                        People associated with this address.
                    </p>
                </header>

                <PeopleList :people="people" />
                <AttachPeoplePopup />
            </div>
        </div>
    </section>
</template>
