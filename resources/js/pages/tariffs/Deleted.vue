<script setup lang="ts">

import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Tariff } from '@/types';
import { index as configurationsDeletedIndex } from '@/routes/tariffs/deleted';
import { ref } from 'vue';
import { ArchiveRestore, Trash2 } from 'lucide-vue-next';
import ConfirmationModal from '@/components/ConfirmationModal.vue';
import { index as tariffsIndex } from '@/routes/tariffs';

defineProps<{
    tariffs: Tariff[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Тарифы',
        href: tariffsIndex().url,
    },
    {
        title: 'Удалённые тарифы',
        href: configurationsDeletedIndex().url,
    },
];

const showRestoreConnectionConfigurationModal = ref(false);
const showRestoreAllConnectionConfigurationsModal = ref(false);
const showFinallyDeleteConnectionConfigurationModal = ref(false);
const showFinallyDeleteAllConnectionConfigurationsModal = ref(false);

const restoreConnectionConfigurationData = ref({
    id: '',
    name: '',
});

const finallyDeleteConnectionConfigurationData = ref({
    id: '',
    name: '',
});

const mainTableHeaders: string[] = [
    'ID',
    'Название',
    'Конфигурации',
    'Создан',
    'Обновлён',
    'Удалён',
];

const mainTableTdClasses: string = "border border-gray-300 p-4";

const page = usePage()

const restoreConnectionConfigurationModal = (connectionConfigurationId) => {
    showRestoreConnectionConfigurationModal.value = true
    restoreConnectionConfigurationData.value = findConnectionConfigurationById(connectionConfigurationId)
}

const restoreConnectionConfiguration = (isConfirmed) => {
    showRestoreConnectionConfigurationModal.value = false
    
    if (isConfirmed) {
        useForm({id: restoreConnectionConfigurationData.value.id}).put(page.props.restoreConnectionConfigurationUrl);
    }
}

const restoreAllServersModal = () => {
    showRestoreAllConnectionConfigurationsModal.value = true
}

const restoreAllConnectionConfigurations = (isConfirmed) => {
    showRestoreAllConnectionConfigurationsModal.value = false
    
    if (isConfirmed) {
        useForm().put(page.props.restoreAllConnectionConfigurationsUrl);
    }
}

const finallyDeleteConnectionConfigurationModal = (connectionConfigurationId) => {
    showFinallyDeleteConnectionConfigurationModal.value = true
    finallyDeleteConnectionConfigurationData.value = findConnectionConfigurationById(connectionConfigurationId)
}

const finallyDeleteConnectionConfiguration = (isConfirmed) => {
    showFinallyDeleteConnectionConfigurationModal.value = false

    if (isConfirmed) {
        useForm({id: finallyDeleteConnectionConfigurationData.value.id}).delete(page.props.finallyDeleteConnectionConfigurationUrl);
    }
}

const finallyDeleteAllServersModal = () => {
    showFinallyDeleteAllConnectionConfigurationsModal.value = true
}

const finallyDeleteAllConnectionConfigurations = (isConfirmed) => {
    showFinallyDeleteAllConnectionConfigurationsModal.value = false
    
    if (isConfirmed) {
        useForm().delete(page.props.finallyDeleteAllConnectionConfigurationsUrl);
    }
}

const findConnectionConfigurationById = (connectionConfigurationId) => {
    return page.props.connectionConfigurations.find((connectionConfiguration) => tariff.id == connectionConfigurationId)
}
</script>

<template>
    <Head title="Удалённые конфигурации" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ConfirmationModal :show="showRestoreConnectionConfigurationModal"
                               :title="'Вы действительно хотите восстановить конфигурацию ' + restoreConnectionConfigurationData.name + '?'"
                               :message="'Конфигурация будет перемещена в список конфигураций'"
                               @result="restoreConnectionConfiguration"
            ></ConfirmationModal>
            <ConfirmationModal :show="showRestoreAllConnectionConfigurationsModal"
                               :title="'Вы действительно хотите восстановить ВСЕ конфигурации?'"
                               :message="'ВНИМАНИЕ! ВСЕ!!! Конфигурации будут восстановлены!'"
                               @result="restoreAllConnectionConfigurations"
            ></ConfirmationModal>
            <ConfirmationModal :show="showFinallyDeleteConnectionConfigurationModal"
                               :title="'Вы действительно хотите БЕЗВОЗВРАТНО удалить конфигурацию ' + finallyDeleteConnectionConfigurationData.name + '?'"
                               :message="'ВНИМАНИЕ! конфигурацию будет БЕЗВОЗВРАТНО удалён!'"
                               @result="finallyDeleteConnectionConfiguration"
            ></ConfirmationModal>
            <ConfirmationModal :show="showFinallyDeleteAllConnectionConfigurationsModal"
                               :title="'Вы действительно хотите БЕЗВОЗВРАТНО удалить ВСЕ конфигурации?'"
                               :message="'ВНИМАНИЕ! ВСЕ!!! Конфигурации будут БЕЗВОЗВРАТНО удалены!'"
                               @result="finallyDeleteAllConnectionConfigurations"
            ></ConfirmationModal>
            <div>
                <button v-if="tariffs.length > 0"
                        @click="restoreAllServersModal"
                        class="inline-block rounded-md bg-blue-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500 focus:outline-none cursor-pointer"
                >
                    Восстановить все конфигурации
                </button>
                <button v-if="tariffs.length > 0"
                        @click="finallyDeleteAllServersModal"
                        class="inline-block rounded-md bg-red-500/80 ml-3 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500 focus:outline-none cursor-pointer"
                >
                    БЕЗВОЗВРАТНО удалить все конфигурации
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="border-collapse border border-gray-400">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th v-for="(mainTableHeader, index) in mainTableHeaders"
                                :key="index" class="border border-gray-300 p-4"
                            >
                                {{ mainTableHeader }}
                            </th>
                            <th ref="serverActions" class="border border-gray-300 p-4"></th>
                        </tr>
                    </thead>
    
                    <tbody>
                        <tr v-for="tariff in tariffs" :key="tariff.id"
                            class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900/50 dark:even:bg-gray-950 hover:bg-gray-200 dark:hover:bg-gray-800"
                        >
                            <td :class="mainTableTdClasses">{{ tariff.id }}</td>
                            <td :class="mainTableTdClasses">{{ tariff.name }}</td>
                            <td :class="mainTableTdClasses">
                                <template v-for="(configuration, index) in tariff.configurations">
                                    {{ configuration.name }}<template v-if="index !== tariff.configurations.length - 1">, </template>
                                </template>
                            </td>
                            <td :class="mainTableTdClasses">{{ tariff.created_at }}</td>
                            <td :class="mainTableTdClasses">{{ tariff.updated_at }}</td>
                            <td :class="mainTableTdClasses">{{ tariff.deleted_at }}</td>
                            <td :class="mainTableTdClasses">
                                <div :class="''">
                                    <component :class="'cursor-pointer mb-3'" :is="ArchiveRestore" @click="restoreConnectionConfigurationModal(tariff.id)" />
                                    <component :class="'text-pink-600 cursor-pointer'" :is="Trash2" @click="finallyDeleteConnectionConfigurationModal(tariff.id)" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
