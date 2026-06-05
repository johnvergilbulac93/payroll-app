<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AttendanceProcessingLayout from '@/layouts/attendance-processing/Layout.vue';
import { index, upload } from '@/routes/attendance/processing';
import { Head, router } from '@inertiajs/vue3';

import Button from '@/components/ui/button/Button.vue';
import Spinner from '@/components/ui/spinner/Spinner.vue';
import { type BreadcrumbItem } from '@/types';
import { Check, Search, Upload } from '@lucide/vue';
import { h, ref } from 'vue';
import { toast } from 'vue-sonner';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Attendance Processing',
        href: index().url,
    },
];

// file handling
const fileInput = ref<HTMLInputElement | null>(null);
const selectedFile = ref<File | null>(null);
const selectedFileName = ref<string>('');
const isLoading = ref(false);
const triggerFileSelect = () => {
    fileInput.value?.click();
};

const onFileChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0] ?? null;

    selectedFile.value = file;
    selectedFileName.value = file?.name ?? '';

    uploadFile();
};

const uploadFile = () => {
    if (!selectedFile.value) return;

    const formData = new FormData();
    formData.append('file', selectedFile.value);

    router.post(upload(), formData, {
        forceFormData: true,
        onBefore: () => {
            isLoading.value = true;
        },
        onSuccess: () => {
            selectedFile.value = null;
            selectedFileName.value = '';
            isLoading.value = false;
            if (fileInput.value) fileInput.value.value = '';
            toast.success('Upload Sucessful! ', {
                icon: () => h(Check, { class: 'h-5 w-5 text-green-500' }), // correct way
            });
        },
        onError: () => {
            isLoading.value = false;
            if (fileInput.value) fileInput.value.value = '';
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Upload Biometrics .DAT File" />

        <h1 class="sr-only">Attendance Processing</h1>
        <AttendanceProcessingLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Upload Biometrics .DAT File" description="Manage Biometrics .DAT File " />

                <div class="flex w-full items-center justify-center">
                    <div
                        class="flex h-64 w-full flex-col items-center justify-center rounded border border-dashed bg-[#f9fafb]">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-[#4a5565]">
                            <Upload />
                            <p class="mb-2 text-sm">
                                Click the button below to upload
                            </p>
                            <p class="mb-4 text-xs">.DAT FILE</p>
                            <Button type="button" class="cursor-pointer" :disabled="isLoading"
                                @click="triggerFileSelect">
                                <Search v-if="!isLoading" />
                                <Spinner v-if="isLoading" />

                                {{ isLoading ? 'Uploading...' : 'Browse file' }}
                            </Button>
                            <p v-if="selectedFileName" class="mt-3 text-sm text-muted-foreground">
                                Selected file:
                                <span class="font-medium">{{
                                    selectedFileName
                                    }}</span>
                            </p>
                        </div>
                    </div>
                    <input ref="fileInput" type="file" accept=".dat" class="hidden" @change="onFileChange" />
                </div>
            </div>
        </AttendanceProcessingLayout>
    </AppLayout>
</template>
