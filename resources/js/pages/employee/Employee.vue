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
import Dialog from '@/layouts/Dialog/Dialog.vue';
import { type BreadcrumbItem } from '@/types';
import debounce from 'lodash.debounce';
import { Check, SquarePen, Trash2 } from '@lucide/vue';
import { h, reactive, ref } from 'vue';
import { toast } from 'vue-sonner';
import { Employee } from '@/types/employee';
import { ColumnDef } from '@tanstack/vue-table';
import DragHandle from '@/components/DragHandle.vue';
import { Checkbox } from '@/components/ui/checkbox';
import DataTable from '@/components/DataTable.vue';

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
    employees: any[]
    filter: any[]
    groups: any[]
}>(), {
})

const form = reactive({
    search: props.filter?.search || '',
    page: 1,
    limit: 10,
});
const employeeID = ref('');
const visible = ref(false);
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

const onEdit = (employee) => {
    router.visit(employeeShow(employee));
};
const createEmployee = () => {
    router.get(employeeCreate());
};
const perPageChange = (value) => {
    form.limit = value;
    filter();
};

const onSearch = (value: string) => {
    form.search = value;
    filter();
};
const onDelete = (id) => {
    visible.value = true;
    employeeID.value = id;
};

const onConfirmDelete = () => {
    router.delete(employeeDestroy(employeeID.value), {
        preserveState: true,
        onSuccess: () => {
            visible.value = false;
            toast.success('Successfully Deleted. ', {
                icon: () => h(Check, { class: 'h-5 w-5 text-green-500' }), // correct way
            });
        },
    });
};
</script>

<template>

    <Head title="Employee 201" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6">
            <Heading title="Employee 201 Master List" description="Manage Employee 201 records" />

            <DataTable @search="onSearch" @per-page="perPageChange" @add="createEmployee" :button-text="'New Employee'"
                :data="employees" :columns="columns" />

            <Dialog v-model="visible" title="Confirm Deletion"
                message="Are you sure you want to delete this employee? This action cannot be undone."
                confirmText="Delete" cancelText="Cancel" @confirm="onConfirmDelete" />
        </div>
    </AppLayout>
</template>
