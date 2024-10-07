<script setup>
import Button from 'primevue/button';
import { setTheme } from '@/Utils/theme';
import { ref } from 'vue';
const themes = ['light', 'dark', 'system'];
const currentTheme = ref(localStorage.theme || 'system');
const cycleTheme = () => {
    const nextTheme =
        themes[(themes.indexOf(currentTheme.value) + 1) % themes.length];
    setTheme(nextTheme);
    currentTheme.value = nextTheme;
};
</script>

<template>
    <div>
        <Button
            outlined
            class="!border-1"
            size="small"
            aria-label="Toggle Theme"
            @click="cycleTheme()"
        >
            <i class="pi pi-sun" v-if="currentTheme === 'light'"></i>
            <i class="pi pi-moon" v-else-if="currentTheme === 'dark'"></i>
            <i class="pi pi-desktop" v-else></i>
        </Button>
    </div>
</template>
