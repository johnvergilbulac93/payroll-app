<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { h, onMounted, ref } from 'vue'
import { type BreadcrumbItem } from '@/types';
import { index, store, update } from '@/routes/users/accounts';
import { User, UserError } from '@/types/user';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch'
import { toast } from 'vue-sonner';
import { Check } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { IconCircleCheckFilled } from '@tabler/icons-vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Maintenance',
        href: index().url,
    },
];

const isAdd = ref<boolean>(false)
const isLoading = ref<boolean>(false)

const form = useForm<User>({
    id: '',
    public_id: '',
    name: '',
    email: '',
    username: '',
    isActive: true,
    role: '',
    created_at: ''
})
const props = withDefaults(defineProps<{
    errors: UserError;
    user: User;
}>(), {
})

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
                description: 'User saved successfully.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
            });
        },
        onError: () => {
            isLoading.value = false;
        },
    });
}

const onUpdate = () => {
    form.put(update(form.id).url, {
        preserveState: true,
        onBefore: () => {
            isLoading.value = true;
        },
        onSuccess: () => {
            isLoading.value = false;
            form.reset();
            toast.success('Success', {
                description: 'User updated successfully.',
                icon: () => h(IconCircleCheckFilled, { class: 'h-5 w-5 text-emerald-500 ' }),
            });
        },
        onError: () => {
            isLoading.value = false;
        },
    });
}

onMounted(() => {
    isAdd.value = props.user ? true : false
    if (props.user) {
        form.id = props.user.id
        form.name = props.user.name
        form.email = props.user.email
        form.username = props.user.username
        form.role = props.user.role
        form.role = props.user.role
        form.isActive = props.user.isActive
    }
})

</script>
<template>

    <Head title="New User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6 ">
            <div class="flex items-center justify-between">
                <Heading
                    :title="!isAdd ? 'Add User' : 'Update User'"
                    :description="!isAdd
                        ? 'Manage new user accounts'
                        : 'Update selected user'
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
            <div class="w-full lg:w-1/2 space-y-4">
                <div class="grid gap-4 grid-cols-1 1">
                    <div class="grid grid-rows-[auto_auto_20px] gap-1 space-y-2">
                        <Label
                            for="name"
                            :class="{
                                'text-red-500': props.errors.name
                            }"
                        >Name </Label>
                        <Input
                            v-model="form.name"
                            id="name"
                            type="text"
                            autocomplete="name"
                            placeholder="name"
                            :class="{
                                'border-red-500': props.errors.name
                            }"
                        />
                        <InputError :message="props.errors.name" />
                    </div>

                </div>
                <div class="grid gap-4 grid-cols-1 1">
                    <div class="grid grid-rows-[auto_auto_20px] gap-1">
                        <Label
                            for="email"
                            :class="{
                                'text-red-500': props.errors.email
                            }"
                        >Email </Label>
                        <Input
                            v-model="form.email"
                            id="email"
                            type="email"
                            autocomplete="email"
                            placeholder="email"
                            :class="{
                                'border-red-500': props.errors.name
                            }"
                        />
                        <InputError :message="props.errors.email" />
                    </div>
                </div>
                <div class="grid gap-4 grid-cols-1 1">
                    <div class="grid grid-rows-[auto_auto_20px] gap-1">
                        <Label
                            for="username"
                            :class="{
                                'text-red-500': props.errors.username
                            }"
                        >Username </Label>
                        <Input
                            v-model="form.username"
                            id="username"
                            type="text"
                            autocomplete="username"
                            placeholder="username"
                            :class="{
                                'border-red-500': props.errors.name
                            }"
                        />
                        <InputError :message="props.errors.username" />
                    </div>
                </div>
                <div class="grid gap-4 grid-cols-1 1">
                    <div class="grid grid-rows-[auto_auto_20px] gap-2 ">
                        <Label
                            for="role"
                            :class="{
                                'text-red-500': props.errors.role
                            }"
                        >Role</Label>
                        <Select v-model="form.role">
                            <SelectTrigger
                                class=" w-1/2 "
                                size="sm"
                                :class="{
                                    'border-red-500': props.errors.name
                                }"
                            >
                                <SelectValue placeholder="Select a role" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="1">SuperAdmin</SelectItem>
                                <SelectItem value="2"> Admin </SelectItem>
                                <SelectItem value="3"> User </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="props.errors.role" />
                    </div>
                </div>
                <div class="grid gap-4 grid-cols-1 ">
                    <div class="grid grid-rows-[auto_auto_20px]">
                        <div class="flex items-center space-x-2">
                            <Switch
                                id="status"
                                v-model="form.isActive"
                            />
                            <Label for="status">Status</Label>
                        </div>
                        <!-- <InputError :message="props.errors.Frequency" /> -->
                    </div>
                </div>
                <div class="flex justify-start gap-4">
                    <Button
                        class="cursor-pointer"
                        type="submit"
                        :disabled="isLoading"
                        @click="!isAdd ? onSave() : onUpdate()"
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
    </AppLayout>
</template>
<style lang="">

</style>