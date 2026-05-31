<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AttendanceProcessingLayout from '@/layouts/attendance-processing/Layout.vue';
import { index } from '@/routes/attendance/processing';
import { Head } from '@inertiajs/vue3';

import { type BreadcrumbItem } from '@/types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Interpret Uploaded Attendance Log',
        href: index().url,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Interpret Uploaded Attendance Log" />

        <h1 class="sr-only">Interpret Uploaded Attendance Log</h1>
        <AttendanceProcessingLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    title="Interpret Uploaded Attendance Log"
                    description="Manage Uploaded Attendance Log"
                />
            </div>
        </AttendanceProcessingLayout>
    </AppLayout>
</template>
