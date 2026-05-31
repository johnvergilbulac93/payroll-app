<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';

import { Separator } from '@/components/ui/separator';
import { useActiveUrl } from '@/composables/useActiveUrl';
import { toUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import {
    interpret_attendance_logs,
    index as uploading,
} from '@/routes/attendance/processing';
import { show } from '@/routes/two-factor';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Upload Biometrics .DAT file',
        href: uploading(),
    },
    {
        title: 'Interpret Uploaded Attendance Log',
        href: interpret_attendance_logs(),
    },
    {
        title: 'Attendance Logs Corrections',
        href: show(),
    },
    {
        title: 'Confirm Final Validated Attendance Logs',
        href: editAppearance(),
    },
    {
        title: 'Print Attendance Logs',
        href: editAppearance(),
    },
];

const { urlIsActive } = useActiveUrl();
</script>

<template>
    <div class="px-4 py-6">
        <Heading
            title="Attendance Processing"
            description="Manage the processing of attendance"
        />

        <div class="flex flex-col lg:flex-row lg:space-x-12">
            <aside class="w-full max-w-xl lg:w-80">
                <nav
                    class="flex flex-col space-y-1 space-x-0"
                    aria-label="Attendance Processing"
                >
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="toUrl(item.href)"
                        variant="ghost"
                        :class="[
                            'w-full justify-start',
                            { 'bg-muted': urlIsActive(item.href) },
                        ]"
                        as-child
                    >
                        <Link :href="item.href">
                            <component :is="item.icon" class="h-4 w-4" />
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <Separator class="my-6 lg:hidden" />

            <div class="flex-1">
                <section class="space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
