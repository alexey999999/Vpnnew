<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
// import NavUser from '@/components/NavUser.vue';
// import NavFooter from '@/components/NavFooter.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as serversIndex } from '@/routes/servers';
import { index as configurationsIndex } from '@/routes/connection-configurations';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Server, ListCollapse } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Панель управления',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Серверы',
        href: serversIndex(),
        icon: Server,
    },
    {
        title: 'Конфигурации',
        href: configurationsIndex(),
        icon: ListCollapse,
    },
];

// const footerNavItems: NavItem[] = [
//     {
//         title: 'Github Repo',
//         href: 'https://github.com/laravel/vue-starter-kit',
//         icon: Folder,
//     },
//     {
//         title: 'Documentation',
//         href: 'https://laravel.com/docs/starter-kits#vue',
//         icon: BookOpen,
//     },
// ];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
<!--            <NavFooter :items="footerNavItems" />-->
<!--            <NavUser />-->
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
