<script setup>
import Badge from 'primevue/badge';
import { ref } from 'vue';

const defaultTitle = ref('Resource Record');

defineProps({
    title: String,
    withCounter: Boolean,
    counterValue: Number,
    counterLabel: String,
});
</script>

<template>
    <div class="nj-resource-header -mb-3">
        <div
            class="mx-auto max-w-7xl border-b px-4 pt-5 sm:px-6 lg:px-8 dark:border-zinc-600"
        >
            <h2
                class="text-surface-900 dark:text-surface-0 mb-4 text-3xl font-medium"
            >
                {{ title || defaultTitle }}
            </h2>
            <div
                v-if="withCounter"
                class="text-surface-500 dark:text-surface-300 mb-8"
            >
                {{ counterLabel }}:
                <Badge :value="counterValue" />
            </div>
        </div>
    </div>
</template>
