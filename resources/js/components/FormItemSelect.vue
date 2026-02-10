<script setup lang="ts">
import { defineProps, computed } from 'vue';
import VueSelect from 'vue3-select-component';
import "vue3-select-component/styles";
import { Option } from '@/types';

interface Props {
    itemWrapperClasses?: string;
    itemLabelClasses?: string;
    // inputClasses?: string;
    label: string;
    placeholder?: string;
    options: Option[];
    error?: string;
    isMulti?: boolean;
    isDisabled?: boolean;
}

const modelValue = defineModel();

const props = withDefaults(defineProps<Props>(), {
    itemWrapperClasses: 'mb-6',
    itemLabelClasses: 'mb-2',
    // inputClasses: 'block w-full rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline focus:outline-sky-500 focus:invalid:border-pink-500 focus:invalid:outline-pink-500 disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none sm:text-sm dark:disabled:border-gray-700 dark:disabled:bg-gray-800/20',
    placeholder: '',
    error: '',
    isMulti: false,
    isDisabled: false,
});

const computedPlaceholder: string = computed(() => {
    return props.placeholder !== '' ? props.placeholder : props.label
});
</script>

<template>
    <div :class="itemWrapperClasses">
        <div :class="itemLabelClasses">{{ label }}</div>
        <div v-if="error" class="text-sm text-pink-600">
            {{ error }}
        </div>
        <VueSelect
            v-model="modelValue"
            :is-multi="isMulti"
            :is-disabled="isDisabled"
            :options="options"
            :placeholder="computedPlaceholder"
            :class="[error ? 'border rounded-md border-pink-500 text-pink-600 focus:border-pink-500 focus:outline-pink-500' : '']"
        />
    </div>
</template>