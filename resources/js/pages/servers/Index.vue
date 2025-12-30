<script setup lang="ts">

import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Server } from '@/types';
import { index as serversIndex } from '@/routes/servers';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Серверы',
        href: serversIndex().url,
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
];

const serversTableTdClasses: string = "border border-gray-300 p-4";

defineProps<{
    servers: Server[];
}>();
</script>

<template>
    <Head title="Панель управления" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <table class="border-collapse border border-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th v-for="(serversTableHeader, index) in serversTableHeaders"
                            :key="index" class="border border-gray-300 p-4"
                        >
                            {{ serversTableHeader }}
                        </th>
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
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
