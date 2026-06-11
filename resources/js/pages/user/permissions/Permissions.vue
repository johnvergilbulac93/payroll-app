<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

import { Head, router, useForm } from '@inertiajs/vue3';

import DataTable from '@/components/DataTable.vue';
import { Filter, type BreadcrumbItem } from '@/types';
import { h, reactive, ref } from 'vue'
import { ColumnDef } from '@tanstack/vue-table';
import DragHandle from '@/components/DragHandle.vue';
import debounce from 'lodash/debounce';
import { destroy, index, store, update } from '@/routes/users/roles';
import { IconCircleCheckFilled, IconDotsVertical, IconXboxXFilled } from '@tabler/icons-vue';
import moment from 'moment';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import AlertDialog from '@/layouts/dialog/Dialog.vue';
import Dialog from '@/usable/Dialog.vue';
import { toast } from 'vue-sonner';
import { SquarePen, Trash2, User2 } from '@lucide/vue';
import UserLayout from '@/layouts/user/UserLayout.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Switch } from '@/components/ui/switch';
import InputError from '@/components/InputError.vue';
import { IconUserShield } from '@tabler/icons-vue';
import {
    Tabs,
    TabsContent,
    TabsList,
    TabsTrigger,
} from '@/components/ui/tabs'
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Management',
        href: index().url,
    },
];
const props = withDefaults(defineProps<{
    permissions: any[]
    filter: Filter
}>(), {
})
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Permissions" />
        <h1 class="sr-only">Permissions</h1>
        <UserLayout>
            <div class="px-4">
                <Heading
                    title="Permissions"
                    description="Manage user permissions"
                />
                <Tabs default-value="user_permission">
                    <TabsList>
                        <TabsTrigger
                            value="user_permission"
                            class="cursor-pointer"
                        >
                            <IconUserShield />
                            User Permission
                        </TabsTrigger>
                        <TabsTrigger
                            value="role_permission"
                            class="cursor-pointer"
                        >
                            <IconUserShield />
                            Role
                            Permission
                        </TabsTrigger>
                    </TabsList>
                    <TabsContent value="user_permission">
                        User Permission Here
                    </TabsContent>
                    <TabsContent value="role_permission">
                        Role Permission Here
                    </TabsContent>
                </Tabs>
            </div>
        </UserLayout>
    </AppLayout>
</template>
