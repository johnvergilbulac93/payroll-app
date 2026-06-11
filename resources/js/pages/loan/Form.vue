<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

import {
    NumberField,
    NumberFieldContent,
    NumberFieldDecrement,
    NumberFieldIncrement,
    NumberFieldInput,
} from '@/components/ui/number-field';

import AppLayout from '@/layouts/AppLayout.vue';
import DatePicker from '@/layouts/datepicker/DatePicker.vue';
import { create, index, store, update } from '@/routes/loan';
import { type BreadcrumbItem } from '@/types';
import { stringToDateValue } from '@/utils/date';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Check, LucideFileQuestion, Plus, Router } from '@lucide/vue';
import { h, onMounted, ref } from 'vue';
import { toast } from 'vue-sonner';
import Tooltip from '@/usable/Tooltip.vue';
import Dialog from '@/usable/Dialog.vue';
import ComboboxServerSideSearch from '@/usable/ComboboxServerSideSearch.vue';
import { index as loanTypeIndex, store as loanTypeStore } from '@/routes/helpers/loanType';
import { index as employeeIndex } from '@/routes/helpers/employees';
import { Loan, LoanError } from '@/types/loans';
import { LoanType } from '@/types/loantype'

import axios from 'axios';
import debounce from 'lodash/debounce';
import { IconCircleCheckFilled } from '@tabler/icons-vue';
const form = useForm<Loan>({
    id: '',
    public_id: '',
    EmpNbr: '',
    LoanTypeID: '',
    OrigBal: 0,
    DedAmt: 0,
    StartDate: '',
    Frequency: '',
    BalanceAmt: 0,
    BalanceasofDate: 0,
});
const props = defineProps<{
    errors: LoanError;
    loans: Loan;
    loanTypes: LoanType;
}>();
const loanType = useForm<LoanType>({
    LoanType: '',
    Route: 'loan.create'
});
onMounted(() => {
    isAdd.value = !props.loans ? true : false;
    if (props.loans) {
        form.public_id = props.loans.public_id;
        form.EmpNbr = props.loans.EmpNbr;
        form.LoanTypeID = props.loans.LoanTypeID;
        form.OrigBal = props.loans.OrigBal;
        form.DedAmt = props.loans.DedAmt;
        form.StartDate = props.loans.StartDate;
        form.Frequency = props.loans.Frequency;
        form.BalanceAmt = props.loans.BalanceAmt;
        form.BalanceasofDate = props.loans.BalanceasofDate;

    }
});
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Employee Loan Record',
        href: create().url,
    },
];



const isAdd = ref(false);
const isLoading = ref(false);
const isLoadingSearch = ref(false);
const visible = ref(false);

const loanTypes = ref([]);
const employees = ref([]);

const onSave = () => {
    form.post(store().url, {
        preserveState: true,
        onBefore: () => {
            isLoading.value = true;
        },
        onSuccess: () => {
            isLoading.value = false;
            form.reset();
            toast.success('Success', {
                description: 'Employee loan saved successfully.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
            });
        },
        onError: () => {
            isLoading.value = false;
        },
    });
};
const onUpdate = () => {
    form.put(update(form.public_id).url, {
        preserveState: true,
        onBefore: () => {
            isLoading.value = true;
        },
        onSuccess: () => {
            isLoading.value = false;
            form.reset();
            toast.success('Success', {
                description: 'Employee loan updated successfully.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
            });
        },
        onError: () => {
            isLoading.value = false;
        },
    });
};

const handleCancel = () => {
    router.get(index());
};
const showLoanTypeDialog = () => {
    visible.value = true;
};
const onSaveLoanType = () => {
    loanType.post(loanTypeStore().url, {
        onSuccess: () => {
            loanType.reset('LoanType');
            visible.value = false;
            toast.success('Success', {
                description: 'Loan type saved successfully.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
            });
        },
    });
};
const onSearchEmployee = debounce(async (query: string) => {
    isLoadingSearch.value = true
    try {
        const response = await axios.get(employeeIndex().url, { params: { search: query } });
        employees.value = response.data
    } finally {
        isLoadingSearch.value = false
    }
}, 400);

const onSearchLoanType = debounce(async (query: string) => {
    isLoadingSearch.value = true
    try {
        const response = await axios.get(loanTypeIndex().url, { params: { search: query } });
        loanTypes.value = response.data
    } finally {
        isLoadingSearch.value = false
    }
}, 400);

onMounted(() => {
    onSearchLoanType('');
    onSearchEmployee('');
});
</script>

<template>

    <Head :title="isAdd ? 'New Employee Loan' : 'Update Employee Loan'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6">
            <div class="flex items-center justify-between">
                <Heading
                    :title="isAdd ? ' New Employee Loan' : 'Update Employee Loan'"
                    :description="isAdd
                        ? 'Manage a new employee loan'
                        : 'Update selected employee loan'
                        "
                />
                <Button
                    class="cursor-pointer"
                    type="button"
                    @click="router.visit(index())"
                >
                    <ArrowLeft /> Back
                </Button>
            </div>

            <div class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-4 ">
                    <div class="grid grid-rows-[auto_auto_20px] gap-2 ">
                        <Label for="employee_number">Loan Type</Label>
                        <div class="flex items-center gap-2 w-52.5">
                            <ComboboxServerSideSearch
                                :loading="isLoadingSearch"
                                @search="onSearchLoanType"
                                :items="loanTypes"
                                v-model="form.LoanTypeID"
                                :labelText="'Select a Loan Type'"
                            />
                            <Tooltip description="Add Loan Type">
                                <Button
                                    @click="showLoanTypeDialog"
                                    variant="outline"
                                    class="cursor-pointer"
                                >
                                    <LucideFileQuestion class="text-[#1549e6]" />
                                </Button>
                            </Tooltip>
                        </div>
                        <InputError :message="props.errors.LoanTypeID" />
                    </div>
                    <div class="grid grid-rows-[auto_auto_20px] gap-2 col-span-3 ">
                        <Label for="employee_number">Employee Name</Label>
                        <div class="w-64">
                            <ComboboxServerSideSearch
                                :loading="isLoadingSearch"
                                @search="onSearchEmployee"
                                :items="employees"
                                v-model="form.EmpNbr"
                                :labelText="'Select a employee'"
                            />
                        </div>
                        <InputError :message="props.errors.EmpNbr" />
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-3">
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="original_balance">Original Balance</Label>
                        <NumberField
                            v-model="form.OrigBal"
                            class="mt-1 block w-full"
                            id="original_balance"
                            :step="0.01"
                            :locale="'en-PH'"
                            :format-options="{
                                style: 'currency',
                                currency: 'PHP',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            }"
                        >
                            <NumberFieldContent>
                                <NumberFieldDecrement />
                                <NumberFieldInput />
                                <NumberFieldIncrement />
                            </NumberFieldContent>
                        </NumberField>
                        <InputError :message="errors.OrigBal" />
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="deduction_amount">Deduction Amount</Label>
                        <NumberField
                            v-model="form.DedAmt"
                            class="mt-1 block w-full"
                            id="deduction_amount"
                            :step="0.01"
                            :locale="'en-PH'"
                            :format-options="{
                                style: 'currency',
                                currency: 'PHP',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            }"
                        >
                            <NumberFieldContent>
                                <NumberFieldDecrement />
                                <NumberFieldInput />
                                <NumberFieldIncrement />
                            </NumberFieldContent>
                        </NumberField>
                        <InputError :message="errors.DedAmt" />
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="balance_amount">Balance Amount </Label>
                        <NumberField
                            v-model="form.BalanceAmt"
                            class="mt-1 block w-full"
                            id="balance_amount"
                            :step="0.01"
                            :locale="'en-PH'"
                            :format-options="{
                                style: 'currency',
                                currency: 'PHP',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            }"
                        >
                            <NumberFieldContent>
                                <NumberFieldDecrement />
                                <NumberFieldInput />
                                <NumberFieldIncrement />
                            </NumberFieldContent>
                        </NumberField>

                        <InputError :message="errors.BalanceAmt" />
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-3">
                    <div class="grid grid-rows-[auto_auto_20px] gap-2 ">
                        <Label for="frequency">Frequency </Label>
                        <Input
                            id="frequency"
                            v-model="form.Frequency"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="frequency"
                            placeholder="Frequency"
                        />
                        <InputError :message="props.errors.Frequency" />
                    </div>

                    <div class="grid grid-rows-[auto_auto_20px] gap-2">
                        <Label for="start_deduction_date">Start Deduction Date</Label>
                        <DatePicker
                            class="w-50"
                            v-model="form.StartDate"
                        ></DatePicker>
                        <InputError :message="props.errors.StartDate" />

                    </div>
                    <div class="grid grid-rows-[auto_auto_20px] gap-2">
                        <Label for="balance_as_of_date">Balance As of Date</Label>
                        <NumberField
                            v-model="form.BalanceasofDate"
                            class="mt-1 block w-full"
                            id="balance_as_of_date"
                            :step="0.01"
                            :locale="'en-PH'"
                            :format-options="{
                                style: 'currency',
                                currency: 'PHP',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            }"
                        >
                            <NumberFieldContent>
                                <NumberFieldDecrement />
                                <NumberFieldInput />
                                <NumberFieldIncrement />
                            </NumberFieldContent>
                        </NumberField>
                        <InputError :message="props.errors.BalanceasofDate" />
                    </div>
                </div>
                <div class="flex justify-end gap-4">
                    <Button
                        class="cursor-pointer"
                        variant="outline"
                        type="button"
                        @click="handleCancel"
                    >Cancel</Button>
                    <Button
                        class="cursor-pointer"
                        type="submit"
                        :disabled="isLoading"
                        @click="isAdd ? onSave() : onUpdate()"
                    >
                        <Spinner
                            class="animate-spin"
                            v-if="isLoading"
                        />
                        Submit
                    </Button>
                </div>
            </div>
        </div>
        <Dialog
            v-model:visible="visible"
            title="New Loan Type"
            description="Manage a new loan type"
            @save="onSaveLoanType"
        >
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="loan_type">Loan Type</Label>
                    <Input
                        v-model="loanType.LoanType"
                        id="loan_type"
                        name="loan_type"
                        type="text"
                        placeholder="Description"
                    />
                    <InputError :message="props.errors.LoanType" />
                </div>
            </div>
        </Dialog>
    </AppLayout>

</template>
