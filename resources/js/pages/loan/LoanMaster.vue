<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';

import AppLayout from '@/layouts/AppLayout.vue';

import {
    create as loanCreate,
    destroy as loanDestroy,
    index as loanIndex,
    show as loanShow,
} from '@/routes/loan';

import { Head, router } from '@inertiajs/vue3';

import DataTable from '@/components/DataTable.vue';
import Dialog from '@/layouts/dialog/Dialog.vue';
import { Filter, type BreadcrumbItem } from '@/types';
import debounce from 'lodash/debounce';
import { Check, SquarePen, Trash2 } from '@lucide/vue';
import { h, reactive, ref } from 'vue';
import { toast } from 'vue-sonner';
import moment from 'moment';
import { Loan } from '@/types/loans';
import { ColumnDef } from '@tanstack/vue-table';
import DragHandle from '@/components/DragHandle.vue';

import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { IconDotsVertical } from '@tabler/icons-vue';

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
                                router.visit(loanShow(row.original.public_id))
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
    loans: any[]
    filter: Filter
}>(), {
})
const form = reactive({
    search: props.filter?.search || '',
    page: 1,
    limit: 10,
});
const public_id = ref<string>('');
const visible = ref<boolean>(false);

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
    public_id.value = id;
};

const onConfirmDelete = () => {
    router.delete(loanDestroy(public_id.value), {
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
            <Heading
                title="Employee Loan Master"
                description="Manage employees loan record"
            />
            <DataTable
                @search="onSearch"
                @per-page="perPageChange"
                @add="router.visit(loanCreate())"
                :button-text="'New Loan'"
                :data="loans"
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
