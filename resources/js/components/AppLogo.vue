<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';

import { FolderClock, HandCoins } from '@lucide/vue';
</script>

<template>
    <div class="flex aspect-square size-10 items-center justify-center rounded-md  ">
        <!-- <AppLogoIcon class="size-5 fill-current text-white dark:text-black" /> -->
        <HandCoins class="  dark:text-white size-8" />
    </div>
    <div class=" grid flex-1 text-left">
        <span class="mb-0.5 truncate leading-tight font-semibold text-xl">Payroll</span>
    </div>
</template>
