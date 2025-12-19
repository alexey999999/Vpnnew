<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();

const appUrl = computed(() => usePage().props.appUrl);
</script>

<template>
    <img src='/favicon.svg' alt="Logo"/>
</template>
