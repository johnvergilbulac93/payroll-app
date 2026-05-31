<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
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
import { create, index, store, update } from '@/routes/employees';
import { type BreadcrumbItem } from '@/types';
import type { Employee } from '@/types/employee';
import { stringToDateValue } from '@/utils/date';
import { Head, router, useForm } from '@inertiajs/vue3';
import type { DateValue } from '@internationalized/date';
import { ArrowLeft, Check } from 'lucide-vue-next';
import { h, onMounted, ref } from 'vue';
import { toast } from 'vue-sonner';

const form = useForm<Employee>({
    id: '',
    EmpNbr: '',
    FirstName: '',
    Group: '',
    MidName: '',
    LastName: '',
    Suffix: '',
    Address: '',
    CityProv: '',
    BirthDate: null as DateValue | null,
    EmployDate: null as DateValue | null,
    RegularDate: null as DateValue | null,
    Position: '',
    Assignment: '',
    SalaryGrade: '',
    BasicPay: '',
    DailyRate: '',
    HourlyRate: '',
    Status: '',
    SSSNbr: '',
    PHICNbr: '',
    HDMFNbr: '',
    TIN: '',
    Degree: '',
    AllowReg: '',
    ResignDate: null as DateValue | null,
    BPIATM: '',
    BPIEmpCode: '',
    PIN: '',
    PERAAID: '',
});
onMounted(() => {
    isAdd.value = !props.employee ? true : false;
    if (props.employee) {
        form.id = props.employee.id;
        form.EmpNbr = props.employee.EmpNbr;
        form.FirstName = props.employee.FirstName;
        form.LastName = props.employee.LastName;
        form.MidName = props.employee.MidName;
        form.Suffix = props.employee.Suffix;
        form.Address = props.employee.Address;
        form.BirthDate = stringToDateValue(props.employee.BirthDate);
        form.CityProv = props.employee.CityProv;
        form.Position = props.employee.Position;
        form.Assignment = props.employee.Assignment;
        form.SalaryGrade = props.employee.SalaryGrade;
        form.BasicPay = props.employee.BasicPay ? props.employee.BasicPay : 0;
        form.DailyRate = props.employee.DailyRate
            ? props.employee.DailyRate
            : 0;
        form.HourlyRate = props.employee.HourlyRate
            ? props.employee.HourlyRate
            : 0;
        form.Status = props.employee.Status;
        form.SSSNbr = props.employee.SSSNbr;
        form.PHICNbr = props.employee.PHICNbr;
        form.HDMFNbr = props.employee.HDMFNbr;
        form.TIN = props.employee.TIN;
        form.Degree = props.employee.Degree;
        form.AllowReg = props.employee.AllowReg ? props.employee.AllowReg : 0;
        form.BPIATM = props.employee.BPIATM;
        form.BPIEmpCode = props.employee.BPIEmpCode;
        form.PIN = props.employee.PIN;
        form.PERAAID = props.employee.PERAAID;

        form.Group = props.employee.Group.trim();
    }
});
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Employee 201',
        href: create().url,
    },
];

const props = defineProps<{
    errors: Object;
    employee: Object;
}>();

const isAdd = ref(false);
const isLoading = ref(false);

const onSave = () => {
    form.post(store().url, {
        preserveState: true,
        onBefore: () => {
            isLoading.value = true;
        },
        onSuccess: () => {
            isLoading.value = false;
            form.reset();
            toast.success('Successfully saved. ', {
                icon: () => h(Check, { class: 'h-5 w-5 text-green-500' }), // correct way
            });
        },
        onError: () => {
            isLoading.value = false;
        },
    });
};
const onUpdate = () => {
    form.put(update(form.id).url, {
        preserveState: true,
        onBefore: () => {
            isLoading.value = true;
        },
        onSuccess: () => {
            isLoading.value = false;
            form.reset();
            toast.success('Successfully Updated. ', {
                icon: () => h(Check, { class: 'h-5 w-5 text-green-500' }), // correct way
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
</script>

<template>
    <Head :title="isAdd ? 'Add Employee' : 'Update Employee'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6">
            <div class="flex items-center justify-between">
                <Heading
                    :title="isAdd ? 'Add Employee' : 'Update Employee'"
                    :description="
                        isAdd
                            ? 'Manage you new Employee'
                            : 'Update selected Employee'
                    "
                />
                <Button
                    class="cursor-pointer"
                    type="button"
                    @click="handleCancel"
                    ><ArrowLeft /> Back</Button
                >
            </div>

            <div class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-2">
                    <div class="grid grid-rows-[auto_auto_20px] gap-2">
                        <Label for="employee_number">Employee Number </Label>
                        <Input
                            id="employee_number"
                            v-model="form.EmpNbr"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="employee-number"
                            placeholder="Employee Number"
                        />
                        <InputError :message="props.errors.EmpNbr" />
                    </div>
                    <div class="grid grid-rows-[auto_auto_20px] gap-2">
                        <Label for="group">Group </Label>
                        <Select id="group" v-model="form.Group">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select a group" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectLabel>Group</SelectLabel>
                                    <SelectItem value="NT">
                                        Non-Teaching
                                    </SelectItem>
                                    <SelectItem value="T">
                                        Teaching
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <InputError :message="props.errors.Group" />
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-4">
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="first_name">First Name</Label>
                        <Input
                            id="first_name"
                            v-model="form.FirstName"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="firstname"
                            placeholder="First Name"
                        />
                        <InputError :message="props.errors.FirstName" />
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="middle_name">Middle Name</Label>
                        <Input
                            id="middle_name"
                            v-model="form.MidName"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="middlename"
                            placeholder="Middle Name"
                        />
                        <InputError :message="props.errors.MidName" />
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="last_name">Last Name</Label>
                        <Input
                            id="last_name"
                            v-model="form.LastName"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="lastname"
                            placeholder="Last Name"
                        />
                        <InputError :message="props.errors.LastName" />
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="suffix">Suffix</Label>
                        <Input
                            id="suffix"
                            v-model="form.Suffix"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="suffix"
                            placeholder="Jr., Sr., I, II, etc"
                        />
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-2">
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="address">Address</Label>
                        <Input
                            id="address"
                            v-model="form.Address"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="address"
                            placeholder="Address"
                        />
                        <InputError :message="props.errors.Address" />
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="city_province">City/Province</Label>
                        <Input
                            id="city_province"
                            v-model="form.CityProv"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="city-province"
                            placeholder="City/Province"
                        />
                        <InputError :message="props.errors.CityProv" />
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-2">
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="birth_date">Birth Date</Label>
                        <DatePicker
                            v-model="form.BirthDate"
                            placeholder="Select birth date"
                        />
                        <InputError :message="props.errors.BirthDate" />
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="date_of_employments"
                            >Date of Employment</Label
                        >
                        <DatePicker
                            v-model="form.EmployDate"
                            placeholder="Select date of employment"
                        />
                        <InputError :message="props.errors.EmployDate" />
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-3">
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="position">Position</Label>
                        <Input
                            id="position"
                            v-model="form.Position"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="position"
                            placeholder="Position"
                        />
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="area_of_assignment"
                            >Area of Assignment</Label
                        >
                        <Input
                            id="area_of_assignment"
                            v-model="form.Assignment"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="area-of-assignment"
                            placeholder="Area of Assignment"
                        />
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="salary_grade">Salary Grade</Label>
                        <Input
                            id="salary_grade"
                            v-model="form.SalaryGrade"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="salary-grade"
                            placeholder="Salary Grade"
                        />
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-4">
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="basic_pay">Basic Pay</Label>

                        <NumberField
                            v-model="form.BasicPay"
                            class="mt-1 block w-full"
                            id="basic_pay"
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
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="daily_rate">Daily Rate</Label>
                        <NumberField
                            v-model="form.DailyRate"
                            class="mt-1 block w-full"
                            id="daily_rate"
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
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="hourly_rate">Hourly Rate</Label>
                        <NumberField
                            v-model="form.HourlyRate"
                            class="mt-1 block w-full"
                            id="hourly_rate"
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
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="status">Status</Label>
                        <Input
                            id="status"
                            v-model="form.Status"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="status"
                            placeholder="Status"
                        />
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-4">
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="sss_number">SSS Number</Label>
                        <Input
                            id="sss_number"
                            v-model="form.SSSNbr"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="sss-number"
                            placeholder="XX-XXXXXXX-X"
                        />
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="phic_number">PHIC Number</Label>
                        <Input
                            id="phic_number"
                            v-model="form.PHICNbr"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="phic-number"
                            placeholder="XX-XXXXXXXXX-X"
                        />
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="hdmf_number">HDMF Number</Label>
                        <Input
                            id="hdmf_number"
                            v-model="form.HDMFNbr"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="hdmf-number"
                            placeholder="XXXX-XXXX-XXXX"
                        />
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="tin_number">TIN Number</Label>
                        <Input
                            id="tin_number"
                            v-model="form.TIN"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="tin-number"
                            placeholder="XXX-XXX-XXX"
                        />
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-3">
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="degree_educational_attainment"
                            >Degree / Educational Attainment</Label
                        >
                        <Input
                            id="degree_educational_attainment"
                            v-model="form.Degree"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="degree-educational-attainment"
                            placeholder="Degree / Educational Attainment"
                        />
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="allowances_regularly_received"
                            >Allowances Regularly Received</Label
                        >
                        <NumberField
                            v-model="form.AllowReg"
                            class="mt-1 block w-full"
                            id="allowances_regularly_received"
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
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="date_of_resignation"
                            >Date of Resignation</Label
                        >
                        <DatePicker
                            v-model="form.ResignDate"
                            placeholder="Select date of resignation"
                        ></DatePicker>
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-4">
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="bpi_atm_no">BPI ATM No.</Label>
                        <Input
                            id="bpi_atm_no"
                            v-model="form.BPIATM"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="bpi-atm-no"
                            placeholder="BPI ATM No."
                        />
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="bpi_employee_code">BPI Employee Code</Label>
                        <Input
                            id="bpi_employee_code"
                            v-model="form.BPIEmpCode"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="bpi-employee-code"
                            placeholder="BPI Employee Code"
                        />
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="pin">PIN</Label>
                        <Input
                            id="pin"
                            v-model="form.PIN"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="pin"
                            placeholder="PIN"
                        />
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="peraa_id">PERAA ID</Label>
                        <Input
                            id="peraa_id"
                            v-model="form.PERAAID"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="peraa-id"
                            placeholder="PERAA ID"
                        />
                        <!-- <InputError :message="errors.current_password" /> -->
                    </div>
                </div>
                <div class="flex justify-end gap-4">
                    <Button
                        class="cursor-pointer"
                        variant="outline"
                        type="button"
                        @click="handleCancel"
                        >Cancel</Button
                    >
                    <Button
                        class="cursor-pointer"
                        type="submit"
                        :disabled="isLoading"
                        @click="isAdd ? onSave() : onUpdate()"
                    >
                        <Spinner class="animate-spin" v-if="isLoading" />
                        Submit
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
