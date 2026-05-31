<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

import { index as userIndex } from '@/routes/user';
import { Head } from '@inertiajs/vue3';

import DataTable from '@/layouts/datatable/DataTable.vue';
import { type BreadcrumbItem } from '@/types';
import { SquarePen, Trash2 } from 'lucide-vue-next';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Maintenance',
        href: userIndex().url,
    },
];
const columns = [
    { label: 'Name', key: 'name' },
    { label: 'Email', key: 'email' },
    { label: 'Role', key: 'role' },
    { label: 'Actions', key: 'actions' },
];

const users = [
    { id: 1, name: 'John', email: 'john@test.com', role: 'Admin' },
    { id: 2, name: 'Jane', email: 'jane@test.com', role: 'User' },
];

const editUser = (user) => {
    console.log('Edit', user);
};
const createUser = () => {
    console.log('Create User');
};
</script>

<template>
    <Head title="User Maintenance" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6">
            <Heading
                title="User Maintenance"
                description="Manage user accounts"
            />

            <DataTable
                @create="createUser"
                :columns="columns"
                :rows="users"
                row-key="id"
                :button-text="'User'"
            >
                <!-- Custom Actions Column -->
                <template #cell-actions="{ row }">
                    <Button class="cursor-pointer" variant="outline" size="sm">
                        <SquarePen class="text-primary" />
                    </Button>
                    &nbsp;
                    <Button class="cursor-pointer" variant="outline" size="sm">
                        <Trash2 class="text-destructive" />
                    </Button>
                </template>
            </DataTable>
        </div>
    </AppLayout>
</template>
