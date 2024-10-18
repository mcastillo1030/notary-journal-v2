<script setup>
import Breadcrumb from 'primevue/breadcrumb';
import { Link } from '@inertiajs/vue3';

defineProps({
    breadcrumbs: Array,
});
</script>

<template>
    <div class="-mb-6 pt-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <Breadcrumb
                :model="breadcrumbs"
                pt:root="sm:rounded-lg"
                class="nj-admin-breadcrumb shadow-md"
            >
                <template #item="{ item }">
                    <span
                        v-if="route().current(item.key) || !!!item.route"
                        class="text-gray-500 dark:text-gray-300"
                    >
                        <span v-if="item.label === 'Home'">
                            <i class="pi pi-home"></i>
                        </span>
                        <span v-else>
                            {{ item.label }}
                        </span>
                    </span>
                    <Link
                        v-else
                        :href="item.route"
                        class="text-gray-500 dark:text-gray-300"
                    >
                        <span v-if="item.label === 'Home'">
                            <i class="pi pi-home"></i>
                        </span>
                        <span v-else>
                            {{ item.label }}
                        </span>
                    </Link>
                </template>
            </Breadcrumb>
        </div>
    </div>
</template>

<style>
.nj-dark .nj-admin-breadcrumb {
    --p-breadcrumb-background: rgb(39 39 42 / var(--tw-bg-opacity));
}
</style>
