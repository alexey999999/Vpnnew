<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();
const show = ref(false);
const message = computed(() => page.props.flash.success);

watch(message, (newMessage) => {
    if (newMessage) {
        show.value = true;
        setTimeout(() => show.value = false, 3000); // Hide after 3 seconds
    }
});
</script>

<template>
    <div v-if="show" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded shadow-lg">
        {{ message }}
    </div>
</template>