<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import { X } from 'lucide-vue-next';

const props = defineProps({
    show: Boolean
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
