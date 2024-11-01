<script setup>
import IdWithMenu from '@/Components/IdWithMenu.vue';
import DataView from 'primevue/dataview';
import Button from 'primevue/button';

const handleAddIdentification = () => {
    document.dispatchEvent(new CustomEvent('identification.create'));
};

defineProps({
    identifications: Array,
});
</script>

<template>
    <section
        class="nj-ids-card bg-white shadow-md sm:rounded-lg dark:bg-zinc-800"
    >
        <div class="relative p-6 text-zinc-900 dark:text-zinc-100">
            <div class="bg-surface-0 dark:bg-surface-950">
                <header class="mb-6">
                    <h3
                        class="text-surface-900 dark:text-surface-0 text-2xl font-medium"
                    >
                        Identifications
                    </h3>
                    <p class="text-gray-500 dark:text-gray-300">
                        Identifications associated with this person.
                    </p>
                </header>
                <DataView
                    :value="identifications"
                    class="nj-ids-card__list border-surface dark:border-0"
                    pt:emptyMessage:class="px-4 py-2 bg-gray-100 dark:bg-zinc-700 rounded-lg"
                >
                    <template #list="slotProps">
                        <ul
                            class="border-surface rounded-lg border dark:border-0"
                        >
                            <li
                                v-for="(item, index) in slotProps.items"
                                :key="item.id"
                                class="relative p-4 pe-10"
                                :class="{
                                    'border-surface-200 dark:border-surface-700 border-t':
                                        index !== 0,
                                }"
                            >
                                <IdWithMenu :identification="item" />
                            </li>
                        </ul>
                    </template>
                </DataView>
                <Button
                    label="Add Identification"
                    icon="pi pi-plus"
                    class="mt-4"
                    size="small"
                    severity="secondary"
                    outlined
                    @click="handleAddIdentification"
                />
            </div>
        </div>
    </section>
</template>

<style>
.nj-ids-card__list {
    --p-dataview-content-border-radius: 0.5rem;
}
</style>
