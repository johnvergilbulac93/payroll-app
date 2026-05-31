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

import { Head, router } from '@inertiajs/vue3';

import DataTable from '@/layouts/datatable/DataTable.vue';
import Dialog from '@/layouts/Dialog/Dialog.vue';
import Pagination from '@/layouts/pagination/Pagination.vue';
import { type BreadcrumbItem } from '@/types';
import debounce from 'lodash.debounce';
import { Check, SquarePen, Trash2 } from 'lucide-vue-next';
import { h, reactive, ref } from 'vue';
import { toast } from 'vue-sonner';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Employee 201',
        href: employeeIndex().url,
    },
];
const columns = [
    { label: 'Employee Number', key: 'EmpNbr' },
    { label: 'Full Name', key: 'FullName' },
    { label: 'Position', key: 'Position' },
    { label: 'Area of Assignment', key: 'Assignment' },
    { label: 'Salary Grade', key: 'SalaryGrade' },
    { label: 'Basic Pay', key: 'BasicPay' },
    { label: 'Actions', key: 'actions' },
];

const props = defineProps({ employees: Object, filter: Object });
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
            <Heading
                title="Employee 201 Master List"
                description="Manage Employee 201 records"
            />

            <DataTable
                @create="createEmployee"
                @search="onSearch"
                :columns="columns"
                :rows="employees?.data"
                row-key="id"
                button-text="Employee"
            >
                <template #cell-BasicPay="{ row }">
                    {{ formatCurrency(row.BasicPay) }}
                </template>
                <!-- Custom Actions Column -->
                <template #cell-actions="{ row }">
                    <TooltipProvider>
                        <Tooltip>
                            <TooltipTrigger as-child>
                                <Button
                                    class="cursor-pointer"
                                    variant="outline"
                                    size="sm"
                                    @click="onEdit(row.id)"
                                >
                                    <SquarePen class="text-primary" />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>Edit</p>
                            </TooltipContent>
                        </Tooltip>
                    </TooltipProvider>

                    &nbsp;

                    <TooltipProvider>
                        <Tooltip>
                            <TooltipTrigger as-child>
                                <Button
                                    class="cursor-pointer"
                                    variant="outline"
                                    size="sm"
                                    @click="onDelete(row.id)"
                                >
                                    <Trash2 class="text-destructive" />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>Delete</p>
                            </TooltipContent>
                        </Tooltip>
                    </TooltipProvider>
                </template>
            </DataTable>
            <Pagination
                :pagination="employees"
                @per-page-change="perPageChange"
            />
            <Dialog
                v-model="visible"
                title="Confirm Deletion"
                message="Are you sure you want to delete this employee? This action cannot be undone."
                confirmText="Delete"
                cancelText="Cancel"
                @confirm="onConfirmDelete"
            />
        </div>
    </AppLayout>
</template>
