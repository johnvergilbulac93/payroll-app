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
import { create, destroy, index, resetPassword, show } from '@/routes/users/accounts';

import { IconCircleCheckFilled, IconDotsVertical, IconKey, IconTrash, IconUserShield, IconXboxXFilled } from '@tabler/icons-vue';
import moment from 'moment';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import AlertDialog from '@/layouts/dialog/Dialog.vue';
import { toast } from 'vue-sonner';
import { SquarePen } from '@lucide/vue';
import UserLayout from '@/layouts/user/UserLayout.vue';
import Dialog from '@/usable/Dialog.vue';
import PermissionTree from '@/usable/PermissionTree.vue';
import axios from 'axios';
import { index as userPermissionIndex, update as updateUserPermission } from '@/routes/users/permissions/users/index'
import { IconLoader2 } from '@tabler/icons-vue';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Management',
        href: index().url,
    },
];
const visible = ref<boolean>(false)
const isDelete = ref<boolean>(false)
const publicId = ref<string>('')
const showPermissionModal = ref<boolean>(false)
const loading = ref<boolean>(false)
const selectedPermissions = ref<number[]>([])

const columns: ColumnDef<User>[] = [
    {
        id: "id",
        header: () => null,
        cell: ({ row }) => h(DragHandle),
    },
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
                                router.visit(show(row.original.public_id))
                            },
                        }, () => [
                            h(SquarePen, { class: "w-4 h-4  " }),
                            "Edit"
                        ]),

                        h(DropdownMenuItem, {
                            class: "cursor-pointer",
                            onSelect: () => {
                                onReset(row.original.public_id)
                            },
                        }, () => [h(IconKey, { class: "w-4 h-4  " }), "Reset Password"]),
                        h(DropdownMenuItem, {
                            class: "cursor-pointer",
                            onSelect: () => {
                                onPermission(row.original.public_id)
                            },
                        }, () => [h(IconUserShield, { class: "w-4 h-4  " }), "User Permission"]),
                        h(DropdownMenuItem, {
                            class: "cursor-pointer text-red-500",
                            onSelect: () => {
                                onDelete(row.original.public_id)
                            },
                        }, () => [h(IconTrash, { class: "w-4 h-4  " }), "Delete"]),
                    ],
                }),
            ],
        }),
    },
]
interface Permission {
    id: number
    name: string
    slug?: string
    children?: Permission[]
}
const permissionTree = ref<Permission[]>([])
const props = withDefaults(defineProps<{
    users: any[]
    filter: Filter,
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
    router.get(create());
};
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
    isDelete.value = true
    publicId.value = id;
}
const onReset = (id: string) => {
    visible.value = true
    isDelete.value = false
    publicId.value = id
}
const onPermission = (id: string) => {
    getPermissionPerUser(id)
    publicId.value = id
    showPermissionModal.value = true;
}
const getPermissionPerUser = async (id: string) => {
    loading.value = true
    try {
        const { data } = await axios.get(userPermissionIndex(id).url)
        permissionTree.value = data.data
        loading.value = false
    } catch (error) {
        loading.value = false
    }
}
const confirmDelete = () => {
    router.delete(destroy(publicId.value), {
        preserveState: true,
        onSuccess: () => {
            visible.value = false;
            toast.success('Success', {
                description: 'User deleted successfully.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
            });
        },
    });
}
const onSavePermission = () => {
    router.put(updateUserPermission(publicId.value), { permission_ids: selectedPermissions.value }, {
        preserveState: true,
        onSuccess: () => {
            visible.value = false;
            showPermissionModal.value = false
            toast.success('Success', {
                description: 'Permission updated successfully.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
            });
        },
    });
}
const confirmResetPassword = () => {
    router.patch(resetPassword(publicId.value), {}, {
        preserveState: true,
        onSuccess: () => {
            visible.value = false;
            toast.success('Success', {
                description: 'User password has been successfully reset.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
            });

        },
    });
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="User Accounts" />
        <h1 class="sr-only">User Management</h1>
        <UserLayout>
            <div class="px-4">
                <Heading
                    title="Accounts"
                    description="Manage user accounts"
                />
                <DataTable
                    @search="onSearch"
                    @per-page="perPageChange"
                    @add="createUser"
                    :button-text="'New User'"
                    :data="users"
                    :columns="columns"
                />
                <AlertDialog
                    v-model="visible"
                    title="Confirmation"
                    :description="`Are you sure you want to ${isDelete ? 'delete' : 'reset the password to'} this user? This action cannot be undone.`"
                    confirmText="Delete"
                    cancelText="Cancel"
                    @confirm="isDelete ? confirmDelete() : confirmResetPassword()"
                />
                <Dialog
                    v-model:visible="showPermissionModal"
                    title="Update User Permission"
                    description="Manage your user permission"
                    @save="onSavePermission"
                >
                    <div class="h-64 overflow-y-auto p-2">
                        <div
                            class="h-full flex items-center justify-center"
                            v-if="loading"
                        >
                            <IconLoader2 class="animate-spin h-14" />
                        </div>
                        <PermissionTree
                            v-else
                            v-model="selectedPermissions"
                            :data="permissionTree"
                        />
                    </div>

                </Dialog>
            </div>
        </UserLayout>

    </AppLayout>
</template>
