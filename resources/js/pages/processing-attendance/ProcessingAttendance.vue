<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AttendanceProcessingLayout from '@/layouts/attendance-processing/Layout.vue';
import { index } from '@/routes/attendance/processing';
import { Head } from '@inertiajs/vue3';

import { type BreadcrumbItem } from '@/types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Attendance Processing',
        href: index().url,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Attendance Processing" />
        <h1 class="sr-only">Attendance Processing</h1>
        <AttendanceProcessingLayout></AttendanceProcessingLayout>
    </AppLayout>
</template>
