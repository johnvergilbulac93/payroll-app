<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';

import { Separator } from '@/components/ui/separator';
import { useActiveUrl } from '@/composables/useActiveUrl';
import { toUrl } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { index as accountsIndex } from '@/routes/users/accounts'
import { index as archivesIndex } from '@/routes/users/archives'
import { index as rolesIndex } from '@/routes/users/roles';


const sidebarNavItems: NavItem[] = [
    {
        title: 'Accounts',
        href: accountsIndex().url,
    },
    {
        title: 'Archives',
        href: archivesIndex().url,
    },
    {
        title: 'Roles',
        href: rolesIndex().url,
    },

];

const { urlIsActive } = useActiveUrl();
</script>

<template>
    <div class="px-4">
        <Heading
            title="User Management"
            description="Manage the user Management"
        />
        <div class="flex flex-col lg:flex-row ">
            <aside class="w-full max-w-lg lg:w-50 ">
                <nav
                    class="flex flex-col space-y-1 space-x-0"
                    aria-label="User Management"
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
                            <component
                                :is="item.icon"
                                class="h-4 w-4"
                            />
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
