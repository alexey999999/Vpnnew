<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import { X } from 'lucide-vue-next';

interface Props {
    show: boolean;
    title?: string;
}

const props = withDefaults(defineProps<Props>(), {
    title: '',
    itemWrapperClasses: 'mb-6',
    itemLabelClasses: 'mb-2',
    // inputClasses: 'block w-full rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline focus:outline-sky-500 focus:invalid:border-pink-500 focus:invalid:outline-pink-500 disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none sm:text-sm dark:disabled:border-gray-700 dark:disabled:bg-gray-800/20',
    placeholder: '',
    error: '',
    isMulti: false,
    isDisabled: false,
});

const emit = defineEmits(['close']);
const close = () => emit('close');
</script>

<template>
    <teleport to="body">
        <div v-if="show" class="modal-overlay" @click.self="close">
            <div class="modal-content-wrapper h-[calc(100vh-40px)]">
                <div class="modal-content w-xl h-full overflow-y-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                    <component :is="X" @click="close"
                               class="bg-white rounded-[12px] absolute top-[7px] right-[5px] cursor-pointer text-black/75 hover:text-black"
                    />
                    <h3 class="text-2xl font-bold mb-4">{{ title}}</h3>
                    <slot></slot>
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
    cursor: pointer;
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
