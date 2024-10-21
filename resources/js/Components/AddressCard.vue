<script setup>
import AddressList from '@/Components/AddressList.vue';
import AttachAddressPopup from '@/Components/AttachAddressPopup.vue';
defineProps({
    addresses: Array,
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
                        Addresses
                    </h3>
                    <p class="text-gray-500 dark:text-gray-300">
                        Addresses associated with this person.
                    </p>
                </header>
                <AddressList :addresses="addresses" />
                <AttachAddressPopup />
            </div>
        </div>
    </section>
</template>
