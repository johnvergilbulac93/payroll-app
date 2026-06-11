<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

import { Head, router } from '@inertiajs/vue3';

import DataTable from '@/components/DataTable.vue';
import { Filter, type BreadcrumbItem } from '@/types';
import { h, reactive, ref } from 'vue'
import { ColumnDef } from '@tanstack/vue-table';
import { User } from '@/types/user';
import DragHandle from '@/components/DragHandle.vue';
import debounce from 'lodash/debounce';
import { destroy, index, restore } from '@/routes/users/archives';
import { IconCircleCheckFilled, IconDotsVertical, IconXboxXFilled } from '@tabler/icons-vue';
import moment from 'moment';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import Dialog from '@/layouts/dialog/Dialog.vue';
import { toast } from 'vue-sonner';
import { Check } from '@lucide/vue';
import UserLayout from '@/layouts/user/UserLayout.vue';
import { index as accountsIndex } from '@/routes/users/accounts';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Management',
        href: index().url,
    },
];
const visible = ref<boolean>(false)
const isDelete = ref<boolean>(false)
const publicId = ref<string>('')

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
        accessorKey: "isActive",
        header: "Status",
        cell: ({ row }) => {
            const status = row.getValue("isActive")
            return h("div", { class: "flex items-center gap-2" }, [
                status == 1
                    ? h(IconCircleCheckFilled, { class: "h-4 w-4 text-emerald-500" })
                    : h(IconXboxXFilled, { class: "h-4 w-4 text-red-500" }),
                h("span", { class: 'text-sm' }, status == 1 ? 'Active' : 'Inactive'),
            ])
        },
    },
    {
        accessorKey: "deleted_at",
        header: "Date Deleted",
        cell: ({ row }) => {
            return moment(row.getValue("deleted_at")).format('LLL');
        },
    },

    {
        id: "actions",
        cell: ({ row }) => h(DropdownMenu, {}, {
            default: () => [
                h(DropdownMenuTrigger, { asChild: true }, {
                    default: () => h(Button, {
                        variant: "ghost",
                        class: "h-8 w-8 p-0 cursor-pointer",
                    }, {
                        default: () => [
                            h("span", { class: "sr-only" }, "Open menu"),
                            h(IconDotsVertical, { class: "h-4 w-4 " }),
                        ],
                    }),
                }),
                h(DropdownMenuContent, { align: "end" }, {
                    default: () => [
                        h(DropdownMenuItem, {
                            class: "cursor-pointer",
                            onSelect: () => {
                                onRestore(row.original.public_id)
                            },
                        }, () => "Restore User"),
                        h(DropdownMenuSeparator, {}),
                        h(DropdownMenuItem, {
                            class: "cursor-pointer text-red-500",
                            onSelect: () => {
                                onDelete(row.original.public_id)
                            },
                        }, () => "Delete"),
                    ],
                }),
            ],
        }),
    },
]

const props = withDefaults(defineProps<{
    users: any[]
    filter: Filter
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
const perPageChange = (value: number) => {
    form.limit = value;
    filter();
};
const onSearch = (value: string) => {
    form.search = value;
    filter();
};
const onDelete = (id: string) => {
    visible.value = true;
    isDelete.value = true;
    publicId.value = id;

}
const onRestore = (id: string) => {
    visible.value = true;
    isDelete.value = false;
    publicId.value = id;
}
const confirmRestore = () => {
    router.post(restore(publicId.value), {}, {
        preserveState: true,
        onSuccess: () => {
            visible.value = false;
            toast.success('Success.', {
                description: 'Click continue to view the user in Accounts.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
                closeButton: true,
                duration: 5000, //5 sec duration
                action: {
                    label: 'Continue',
                    onClick: () => router.visit(accountsIndex().url),
                },
            });
        },
    });
}
const confirmDelete = () => {
    router.delete(destroy(publicId.value), {
        preserveState: true,
        onSuccess: () => {
            visible.value = false;
            toast.success('Success', {
                description: 'User was permamently deleted in the database.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
            });
        },
    });
}

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="User Archives" />
        <h1 class="sr-only">User Archives</h1>
        <UserLayout>
            <div class="px-4">
                <Heading
                    title="Archives"
                    description="Manage user archives"
                />
                <DataTable
                    @search="onSearch"
                    @per-page="perPageChange"
                    :button-text="'New User'"
                    :data="users"
                    :columns="columns"
                    :allow-add="false"
                />
                <Dialog
                    v-model="visible"
                    title="Confirmation"
                    :description="`Are you sure you want to ${isDelete ? 'delete permamently' : 'restore'} this record? This action cannot be undone.`"
                    confirmText="Delete"
                    cancelText="Cancel"
                    @confirm=" isDelete ? confirmDelete() : confirmRestore()"
                />
            </div>
        </UserLayout>
    </AppLayout>
</template>
