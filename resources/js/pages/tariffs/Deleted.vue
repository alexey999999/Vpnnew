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

const showRestoreTariffModal = ref(false);
const showRestoreAllTariffsModal = ref(false);
const showFinallyDeleteTariffModal = ref(false);
const showFinallyDeleteAllTariffsModal = ref(false);

const restoreTariffData = ref({});
const finallyDeleteTariffData = ref({});

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

const restoreTariffModal = (tariffId) => {
    showRestoreTariffModal.value = true
    restoreTariffData.value = findTariffById(tariffId)
}

const restoreTariff = (isConfirmed) => {
    showRestoreTariffModal.value = false
    
    if (isConfirmed) {
        useForm({id: restoreTariffData.value.id}).put(page.props.restoreTariffUrl);
    }
}

const restoreAllServersModal = () => {
    showRestoreAllTariffsModal.value = true
}

const restoreAllTariffs = (isConfirmed) => {
    showRestoreAllTariffsModal.value = false
    
    if (isConfirmed) {
        useForm().put(page.props.restoreAllTariffsUrl);
    }
}

const finallyDeleteTariffModal = (tariffId) => {
    showFinallyDeleteTariffModal.value = true
    finallyDeleteTariffData.value = findTariffById(tariffId)
}

const finallyDeleteTariff = (isConfirmed) => {
    showFinallyDeleteTariffModal.value = false

    if (isConfirmed) {
        useForm({id: finallyDeleteTariffData.value.id}).delete(page.props.finallyDeleteTariffUrl);
    }
}

const finallyDeleteAllServersModal = () => {
    showFinallyDeleteAllTariffsModal.value = true
}

const finallyDeleteAllTariffs = (isConfirmed) => {
    showFinallyDeleteAllTariffsModal.value = false
    
    if (isConfirmed) {
        useForm().delete(page.props.finallyDeleteAllTariffsUrl);
    }
}

const findTariffById = (tariffId) => {
    return page.props.tariffs.find((tariff) => tariff.id == tariffId)
}
</script>

<template>
    <Head title="Удалённые тарифы" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ConfirmationModal :show="showRestoreTariffModal"
                               :title="'Вы действительно хотите восстановить тариф ' + restoreTariffData.name + '?'"
                               :message="'Тариф будет перемещена в список конфигураций'"
                               @result="restoreTariff"
            ></ConfirmationModal>
            <ConfirmationModal :show="showRestoreAllTariffsModal"
                               :title="'Вы действительно хотите восстановить ВСЕ тарифы?'"
                               :message="'ВНИМАНИЕ! ВСЕ!!! Тарифы будут восстановлены!'"
                               @result="restoreAllTariffs"
            ></ConfirmationModal>
            <ConfirmationModal :show="showFinallyDeleteTariffModal"
                               :title="'Вы действительно хотите БЕЗВОЗВРАТНО удалить тариф ' + finallyDeleteTariffData.name + '?'"
                               :message="'ВНИМАНИЕ! Тариф будет БЕЗВОЗВРАТНО удалён!'"
                               @result="finallyDeleteTariff"
            ></ConfirmationModal>
            <ConfirmationModal :show="showFinallyDeleteAllTariffsModal"
                               :title="'Вы действительно хотите БЕЗВОЗВРАТНО удалить ВСЕ тарифы?'"
                               :message="'ВНИМАНИЕ! ВСЕ!!! Тарифы будут БЕЗВОЗВРАТНО удалены!'"
                               @result="finallyDeleteAllTariffs"
            ></ConfirmationModal>
            <div>
                <button v-if="tariffs.length > 0"
                        @click="restoreAllServersModal"
                        class="inline-block rounded-md bg-blue-500/80 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500 focus:outline-none cursor-pointer"
                >
                    Восстановить все тарифы
                </button>
                <button v-if="tariffs.length > 0"
                        @click="finallyDeleteAllServersModal"
                        class="inline-block rounded-md bg-red-500/80 ml-3 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500 focus:outline-none cursor-pointer"
                >
                    БЕЗВОЗВРАТНО удалить все тарифы
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
                                    <component :class="'cursor-pointer mb-3'" :is="ArchiveRestore" @click="restoreTariffModal(tariff.id)" />
                                    <component :class="'text-pink-600 cursor-pointer'" :is="Trash2" @click="finallyDeleteTariffModal(tariff.id)" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
