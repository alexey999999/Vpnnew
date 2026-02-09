import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    appName: string;
    appUrl: string;
    // quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Server {
    id: number;
    name: string;
    server_type: ServerType;
    protocol_version: number;
    ipv4: string;
    country: Country;
    url: string;
    main_token: string;
    remote_token: string;
    current_load: number;
    avg_load: number;
    port?: string;
    password?: string;
    encryption_method?: string;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
}

export interface ServerType {
    id: number;
    name: string;
}

export interface ConnectionConfiguration {
    id: number;
    name: string;
    configuration_type: ConfigurationType;
    servers_in: Server[];
    servers_out: Server[];
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
}

// TODO: extends from base interface
export interface ConfigurationType {
    id: number;
    name: string;
}

export interface ServerTypeForSelect extends Option {}

export interface Country {
    id: number;
    code: string;
    name: string;
}

export interface CountryForSelect extends Option {}

export interface Option {
    value: number;
    label: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
