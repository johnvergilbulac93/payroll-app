<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';

import AppLayout from '@/layouts/AppLayout.vue';
import {
    create as employeeCreate,
    destroy as employeeDestroy,
    index as employeeIndex,
    show as employeeShow,
} from '@/routes/employees';

import { Head, router } from '@inertiajs/vue3';
import Dialog from '@/layouts/dialog/Dialog.vue';
import { Filter, type BreadcrumbItem } from '@/types';
import debounce from 'lodash/debounce';
import { SquarePen, Trash2, } from '@lucide/vue';
import { h, reactive, ref } from 'vue';
import { toast } from 'vue-sonner';
import { Employee } from '@/types/employee';
import { ColumnDef } from '@tanstack/vue-table';
import DragHandle from '@/components/DragHandle.vue';
import DataTable from '@/components/DataTable.vue';
import { IconCircleCheckFilled, IconXboxXFilled, IconDotsVertical } from '@tabler/icons-vue';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '@/components/ui/dropdown-menu';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Employee 201',
        href: employeeIndex().url,
    },
];
const columns: ColumnDef<Employee>[] = [
    {
        id: "id",
        header: () => null,
        cell: () => h(DragHandle),
    },
    {
        accessorKey: "EmpNbr",
        header: "Employee Number",
        enableHiding: false,
    },
    {
        accessorKey: "FullName",
        header: "Full Name",
        enableHiding: false,
    },
    {
        accessorKey: "Position",
        header: "Position",
    },
    {
        accessorKey: "Area of Assignment",
        header: "Assignment",
    },
    {
        accessorKey: "SalaryGrade",
        header: "Salary Grade",
    },
    {
        accessorKey: "BasicPay",
        header: "Basic Pay",
        cell: ({ row }) => {
            return formatCurrency(row.getValue("BasicPay") ?? 0)
        }
    },
    {
        accessorKey: "Status",
        header: "Status",
        cell: ({ row }) => {
            const status = row.getValue("Status")

            return h("div", { class: "flex items-center gap-2" }, [
                status == 1
                    ? h(IconCircleCheckFilled, { class: "h-4 w-4 text-emerald-500" })
                    : h(IconXboxXFilled, { class: "h-4 w-4 text-red-500" }),
                h("span", { class: 'text-sm' }, status ? 'Active' : 'Inactive'),
            ])
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
                                onEdit(row.original.public_id)
                            },
                        }, () => [
                            h(SquarePen, { class: "w-4 h-4  " }),
                            "Edit"
                        ]),
                        h(DropdownMenuItem, {
                            class: "cursor-pointer text-red-500",
                            onSelect: () => {
                                onDelete(row.original.public_id)
                            },
                        }, () => [h(Trash2, { class: "w-4 h-4  " }), "Delete"]),
                    ],
                }),
            ],
        }),
    },
]

const props = withDefaults(defineProps<{
    employees: any[]
    filter: Filter
}>(), {
})

const form = reactive({
    search: props.filter?.search || '',
    page: 1,
    limit: 10,
});
const visible = ref<boolean>(false);
const publicId = ref<string>('');

const filter = debounce(() => {
    router.get(employeeIndex().url, form, {
        preserveState: true,
        replace: true,
    });
}, 400);

const formatCurrency = (value: number | string) => {
    if (value === null || value === undefined) return '₱0.00';

    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number(value));
};

const onEdit = (id: string) => {
    router.visit(employeeShow(id));
};
const createEmployee = () => {
    router.get(employeeCreate());
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
    publicId.value = id;
};

const onConfirmDelete = () => {
    router.delete(employeeDestroy(publicId.value), {
        preserveState: true,
        onSuccess: () => {
            visible.value = false;
            toast.success('Success', {
                description: 'Employee deleted successfully.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
            });
        },
    });
};
</script>

<template>

    <Head title="Employee 201" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6">
            <Heading
                title="Employee 201 Master"
                description="Manage employees 201 record"
            />

            <DataTable
                @search="onSearch"
                @per-page="perPageChange"
                @add="createEmployee"
                :button-text="'New Employee'"
                :data="employees"
                :columns="columns"
            />

            <Dialog
                v-model="visible"
                title="Confirmation"
                description="Are you sure you want to delete this employee? This action cannot be undone."
                confirmText="Delete"
                cancelText="Cancel"
                @confirm="onConfirmDelete"
            />
        </div>
    </AppLayout>
</template>
