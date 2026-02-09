<script setup lang="ts">

import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, CountryForSelect, Server, ServerTypeForSelect } from '@/types';
import { index as serversIndex } from '@/routes/servers';
import { index as serversDeletedIndex } from '@/routes/servers/deleted';
import { ref, useTemplateRef } from 'vue';
import { ArchiveRestore, Trash2 } from 'lucide-vue-next';
import { useElementVisibility } from '@vueuse/core';
import ConfirmationModal from '@/components/ConfirmationModal.vue';

const restoreServerModal = (serverId) => {
    showRestoreServerModal.value = true
    restoreServerData.value = findServerById(serverId)
}

const restoreServer = (isConfirmed) => {
    showRestoreServerModal.value = false
    
    if (isConfirmed) {
        useForm({id: restoreServerData.value.id}).put(page.props.restoreServerUrl);
    }
}

const restoreAllServersModal = () => {
    showRestoreAllServersModal.value = true
}

const restoreAllServers = (isConfirmed) => {
    showRestoreAllServersModal.value = false
    
    if (isConfirmed) {
        useForm().put(page.props.restoreAllServersUrl);
    }
}

const finallyDeleteServerModal = (serverId) => {
    showFinallyDeleteServerModal.value = true
    finallyDeleteServerData.value = findServerById(serverId)
}

const finallyDeleteServer = (isConfirmed) => {
    showFinallyDeleteServerModal.value = false

    if (isConfirmed) {
        useForm({id: finallyDeleteServerData.value.id}).delete(page.props.finallyDeleteServerUrl);
    }
}

const finallyDeleteAllServersModal = () => {
    showFinallyDeleteAllServersModal.value = true
}

const finallyDeleteAllServers = (isConfirmed) => {
    showFinallyDeleteAllServersModal.value = false
    
    if (isConfirmed) {
        useForm().delete(page.props.finallyDeleteAllServersUrl);
    }
}

const serverActionsEl = useTemplateRef('serverActions')
const isVisibleServerActions = useElementVisibility(serverActionsEl)

const showRestoreServerModal = ref(false);
const showRestoreAllServersModal = ref(false);
const showFinallyDeleteServerModal = ref(false);
const showFinallyDeleteAllServersModal = ref(false);

const restoreServerData = ref({
    id: '',
    name: '',
});

const finallyDeleteServerData = ref({
    id: '',
    name: '',
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Серверы',
        href: serversIndex().url,
    },
    {
        title: 'Удалённые серверы',
        href: serversDeletedIndex().url,
    },
];

const serversTableHeaders: string[] = [
    'ID',
    'Название',
    'Тип',
    'Версия протокола',
    'ipv4',
    'Страна',
    'Url',
    'Главный токен',
    'Токен сервера',
    'Текущая нагрузка',
    'Средняя нагрузка',
    'Порт',
    'Пароль',
    'Метод шифрования',
    'Создан',
    'Обновлён',
    'Удалён',
];

const serversTableTdClasses: string = "border border-gray-300 p-4";
const serverActionsClasses: string = "fixed right-4";

defineProps<{
    servers: Server[];
    serversTypes: ServerTypeForSelect[];
    countries: CountryForSelect[];
}>();

const page = usePage()

const findServerById = (serverId) => {
    return page.props.servers.find((server) => server.id == serverId)
}
</script>

<template>
    <Head title="Удалённые серверы" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ConfirmationModal :show="showRestoreServerModal"
                               :title="'Вы действительно хотите восстановить сервер ' + restoreServerData.name + '?'"
                               :message="'Сервер будет перемещён в список серверов'"
                               @result="restoreServer"
            ></ConfirmationModal>
            <ConfirmationModal :show="showRestoreAllServersModal"
                               :title="'Вы действительно хотите восстановить ВСЕ серверы?'"
                               :message="'ВНИМАНИЕ! ВСЕ!!! Серверы будут восстановлены!'"
                               @result="restoreAllServers"
            ></ConfirmationModal>
            <ConfirmationModal :show="showFinallyDeleteServerModal"
                               :title="'Вы действительно хотите БЕЗВОЗВРАТНО удалить сервер ' + finallyDeleteServerData.name + '?'"
                               :message="'ВНИМАНИЕ! Сервер будет БЕЗВОЗВРАТНО удалён!'"
                               @result="finallyDeleteServer"
            ></ConfirmationModal>
            <ConfirmationModal :show="showFinallyDeleteAllServersModal"
                               :title="'Вы действительно хотите БЕЗВОЗВРАТНО удалить ВСЕ серверы?'"
                               :message="'ВНИМАНИЕ! ВСЕ!!! Серверы будут БЕЗВОЗВРАТНО удалены!'"
                               @result="finallyDeleteAllServers"
            ></ConfirmationModal>
            <div>
                <button v-if="servers.length > 0"
                        @click="restoreAllServersModal"
                        class="inline-block rounded-md bg-blue-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500 focus:outline-none cursor-pointer"
                >
                    Восстановить все серверы
                </button>
                <button v-if="servers.length > 0"
                        @click="finallyDeleteAllServersModal"
                        class="inline-block rounded-md bg-red-500/80 ml-3 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500 focus:outline-none cursor-pointer"
                >
                    БЕЗВОЗВРАТНО удалить все серверы
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="border-collapse border border-gray-400">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th v-for="(serversTableHeader, index) in serversTableHeaders"
                                :key="index" class="border border-gray-300 p-4"
                            >
                                {{ serversTableHeader }}
                            </th>
                            <th ref="serverActions" class="border border-gray-300 p-4"></th>
                        </tr>
                    </thead>
    
                    <tbody>
                        <tr v-for="server in servers" :key="server.id"
                            class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900/50 dark:even:bg-gray-950 hover:bg-gray-200 dark:hover:bg-gray-800"
                        >
                            <td :class="serversTableTdClasses">{{ server.id }}</td>
                            <td :class="serversTableTdClasses">{{ server.name }}</td>
                            <td :class="serversTableTdClasses">{{ server.server_type.name }}</td>
                            <td :class="serversTableTdClasses">{{ server.protocol_version }}</td>
                            <td :class="serversTableTdClasses">{{ server.ipv4 }}</td>
                            <td :class="serversTableTdClasses">{{ server.country.name }}</td>
                            <td :class="serversTableTdClasses">{{ server.url }}</td>
                            <td :class="serversTableTdClasses">{{ server.main_token }}</td>
                            <td :class="serversTableTdClasses">{{ server.remote_token }}</td>
                            <td :class="serversTableTdClasses">{{ server.current_load }}</td>
                            <td :class="serversTableTdClasses">{{ server.avg_load }}</td>
                            <td :class="serversTableTdClasses">{{ server.port }}</td>
                            <td :class="serversTableTdClasses">{{ server.password }}</td>
                            <td :class="serversTableTdClasses">{{ server.encryption_method }}</td>
                            <td :class="serversTableTdClasses">{{ server.created_at }}</td>
                            <td :class="serversTableTdClasses">{{ server.updated_at }}</td>
                            <td :class="serversTableTdClasses">{{ server.deleted_at }}</td>
                            <td :class="[isVisibleServerActions ? serversTableTdClasses : serverActionsClasses]">
                                <div :class="[isVisibleServerActions ? '' : 'bg-white p-2']">
                                    <component :class="'cursor-pointer mb-3'" :is="ArchiveRestore" @click="restoreServerModal(server.id)" />
                                    <component :class="'text-pink-600 cursor-pointer'" :is="Trash2" @click="finallyDeleteServerModal(server.id)" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
