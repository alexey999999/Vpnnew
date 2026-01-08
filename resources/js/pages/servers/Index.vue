<script setup lang="ts">

import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, CountryForSelect, Server, ServerTypeForSelect } from '@/types';
import { index as serversIndex } from '@/routes/servers';
import { computed, ref, watch } from 'vue';
import Modal from '@/components/Modal.vue';
import FormItemInput from '@/components/FormItemInput.vue';
import FormItemSelect from '@/components/FormItemSelect.vue';

const showAddServerModal = ref(false);

watch(showAddServerModal, (isOpen) => {
    // need for disable scroll main content in html body
    if (isOpen) {
        document.documentElement.classList.add('overflow-y-hidden');
    } else {
        document.documentElement.classList.remove('overflow-y-hidden');
    }
});

const form = useForm({
    name: '',
    server_type_id: '',
    protocol_version: '',
    ipv4: '',
    country_id: '',
    url: '',
    main_token: '',
    remote_token: '',
    port: '',
    password: '',
    encryption_method: '',
});

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
    serversTypes: ServerTypeForSelect[];
    countries: CountryForSelect[];
}>();

const page = usePage()

const isServerTypeSS: string = computed(() => {
    return page.props.serversTypes.find((serverType) => {
        return serverType.value === form.server_type_id && serverType.label == 'ss'
    })
});

watch(isServerTypeSS, (isSS) => {
    if (!isSS) {
        form.port = ''
        form.password = ''
        form.encryption_method = ''
    }
});

const saveServerForm = () => {
    // Here you can process the data, e.g., send it to an API,
    // save to local storage, or perform validation

    form.post(page.props.createServerUrl, {
        onSuccess: () => {
            showAddServerModal.value = false
            form.reset()
        }, 
    });

    // Example: Send data to a backend server using fetch or axios
    /*
    fetch('/api/submit-endpoint', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(formData.value),
    })
    .then(response => response.json())
    .then(data => {
      console.log('Success:', data);
      alert('Form saved successfully!');
    })
    .catch((error) => {
      console.error('Error:', error);
    });
    */
};
</script>

<template>
    <Head title="Панель управления" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <Modal :show="showAddServerModal" @close="showAddServerModal = false">
                <h3 class="text-2xl font-bold mb-4">Добавление сервера</h3>
                <FormItemInput v-model="form.name" :label="'Название'"></FormItemInput>
                <FormItemSelect v-model="form.server_type_id" :label="'Тип'" :options="serversTypes"></FormItemSelect>
                <FormItemInput v-model="form.protocol_version" :label="'Версия протокола'"></FormItemInput>
                <FormItemInput v-model="form.ipv4" :label="'ipv4'"></FormItemInput>
                <FormItemSelect v-model="form.country_id" :label="'Страна'" :options="countries"></FormItemSelect>
                <FormItemInput v-model="form.url" :label="'Url'"></FormItemInput>
                <FormItemInput v-model="form.main_token" :label="'Главный токен'"></FormItemInput>
                <FormItemInput v-model="form.remote_token" :label="'Токен сервера'"></FormItemInput>
                <FormItemInput v-if="isServerTypeSS" v-model="form.port" :label="'Порт'"></FormItemInput>
                <FormItemInput v-if="isServerTypeSS" v-model="form.password" :label="'Пароль'"></FormItemInput>
                <FormItemInput v-if="isServerTypeSS" v-model="form.encryption_method" :label="'Метод шифрования'"></FormItemInput>
                <button @click="saveServerForm" :disabled="form.processing"
                        class="block rounded-md bg-emerald-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-500 focus:outline-none cursor-pointer"
                >
                    Сохранить
                </button>
            </Modal>
            <div>
                <button @click="showAddServerModal = true"
                        class="block rounded-md bg-blue-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500 focus:outline-none cursor-pointer"
                >
                    Добавить сервер
                </button>
            </div>
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
