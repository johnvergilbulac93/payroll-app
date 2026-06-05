<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

import { Head, router } from '@inertiajs/vue3';

import DataTable from '@/components/DataTable.vue';
import { type BreadcrumbItem } from '@/types';
import { SquarePen, Trash2 } from '@lucide/vue';
import { h, reactive } from 'vue'
import { ColumnDef } from '@tanstack/vue-table';
import { User } from '@/types';
import DragHandle from '@/components/DragHandle.vue';
import debounce from 'lodash.debounce';
import { index } from '@/routes/user';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Maintenance',
        href: index().url,
    },
];

const onEdit = (id) => {
    console.log(id)
}
const onDelete = (id) => {
    console.log(id)
}

const columns: ColumnDef<User>[] = [
    {
        id: "id",
        header: () => null,
        cell: ({ row }) => h(DragHandle),
    },
    // {
    //     id: "select",
    //     header: ({ table }) => h(Checkbox, {
    //         "modelValue": table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && "indeterminate"),
    //         "onUpdate:modelValue": value => table.toggleAllPageRowsSelected(!!value),
    //         "aria-label": "Select all",
    //     }),
    //     cell: ({ row }) => h(Checkbox, {
    //         "modelValue": row.getIsSelected(),
    //         "onUpdate:modelValue": value => row.toggleSelected(!!value),
    //         "aria-label": "Select row",
    //     }),
    //     enableSorting: false,
    //     enableHiding: false,
    // },
    {
        accessorKey: "name",
        header: "Name",
        enableHiding: false,
    },
    {
        accessorKey: "email",
        header: "Email Address",
    },
    {
        accessorKey: "status",
        header: "Status",
    },
    {
        accessorKey: "created_at",
        header: "Date Created",
    },
    {
        id: "actions",
        header: "Actions",
        cell: ({ row }) =>
            h("div", { class: "flex gap-2" }, [
                h(Button, {
                    variant: "outline",
                    class: "cursor-pointer",
                    onClick: () => { onEdit(row.original.id) },
                }, {
                    default: () => [h(SquarePen, { class: 'text-blue-500' })],
                }),

                h(Button, {
                    variant: "outline",
                    class: "cursor-pointer",
                    onClick: () => { onDelete(row.original.id) },
                }, {
                    default: () => [h(Trash2, { class: 'text-red-500' })],
                }),
            ]),
    }
]
const props = withDefaults(defineProps<{
    users: any[]
    filter: any[]
}>(), {
})
const form = reactive({
    search: props.filter?.search || '',
    page: 1,
    limit: 10,
});
const filter = debounce(() => {
    router.get(index().url, form, {
        preserveState: true,
        replace: true,
    });
}, 400);
const createUser = () => {
    router.get('te');
};
const perPageChange = (value) => {
    form.limit = value;
    filter();
};

const onSearch = (value: string) => {
    form.search = value;
    filter();
};

</script>

<template>

    <Head title="User Maintenance" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6">
            <Heading title="User Maintenance" description="Manage user accounts" />

            <DataTable @search="onSearch" @per-page="perPageChange" @add="createUser" :button-text="'New User'"
                :data="users" :columns="columns" />


        </div>
    </AppLayout>
</template>
