<script setup lang="ts">

import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, ConnectionConfiguration } from '@/types';
import { index as configurationsIndex } from '@/routes/connection-configurations';
import { index as configurationsDeletedIndex } from '@/routes/connection-configurations/deleted';
import { ref } from 'vue';
import { ArchiveRestore, Trash2 } from 'lucide-vue-next';
import ConfirmationModal from '@/components/ConfirmationModal.vue';

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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Конфигурации',
        href: configurationsIndex().url,
    },
    {
        title: 'Удалённые конфигурации',
        href: configurationsDeletedIndex().url,
    },
];

const mainTableHeaders: string[] = [
    'ID',
    'Название',
    'Тип',
    'Входящие конфигурации',
    'Исходящие конфигурации',
    'Создан',
    'Обновлён',
    'Удалён',
];

const mainTableTdClasses: string = "border border-gray-300 p-4";

defineProps<{
    connectionConfigurations: ConnectionConfiguration[];
}>();

const page = usePage()

const findConnectionConfigurationById = (connectionConfigurationId) => {
    return page.props.connectionConfigurations.find((connectionConfiguration) => connectionConfiguration.id == connectionConfigurationId)
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
                               :message="'ВНИМАНИЕ! Конфигурация будет БЕЗВОЗВРАТНО удалена!'"
                               @result="finallyDeleteConnectionConfiguration"
            ></ConfirmationModal>
            <ConfirmationModal :show="showFinallyDeleteAllConnectionConfigurationsModal"
                               :title="'Вы действительно хотите БЕЗВОЗВРАТНО удалить ВСЕ конфигурации?'"
                               :message="'ВНИМАНИЕ! ВСЕ!!! Конфигурации будут БЕЗВОЗВРАТНО удалены!'"
                               @result="finallyDeleteAllConnectionConfigurations"
            ></ConfirmationModal>
            <div>
                <button v-if="connectionConfigurations.length > 0"
                        @click="restoreAllServersModal"
                        class="inline-block rounded-md bg-blue-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500 focus:outline-none cursor-pointer"
                >
                    Восстановить все конфигурации
                </button>
                <button v-if="connectionConfigurations.length > 0"
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
                            <th class="border border-gray-300 p-4"></th>
                        </tr>
                    </thead>
    
                    <tbody>
                        <tr v-for="connectionConfiguration in connectionConfigurations" :key="connectionConfiguration.id"
                            class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900/50 dark:even:bg-gray-950 hover:bg-gray-200 dark:hover:bg-gray-800"
                        >
                            <td :class="mainTableTdClasses">{{ connectionConfiguration.id }}</td>
                            <td :class="mainTableTdClasses">{{ connectionConfiguration.name }}</td>
                            <td :class="mainTableTdClasses">{{ connectionConfiguration.configuration_type.name }}</td>
                            <td :class="mainTableTdClasses">
                                <template v-for="(serverIn, index) in connectionConfiguration.servers_in">
                                    {{ serverIn.name }}<template v-if="index !== connectionConfiguration.servers_in.length - 1">, </template>
                                </template>
                            </td>
                            <td :class="mainTableTdClasses">
                                <template v-for="(serverOut, index) in connectionConfiguration.servers_out">
                                    {{ serverOut.name }}<template v-if="index !== connectionConfiguration.servers_out.length - 1">, </template>
                                </template>
                            </td>
                            <td :class="mainTableTdClasses">{{ connectionConfiguration.created_at }}</td>
                            <td :class="mainTableTdClasses">{{ connectionConfiguration.updated_at }}</td>
                            <td :class="mainTableTdClasses">{{ connectionConfiguration.deleted_at }}</td>
                            <td :class="mainTableTdClasses">
                                <div :class="''">
                                    <component :class="'cursor-pointer mb-3'" :is="ArchiveRestore" @click="restoreConnectionConfigurationModal(connectionConfiguration.id)" />
                                    <component :class="'text-pink-600 cursor-pointer'" :is="Trash2" @click="finallyDeleteConnectionConfigurationModal(connectionConfiguration.id)" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
