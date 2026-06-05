<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    create as employeeCreate,
    destroy as employeeDestroy,
    index as employeeIndex,
    show as employeeShow,
} from '@/routes/employees';

import {
    create as loanCreate,
    destroy as loanDestroy,
    index as loanIndex,
    show as loanShow,
} from '@/routes/loan';

import { Head, router } from '@inertiajs/vue3';

import DataTable from '@/components/DataTable.vue';
import Dialog from '@/layouts/Dialog/Dialog.vue';
import Pagination from '@/layouts/pagination/Pagination.vue';
import { type BreadcrumbItem } from '@/types';
import debounce from 'lodash.debounce';
import { Check, SquarePen, Trash2 } from '@lucide/vue';
import { h, reactive, ref } from 'vue';
import { toast } from 'vue-sonner';
import moment from 'moment';
import { Loan } from '@/types/loans';
import { ColumnDef } from '@tanstack/vue-table';
import DragHandle from '@/components/DragHandle.vue';
import { Checkbox } from '@/components/ui/checkbox';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Employee Loan Maintenance',
        href: loanIndex().url,
    },
];
const columns: ColumnDef<Loan>[] = [
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
        accessorKey: "loan_type.LoanType",
        header: "Loan Type",
        enableHiding: false,
    },
    {
        accessorKey: "EmpNbr",
        header: "Emp. No.",
        enableHiding: false,
    },
    {
        accessorKey: "employee.FullName",
        header: "Emp. Name",
    },
    {
        accessorKey: "OrigBal",
        header: "Orig. Balance",
        cell: ({ row }) => {
            return formatCurrency(row.getValue("OrigBal") ?? 0)
        }
    },
    {
        accessorKey: "DedAmt",
        header: "Deduction Amt.",
        cell: ({ row }) => {
            return formatCurrency(row.getValue("DedAmt") ?? 0)
        }
    },
    {
        accessorKey: "StartDate",
        header: "Start Ded. Date",
        cell: ({ row }) => {
            return moment(row.getValue('StartDate')).format('LL');
        }
    },
    {
        accessorKey: "Frequency",
        header: "Frequency",
    },
    {
        accessorKey: "BalanceAmt",
        header: "Balance Amt.",
        cell: ({ row }) => {
            return formatCurrency(row.getValue("BalanceAmt") ?? 0)
        }
    },
    {
        accessorKey: "BalanceasofDate",
        header: "Balance as of Date",
        cell: ({ row }) => {
            return formatCurrency(row.getValue("BalanceasofDate") ?? 0)
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


const props = defineProps({ loans: Object, filter: Object, });
const form = reactive({
    search: props.filter?.search || '',
    page: 1,
    limit: 10,
});
const employeeID = ref('');
const visible = ref(false);
const filter = debounce(() => {
    router.get(loanIndex().url, form, {
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
    router.visit(loanShow(employee));
};
const newLoan = () => {
    router.get(loanCreate());
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

    <Head title="Employee Loan Maintenance" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6">
            <Heading title="Employee Loan Records" description="Manage employees loan record" />

            <DataTable @search="onSearch" @per-page="perPageChange" @add="newLoan" :button-text="'New Loan'"
                :data="loans" :columns="columns" />
            <Dialog v-model="visible" title="Confirm Deletion"
                message="Are you sure you want to delete this employee? This action cannot be undone."
                confirmText="Delete" cancelText="Cancel" @confirm="onConfirmDelete" />
        </div>
    </AppLayout>
</template>
