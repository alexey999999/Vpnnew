<script setup lang="ts">

import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    BreadcrumbItem,
    User
} from '@/types';
import { index as usersIndex } from '@/routes/users';

defineProps<{
    users: User[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Пользователи',
        href: usersIndex().url,
    },
];

const page = usePage()

const mainTableHeaders: string[] = [
    'ID',
    'Почта',
    'Тарифы',
    'Создан',
    'Обновлён',
];

const mainTableTdClasses: string = "border border-gray-300 p-4";
</script>

<template>
    <Head title="Конфигурации" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="overflow-x-auto">
                <table class="border-collapse border border-gray-400">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th v-for="(mainTableHeader, index) in mainTableHeaders"
                                :key="index" class="border border-gray-300 p-4"
                            >
                                {{ mainTableHeader }}
                            </th>
                        </tr>
                    </thead>
    
                    <tbody>
                        <tr v-for="user in users" :key="user.id"
                            class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900/50 dark:even:bg-gray-950 hover:bg-gray-200 dark:hover:bg-gray-800"
                        >
                            <td :class="mainTableTdClasses">{{ user.id }}</td>
                            <td :class="mainTableTdClasses">{{ user.email }}</td>
                            <td :class="mainTableTdClasses">
                                <template v-for="(tariff, index) in user.tariffs">
                                    <span :class="[tariff.is_active ? 'text-green-500' : 'text-red-500']">{{ tariff.name }}</span><template v-if="index !== user.tariffs.length - 1">, </template>
                                </template>
                            </td>
                            <td :class="mainTableTdClasses">{{ user.created_at }}</td>
                            <td :class="mainTableTdClasses">{{ user.updated_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
