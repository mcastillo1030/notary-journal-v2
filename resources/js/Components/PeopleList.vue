<script setup>
import DataView from 'primevue/dataview';
import PersonWithMenu from './PersonWithMenu.vue';
// import AddressWithMenu from '@/Components/AddressWithMenu.vue';

defineProps({
    people: Array,
});
</script>

<template>
    <DataView
        :value="people"
        class="nj-people-list border-surface dark:border-0"
        pt:emptyMessage:class="px-4 py-2 bg-gray-100 dark:bg-zinc-700 rounded-lg"
    >
        <template #list="slotProps">
            <ul class="border-surface rounded-lg border dark:border-0">
                <li
                    v-for="(item, index) in slotProps.items"
                    :key="item.id"
                    class="relative flex justify-between gap-x-2 p-4"
                    :class="{
                        'border-surface-200 dark:border-surface-700 border-t':
                            index !== 0,
                    }"
                >
                    <PersonWithMenu :person="item" />
                </li>
            </ul>
        </template>
    </DataView>
</template>

<style>
.nj-people-list {
    --p-dataview-content-border-radius: 0.5rem;
}
</style>
