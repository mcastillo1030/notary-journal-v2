<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Badge from 'primevue/badge';

defineProps({
    person: Object,
    addresses: Array,
});
</script>

<template>
    <Head title="People" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Person Detail
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="bg-surface-0 dark:bg-surface-950">
                            <div
                                class="text-surface-900 dark:text-surface-0 mb-4 text-3xl font-medium"
                            >
                                Person Record #{{ person.id }}
                            </div>
                            <div
                                class="text-surface-500 dark:text-surface-300 mb-8"
                            >
                                Number of notarial acts associated:
                                <Badge value="0" severity="secondary" />
                            </div>
                            <ul class="m-0 list-none p-0">
                                <li
                                    class="border-surface flex flex-wrap items-center border-t px-2 py-4"
                                >
                                    <div
                                        class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                                    >
                                        First Name
                                    </div>
                                    <div
                                        class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                                    >
                                        {{ person.first_name }}
                                    </div>
                                </li>
                                <li
                                    class="border-surface flex flex-wrap items-center border-t px-2 py-4"
                                >
                                    <div
                                        class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                                    >
                                        Last Name
                                    </div>
                                    <div
                                        class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                                    >
                                        {{ person.last_name }}
                                    </div>
                                </li>
                                <li
                                    class="border-surface flex flex-wrap items-center border-t px-2 py-4"
                                >
                                    <div
                                        class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                                    >
                                        Email
                                    </div>
                                    <div
                                        class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                                    >
                                        {{ person.email ?? '-' }}
                                    </div>
                                </li>
                                <li
                                    class="border-surface flex flex-wrap items-center border-t px-2 py-4"
                                >
                                    <div
                                        class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                                    >
                                        Phone
                                    </div>
                                    <div
                                        class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                                    >
                                        {{ person.phone ?? '-' }}
                                    </div>
                                </li>
                                <li
                                    class="border-surface flex flex-wrap items-start border-t px-2 py-4"
                                >
                                    <div
                                        class="text-surface-500 dark:text-surface-300 w-6/12 font-medium md:w-2/12"
                                    >
                                        Addresses
                                    </div>
                                    <div
                                        class="text-surface-900 dark:text-surface-0 order-1 w-full md:order-none md:w-8/12"
                                    >
                                        <ul>
                                            <li v-if="!addresses.length">
                                                No addresses.
                                            </li>
                                            <li
                                                v-else
                                                v-for="address in addresses"
                                                :key="address.id"
                                            >
                                                <address>
                                                    <span>{{
                                                        address.line_1
                                                    }}</span>
                                                    <br />
                                                    <span
                                                        v-if="address.line_2"
                                                        >{{
                                                            address.line_2
                                                        }}</span
                                                    >
                                                    <br v-if="address.line_2" />
                                                    {{ address.city }},
                                                    {{ address.state }}
                                                    {{ address.zip }}
                                                </address>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
