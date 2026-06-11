import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from '@lucide/vue';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}
export interface Children 
{
    Label: string;
    Url: NonNullable<InertiaLinkProps['href']>;
}
export interface NavItem {
    title: string;
    tooltip?: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}
export interface NavItem2 {
    Label: string;
    Url: NonNullable<InertiaLinkProps['href']>;
    Icon?: LucideIcon;
    Children: Children[]
}
export interface Filter {
    search : string
}
export interface Helper {
    value: string | number
    label: string
}



export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
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

export type BreadcrumbItemType = BreadcrumbItem;
