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
import { create, index, store as newEmployee, update } from '@/routes/employees';
import { Helper, type BreadcrumbItem } from '@/types';
import { Employee, EmployeeError } from '@/types/employee';
import { stringToDateValue } from '@/utils/date';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Check, LucideFileQuestion, } from '@lucide/vue';
import { h, onMounted, ref } from 'vue';
import { toast } from 'vue-sonner';
import Tooltip from '@/usable/Tooltip.vue';
import Dialog from '@/usable/Dialog.vue';
import { store as newGroup } from '@/routes/helpers/group';
import Combobox from '@/usable/Combobox.vue';
import { Group, GroupError } from '@/types/group';
import { IconCircleCheckFilled } from '@tabler/icons-vue';
import { Switch } from '@/components/ui/switch';

const form = useForm<Employee>({
    public_id: '',
    EmpNbr: '',
    FirstName: '',
    Group: '',
    MidName: '',
    LastName: '',
    Suffix: '',
    Address: '',
    CityProv: '',
    BirthDate: '',
    EmployDate: '',
    RegularDate: '',
    Position: '',
    Assignment: '',
    SalaryGrade: '',
    BasicPay: 0,
    DailyRate: 0,
    HourlyRate: 0,
    Status: true,
    SSSNbr: '',
    PHICNbr: '',
    HDMFNbr: '',
    TIN: '',
    Degree: '',
    AllowReg: '',
    ResignDate: '',
    BPIATM: '',
    BPIEmpCode: '',
    PIN: '',
    PERAAID: '',
});
const group = useForm<Group>({
    Code: '',
    Description: '',
    Route: 'employees.create'
});
onMounted(() => {
    isAdd.value = !props.employee ? true : false;
    if (props.employee) {
        form.public_id = props.employee.public_id;
        form.EmpNbr = props.employee.EmpNbr;
        form.FirstName = props.employee.FirstName;
        form.LastName = props.employee.LastName;
        form.MidName = props.employee.MidName;
        form.Suffix = props.employee.Suffix;
        form.Address = props.employee.Address;
        form.BirthDate = props.employee.BirthDate;
        form.EmployDate = props.employee.EmployDate;
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
interface Errors extends GroupError, EmployeeError { }

const props = withDefaults(defineProps<{
    errors: Errors;
    employee: Employee;
    groups: any[];
}>(), {
})

const isAdd = ref(false);
const isLoading = ref(false);
const visible = ref(false);

const onSave = () => {
    form.post(newEmployee().url, {
        preserveState: true,
        onBefore: () => {
            isLoading.value = true;
        },
        onSuccess: () => {
            isLoading.value = false;
            form.reset();
            toast.success('Success', {
                description: 'Employee saved successfully.',
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
                description: 'Employee updated successfully.',
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

const showGroupDialog = () => {
    visible.value = true;
};

const onSaveGroup = () => {
    group.post(newGroup().url, {
        onSuccess: () => {
            group.reset();
            visible.value = false;
            toast.success('Success', {
                description: 'Group saved successfully.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
            });
        },
    });
};
</script>

<template>

    <Head :title="isAdd ? 'Add Employee' : 'Update Employee'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6">
            <div class="flex items-center justify-between">
                <Heading
                    :title="isAdd ? 'Add Employee' : 'Update Employee'"
                    :description="isAdd
                        ? 'Manage your new Employee'
                        : 'Update selected Employee'
                        "
                />
                <Button
                    class="cursor-pointer"
                    type="button"
                    @click="handleCancel"
                >
                    <ArrowLeft /> Back
                </Button>
            </div>

            <div class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-4">
                    <div class="grid grid-rows-[auto_auto_20px] gap-2 col-span-3">
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
                        <div class="flex items-center gap-2">
                            <Combobox
                                :items="groups"
                                v-model="form.Group"
                                :labelText="'Select a group'"
                            />
                            <Tooltip description="Add new group">
                                <Button
                                    @click="showGroupDialog"
                                    variant="outline"
                                    class="cursor-pointer"
                                >
                                    <LucideFileQuestion class="text-[#1549e6]" />
                                </Button>
                            </Tooltip>
                        </div>
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
                        <Label for="date_of_employments">Date of Employment</Label>
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
                        <Label for="area_of_assignment">Area of Assignment</Label>
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
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-3">
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
                    </div>


                </div>
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-3">
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
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-1 lg:grid-cols-3">
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <Label for="degree_educational_attainment">Degree / Educational Attainment</Label>
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
                        <Label for="allowances_regularly_received">Allowances Regularly Received</Label>
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
                        <Label for="date_of_resignation">Date of Resignation</Label>
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
                <div class="grid  grid-cols-1 ">
                    <div class="grid grid-rows-[auto_auto_15px] gap-2">
                        <div class="flex items-center space-x-2">
                            <Switch id="status" v-model="form.Status" />
                            <Label for="status">Status</Label>
                        </div>
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
            title="Add Group"
            description="Manage your new group"
            @save="onSaveGroup"
        >
            <div class="grid gap-4">
                <div class="grid gap-2 ">
                    <Label for="group_code">Code</Label>
                    <Input
                        v-model="group.Code"
                        id="group_code"
                        name="group_code"
                        type="text"
                        placeholder="Code"
                    />
                    <InputError :message="props.errors.Code" />

                </div>

                <div class="grid gap-2">
                    <Label for="group_description">Description</Label>
                    <Input
                        v-model="group.Description"
                        id="group_description"
                        name="group_description"
                        type="text"
                        placeholder="Description"
                    />
                    <InputError :message="props.errors.Description" />
                </div>
            </div>
        </Dialog>
    </AppLayout>

</template>
