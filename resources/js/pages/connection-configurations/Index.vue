<script setup lang="ts">

import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    BreadcrumbItem,
    ConnectionConfiguration,
    ConfigurationTypeForSelect,
    ConfigurationsTypeNames, ServerTypeNames, ServerForSelect
} from '@/types';
import { index as configurationsIndex } from '@/routes/connection-configurations';
import { ref, watch, useTemplateRef, computed } from 'vue';
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

const isServersInMultiSelect = ref(false);
const serversSelectDisabled = ref(true);
const showAddConfigurationModal = ref(false);
const showDeleteServerModal = ref(false);
const isEditing = ref(false);
const deleteServerData = ref({
    id: '',
    name: '',
});
const serversIn = ref([]);
const serversOut = ref([]);

watch(showAddConfigurationModal, (isOpen) => {
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
    configuration_type_id: '',
    servers_in_ids: '',
    servers_out_ids: '',
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Конфигурации',
        href: configurationsIndex().url,
    },
];

const serversTableHeaders: string[] = [
    'ID',
    'Название',
    'Тип',
    'Входящие серверы',
    'Исходящие серверы',
    'Создан',
    'Обновлён',
];

const mainTableTdClasses: string = "border border-gray-300 p-4";
const serverActionsClasses: string = "fixed right-4";

defineProps<{
    connectionConfigurations: ConnectionConfiguration[];
    configurationsTypes: ConfigurationTypeForSelect[];
    configurationsTypeNames: ConfigurationsTypeNames;
    serverTypeNames: ServerTypeNames;
    serversShadowSocksIn: ServerForSelect[];
    serversShadowSocksOut: ServerForSelect[];
    serversDoubleVpnIn: ServerForSelect[];
    serversDoubleVpnOut: ServerForSelect[];
}>();

const page = usePage()


const configurationType: string = computed(() => {
    return page.props.configurationsTypes.find((configurationsType) => {
        return configurationsType.value === form.configuration_type_id
    });
});

watch(configurationType, (newConfigurationType: ConfigurationTypeForSelect) => {
    form.servers_in_ids = '';
    form.servers_out_ids = '';
    
    if (typeof newConfigurationType === "undefined") {
        serversSelectDisabled.value = true;
    } else {
        serversSelectDisabled.value = false;

        switch (newConfigurationType.label) {
            case page.props.configurationsTypeNames.shadowSocks:
                isServersInMultiSelect.value = true;
                serversIn.value = page.props.serversShadowSocksIn;
                serversOut.value = page.props.serversShadowSocksOut;
                break;
            case page.props.configurationsTypeNames.doubleVpn:
                isServersInMultiSelect.value = false;
                serversIn.value = page.props.serversDoubleVpnIn;
                serversOut.value = page.props.serversDoubleVpnOut;
                break;
        }
    }
});

const openEditServerForm = (serverId) => {
    isEditing.value = true;
    showAddConfigurationModal.value = true
    form.id = serverId
    Object.assign(form, findServerById(serverId))
}

const findServerById = (serverId) => {
    return page.props.servers.find((server) => connectionConfiguration.id == serverId)
}

const saveServerFormSuccess = () => {
    showAddConfigurationModal.value = false
    form.reset()
}

const saveConnectionConfigurationForm = () => {
    // Here you can process the data, e.g., send it to an API,
    // save to local storage, or perform validation
    console.log('isEditing')
    console.log(isEditing)

    if (isEditing.value) {
        console.log('update')
        form.put(page.props.updateConnectionConfigurationUrl, {
            onSuccess: () => {
                saveServerFormSuccess()
            },
        });
    } else {
        console.log('create')
        form.post(page.props.createConnectionConfigurationUrl, {
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
        showAddConfigurationModal.value = false
        form.reset()
    })
    // .catch((error) => {
    //     console.error('Error:', error);
    // });*/
};
</script>

<template>
    <Head title="Конфигурации" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ConfirmationModal :show="showDeleteServerModal"
                               :title="'Вы действительно хотите удалить сервер ' + deleteServerData.name + '?'"
                               :message="'Сервер будет перемещён в список удалённых серверов'"
                               @result="deleteServer"
            ></ConfirmationModal>
            <Modal :show="showAddConfigurationModal" :title="(isEditing ? 'Редактирование' : 'Добавление') + ' конфигурации'" @close="showAddConfigurationModal = false">
                <FormItemInput v-model="form.name" :label="'Название'" :error="page.props.errors.name"></FormItemInput>
                <FormItemSelect v-model="form.configuration_type_id" :label="'Тип'" :options="configurationsTypes" :error="page.props.errors.configuration_type_id"></FormItemSelect>
                <FormItemSelect v-model="form.servers_in_ids" :label="'Входящие серверы'" :options="serversIn" :error="page.props.errors.servers_in_ids" :isMulti="isServersInMultiSelect" :isDisabled="serversSelectDisabled"></FormItemSelect>
                <FormItemSelect v-model="form.servers_out_ids" :label="'Исходящие серверы'" :options="serversOut" :error="page.props.errors.servers_out_ids" :isMulti="true" :isDisabled="serversSelectDisabled"></FormItemSelect>
                
                <button @click="saveConnectionConfigurationForm" :disabled="form.processing"
                        class="block rounded-md bg-emerald-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-500 focus:outline-none cursor-pointer"
                >
                    Сохранить
                </button>
            </Modal>
            <div class="flex justify-between items-center">
                <button @click="form.reset(); isEditing = false; showAddConfigurationModal = true"
                        class="block rounded-md bg-blue-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500 focus:outline-none cursor-pointer text-left"
                >
                    Добавить конфигурацию
                </button>
                <Link :href="page.props.deletedUrl" as="button" type="button" class="block rounded-md bg-red-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500 focus:outline-none cursor-pointer text-right">
                    Удалённые конфигурации
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
                            <td :class="mainTableTdClasses">
                                <div :class="''">
                                    <component :class="'cursor-pointer mb-3'" :is="SquarePen" @click="openEditServerForm(connectionConfiguration.id)" />
                                    <component :class="'text-pink-600 cursor-pointer'" :is="Trash2" @click="deleteServerModal(connectionConfiguration.id)" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
