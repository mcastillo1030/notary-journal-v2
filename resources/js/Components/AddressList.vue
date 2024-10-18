<script setup>
import DataView from 'primevue/dataview';
import AddressWithMenu from '@/Components/AddressWithMenu.vue';

defineProps({
    addresses: Array,
});
</script>

<template>
    <DataView
        :value="addresses"
        class="nj-address-list border-surface dark:border-0"
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
                    <AddressWithMenu :address="item" />
                </li>
            </ul>
        </template>
    </DataView>
</template>

<style>
.nj-address-list {
    --p-dataview-content-border-radius: 0.5rem;
}
</style>
