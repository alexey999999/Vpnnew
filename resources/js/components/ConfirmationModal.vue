<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    show: Boolean,
    title: String,
    message: String,
    button: {
        no: String,
        yes: String
    }
});

const emit = defineEmits<{
    (e: 'result', value: boolean): void
}>()
</script>

<template>
    <teleport to="body">
        <div v-if="show" class="modal-overlay">
            <div class="modal-content-wrapper h-auto">
                <div class="modal-content w-xl h-full overflow-y-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                    <h3 class="text-2xl font-bold mb-4">{{ title }}</h3>
                    <div class="mb-4">{{ message }}</div>
                    <div>
                        <button @click="emit('result', true)"
                                class="inline-block rounded-md bg-pink-600/80 px-3 py-2 text-sm font-semibold text-white hover:bg-pink-600 focus:outline-none cursor-pointer mr-4"
                        >
                            {{ button.yes }}
                        </button>
                        <button @click="emit('result', false)"
                                class="inline-block rounded-md bg-blue-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500 focus:outline-none cursor-pointer"
                        >
                            {{ button.no }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </teleport>
</template>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
}

.modal-content-wrapper {
    padding: 20px;
    background: white;
    border-radius: 8px;
    cursor: default;
}

.modal-content {
    position: relative;
}
</style>
