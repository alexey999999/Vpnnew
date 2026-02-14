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

export interface BaseInterface {
    id: number;
    name: string;
}

export interface Server extends BaseInterface {
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

export interface ServerType extends BaseInterface {}

export interface ServerTypeForSelect extends Option {}

export interface Country extends BaseInterface {
    code: string;
}

export interface CountryForSelect extends Option {}

export interface Option {
    value: number;
    label: string;
}

export interface ConnectionConfiguration extends BaseInterface {
    configuration_type: ConfigurationType;
    servers_in: Server[];
    servers_out: Server[];
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
}

export interface ConfigurationType extends BaseInterface {}

export interface ConfigurationsTypeNames {
    shadowSocks: string;
    doubleVpn: string;
}

export interface ServerTypeNames {
    ss: string;
    vpnIo: string;
    vpnIn: string;
    vpnOut: string;
}

export interface ConfigurationTypeForSelect extends Option {}

export interface ServerForSelect extends Option {}

export interface Tariff extends BaseInterface {
    configurations: ConnectionConfiguration[];
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
}

export interface ConnectionConfigurationForSelect extends Option {}

export type BreadcrumbItemType = BreadcrumbItem;
