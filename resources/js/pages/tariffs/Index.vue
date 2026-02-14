<script setup lang="ts">

import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    BreadcrumbItem,
    Tariff
} from '@/types';
import { index as tariffsIndex } from '@/routes/tariffs';
import { ref, watch } from 'vue';
import Modal from '@/components/Modal.vue';
import FormItemInput from '@/components/FormItemInput.vue';
import FormItemSelect from '@/components/FormItemSelect.vue';
import { SquarePen, Trash2 } from 'lucide-vue-next';
import ConfirmationModal from '@/components/ConfirmationModal.vue';
import { ConnectionConfigurationForSelect } from '@/types';

defineProps<{
    tariffs: Tariff[];
    configurations: ConnectionConfigurationForSelect[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Тарифы',
        href: tariffsIndex().url,
    },
];

const showAddTariffModal = ref(false);
const showDeleteTariffModal = ref(false);
const isEditing = ref(false);
const deleteTariffData = ref({
    id: '',
    name: '',
});

const page = usePage()

const form = useForm({
    id: '',
    name: '',
    configurations_ids: '',
});

const mainTableHeaders: string[] = [
    'ID',
    'Название',
    'Конфигурации',
    'Создан',
    'Обновлён',
];

const mainTableTdClasses: string = "border border-gray-300 p-4";

const deleteTariffModal = (tariffId) => {
    showDeleteTariffModal.value = true
    deleteTariffData.value = findTariffById(tariffId)
}

const deleteTariff = (isConfirmed) => {
    showDeleteTariffModal.value = false
    
    if (isConfirmed) {
        useForm({id: deleteTariffData.value.id}).delete(page.props.deleteTariffUrl);
    }
}

watch(showAddTariffModal, (isOpen) => {
    // need for disable scroll main content in html body
    if (isOpen) {
        document.documentElement.classList.add('overflow-y-hidden');
    } else {
        document.documentElement.classList.remove('overflow-y-hidden');
    }
});

const openEditTariffForm = (tariffId) => {
    form.reset()

    isEditing.value = true;
    showAddTariffModal.value = true
    
    const tariff = findTariffById(tariffId)
    tariff.configurations_ids = tariff.configurations.map(configuration => configuration.id)

    Object.assign(form, tariff)
}

const findTariffById = (tariffId) => {
    return page.props.tariffs.find((tariff) => tariff.id == tariffId)
}

const saveTariffFormSuccess = () => {
    showAddTariffModal.value = false
    form.reset()
}

const saveTariffForm = () => {
    if (isEditing.value) {
        form.put(page.props.createTariffUrl, {
            onSuccess: () => {
                saveTariffFormSuccess()
            },
        });
    } else {
        form.post(page.props.updateTariffUrl, {
            onSuccess: () => {
                saveTariffFormSuccess()
            },
        });
    }
};
</script>

<template>
    <Head title="Конфигурации" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ConfirmationModal :show="showDeleteTariffModal"
                               :title="'Вы действительно хотите удалить тариф ' + deleteTariffData.name + '?'"
                               :message="'Тариф будет перемещена в список удалённых тарифов'"
                               @result="deleteTariff"
            ></ConfirmationModal>
            <Modal :show="showAddTariffModal" :title="(isEditing ? 'Редактирование' : 'Добавление') + ' тарифа'" @close="showAddTariffModal = false">
                <FormItemInput v-model="form.name" :label="'Название'" :error="page.props.errors.name"></FormItemInput>
                <FormItemSelect v-model="form.configurations_ids"
                                :label="'Конфигурации'"
                                :options="configurations"
                                :error="page.props.errors.configurations_ids"
                                :isMulti="true"
                ></FormItemSelect>
                
                <button @click="saveTariffForm" :disabled="form.processing"
                        class="block rounded-md bg-emerald-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-500 focus:outline-none cursor-pointer"
                >
                    Сохранить
                </button>
            </Modal>
            <div class="flex justify-between items-center">
                <button @click="form.reset(); isEditing = false; showAddTariffModal = true"
                        class="block rounded-md bg-blue-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500 focus:outline-none cursor-pointer text-left"
                >
                    Добавить тариф
                </button>
                <Link :href="page.props.deletedUrl" as="button" type="button" class="block rounded-md bg-red-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500 focus:outline-none cursor-pointer text-right">
                    Удалённые тарифы
                </Link>
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
                            <td :class="mainTableTdClasses">
                                <div :class="''">
                                    <component :class="'cursor-pointer mb-3'" :is="SquarePen" @click="openEditTariffForm(tariff.id)" />
                                    <component :class="'text-pink-600 cursor-pointer'" :is="Trash2" @click="deleteTariffModal(tariff.id)" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
