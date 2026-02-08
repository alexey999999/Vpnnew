<script setup lang="ts">

import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, CountryForSelect, Server, ServerTypeForSelect } from '@/types';
import { index as serversIndex } from '@/routes/servers';
import { computed, ref, watch, useTemplateRef } from 'vue';
import Modal from '@/components/Modal.vue';
import FormItemInput from '@/components/FormItemInput.vue';
import FormItemSelect from '@/components/FormItemSelect.vue';
import { SquarePen, Trash2 } from 'lucide-vue-next';
import { useElementVisibility } from '@vueuse/core';
import ConfirmationModal from '@/components/ConfirmationModal.vue';

const deleteServerModal = (serverId) => {
    showDeleteServerModal.value = true
    deleteServerData.value = findServerById(serverId)
}

const deleteServer = (isConfirmed) => {
    showDeleteServerModal.value = false
    
    if (isConfirmed) {
        useForm({id: deleteServerData.value.id}).delete(page.props.deleteServerUrl);
    }
}

const serverActionsEl = useTemplateRef('serverActions')
const isVisibleServerActions = useElementVisibility(serverActionsEl)

const showAddServerModal = ref(false);
const showDeleteServerModal = ref(false);
const isEditing = ref(false);
const deleteServerData = ref({
    id: '',
    name: '',
});

watch(showAddServerModal, (isOpen) => {
    // need for disable scroll main content in html body
    if (isOpen) {
        document.documentElement.classList.add('overflow-y-hidden');
    } else {
        document.documentElement.classList.remove('overflow-y-hidden');
    }
});

const form = useForm({
    id: '',
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
const serverActionsClasses: string = "fixed right-4";

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

const openEditServerForm = (serverId) => {
    isEditing.value = true;
    showAddServerModal.value = true
    form.id = serverId
    Object.assign(form, findServerById(serverId))
}

const findServerById = (serverId) => {
    return page.props.servers.find((server) => server.id == serverId)
}

const saveServerFormSuccess = () => {
    showAddServerModal.value = false
    form.reset()
}

const saveServerForm = () => {
    // Here you can process the data, e.g., send it to an API,
    // save to local storage, or perform validation

    if (isEditing) {
        form.put(page.props.updateServerUrl, {
            onSuccess: () => {
                saveServerFormSuccess()
            },
        });
    } else {
        form.post(page.props.createServerUrl, {
            onSuccess: () => {
                saveServerFormSuccess()
            },
        });
    }

    // Example: Send data to a backend server using fetch or axios
    /*fetch(isEditing ? page.props.updateServerUrl : page.props.createServerUrl, {
      method: isEditing ? 'PUT' : 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(form.data()),
    })
    .then(response => response.json())
    .then(data => {
        // console.log('Success:', data);
        // alert('Form saved successfully!');
        showAddServerModal.value = false
        form.reset()
    })
    // .catch((error) => {
    //     console.error('Error:', error);
    // });*/
};
</script>

<template>
    <Head title="Панель управления" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ConfirmationModal :show="showDeleteServerModal"
                               :title="'Вы действительно хотите удалить сервер ' + deleteServerData.name + '?'"
                               :message="'Сервер будет перемещён в список удалённых серверов'"
                               @result="deleteServer"
            ></ConfirmationModal>
            <Modal :show="showAddServerModal" @close="showAddServerModal = false">
                <h3 class="text-2xl font-bold mb-4">{{ isEditing ? 'Редактирование' : 'Добавление' }} сервера</h3>
                <FormItemInput v-model="form.name" :label="'Название'" :error="page.props.errors.name"></FormItemInput>
                <FormItemSelect v-model="form.server_type_id" :label="'Тип'" :options="serversTypes" :error="page.props.errors.server_type_id"></FormItemSelect>
                <FormItemInput v-model="form.protocol_version" :label="'Версия протокола'" :error="page.props.errors.protocol_version"></FormItemInput>
                <FormItemInput v-model="form.ipv4" :label="'ipv4'" :error="page.props.errors.ipv4"></FormItemInput>
                <FormItemSelect v-model="form.country_id" :label="'Страна'" :options="countries" :error="page.props.errors.country_id"></FormItemSelect>
                <FormItemInput v-model="form.url" :label="'Url'" :error="page.props.errors.url"></FormItemInput>
                <FormItemInput v-model="form.main_token" :label="'Главный токен'" :error="page.props.errors.main_token"></FormItemInput>
                <FormItemInput v-model="form.remote_token" :label="'Токен сервера'" :error="page.props.errors.remote_token"></FormItemInput>
                <FormItemInput v-if="isServerTypeSS" v-model="form.port" :label="'Порт'" :error="page.props.errors.port"></FormItemInput>
                <FormItemInput v-if="isServerTypeSS" v-model="form.password" :label="'Пароль'" :error="page.props.errors.password"></FormItemInput>
                <FormItemInput v-if="isServerTypeSS" v-model="form.encryption_method" :label="'Метод шифрования'" :error="page.props.errors.encryption_method"></FormItemInput>
                <button @click="saveServerForm" :disabled="form.processing"
                        class="block rounded-md bg-emerald-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-500 focus:outline-none cursor-pointer"
                >
                    Сохранить
                </button>
            </Modal>
            <div class="flex justify-between items-center">
                <button @click="form.reset(); isEditing = false; showAddServerModal = true"
                        class="block rounded-md bg-blue-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500 focus:outline-none cursor-pointer text-left"
                >
                    Добавить сервер
                </button>
                <Link :href="page.props.deletedIndexUrl" as="button" type="button" class="block rounded-md bg-red-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500 focus:outline-none cursor-pointer text-right">
                    Удалённые серверы
                </Link>
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
                            <td :class="[isVisibleServerActions ? serversTableTdClasses : serverActionsClasses]">
                                <div :class="[isVisibleServerActions ? '' : 'bg-white p-2']">
                                    <component :class="'cursor-pointer mb-3'" :is="SquarePen" @click="openEditServerForm(server.id)" />
                                    <component :class="'text-pink-600 cursor-pointer'" :is="Trash2" @click="deleteServerModal(server.id)" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
