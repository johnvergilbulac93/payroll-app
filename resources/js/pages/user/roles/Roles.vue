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
import { SquarePen, Trash2 } from '@lucide/vue';
import UserLayout from '@/layouts/user/UserLayout.vue';
import { Role, RoleError } from '@/types/roles';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Switch } from '@/components/ui/switch';
import InputError from '@/components/InputError.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Management',
        href: index().url,
    },
];
const visible = ref<boolean>(false)
const isAdd = ref<boolean>(false)
const openRoleModal = ref<boolean>(false)
const publicId = ref<string>('')

const columns: ColumnDef<Role>[] = [
    {
        id: "id",
        header: () => null,
        cell: ({ row }) => h(DragHandle),
    },
    {
        accessorKey: "Description",
        header: "Role Name",
        enableHiding: false,
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
        accessorKey: "created_at",
        header: "Date Created",
        cell: ({ row }) => {
            return moment(row.getValue("created_at")).format('LLL');
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
                                onEdit(row.original)
                            },
                        }, () => [
                            h(SquarePen, { class: "w-4 h-4  " }),
                            "Edit"
                        ]),
                        h(DropdownMenuSeparator, {}),
                        h(DropdownMenuItem, {
                            class: "cursor-pointer text-red-500",
                            onSelect: () => {
                                onDelete(row.original.public_id)
                            },
                        }, () => [
                            h(Trash2, { class: "w-4 h-4  " }),
                            "Delete"
                        ]),
                    ],
                }),
            ],
        }),
    },
]
const role = useForm<Role>({
    public_id: '',
    Description: '',
    isActive: true,
})

const props = withDefaults(defineProps<{
    roles: any[]
    errors: RoleError
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
    publicId.value = id;

}
const onEdit = (row: Role) => {
    role.Description = row.Description
    role.isActive = row.isActive
    role.public_id = row.public_id
    openRoleModal.value = true
    isAdd.value = false
}
const onShowAddModalRole = () => {
    openRoleModal.value = true
    isAdd.value = true
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
const onSaveRole = () => {
    role.post(store().url, {
        preserveState: true,
        onSuccess: () => {
            role.reset()
            openRoleModal.value = false;
            toast.success('Success', {
                description: 'Role saved successfully.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
            });
        },
    });
}
const onUpdateRole = () => {
    role.put(update(role.public_id).url, {
        preserveState: true,
        onSuccess: () => {
            role.reset()
            openRoleModal.value = false;
            toast.success('Success', {
                description: 'Role saved successfully.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
            });
        },
    });
}

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="User Role" />
        <h1 class="sr-only">User Roles</h1>
        <UserLayout>
            <div class="px-4">
                <Heading
                    title="Roles"
                    description="Manage user role"
                />
                <DataTable
                    @search="onSearch"
                    @per-page="perPageChange"
                    :button-text="'New Role'"
                    :data="roles"
                    :columns="columns"
                    @add="onShowAddModalRole"
                />
                <AlertDialog
                    v-model="visible"
                    title="Confirmation"
                    :description="`Are you sure you want to delete this record? This action cannot be undone.`"
                    confirmText="Delete"
                    cancelText="Cancel"
                    @confirm="confirmDelete"
                />
                <Dialog
                    v-model:visible="openRoleModal"
                    :title="isAdd ? 'Add Role' : 'Update Role'"
                    description="Manage your new role"
                    @save="isAdd ? onSaveRole() : onUpdateRole()"
                >
                    <div class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="role_name">Role Name</Label>
                            <Input
                                v-model="role.Description"
                                name="role_name"
                                type="text"
                                placeholder="Description"
                            />
                            <InputError :message="props.errors.Description" />
                        </div>
                        <div class="grid gap-2">
                            <div class="flex items-center space-x-2">
                                <Switch
                                    id="status"
                                    v-model="role.isActive"
                                    class="cursor-pointer"
                                />
                                <Label
                                    for="status"
                                    class="cursor-pointer"
                                >Status</Label>
                            </div>
                        </div>

                    </div>
                </Dialog>
            </div>
        </UserLayout>
    </AppLayout>
</template>
