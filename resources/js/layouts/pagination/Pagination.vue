<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
interface PaginatedData {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}
const props = defineProps<{
    pagination: PaginatedData;
}>();

// const page = ref('10');

// Emits event to parent when per-page changes
const emit = defineEmits<{
    (e: 'per-page-change', value: number): void;
}>();

const page = ref('10');

// Watch for changes and emit
watch(page, (value) => {
    emit('per-page-change', Number(value));
});
</script>

<template>
    <div class="mt-4 flex items-center justify-between gap-1">
        <div class="flex gap-1">
            <Button
                v-for="link in pagination?.links"
                :key="link.label"
                :variant="link.active ? 'default' : 'outline'"
                :disabled="!link.url"
                v-html="link.label"
                @click="link.url && router.visit(link.url)"
            />
        </div>
        <div class="flex items-center gap-2">
            <Select v-model="page">
                <SelectTrigger class="w-[100]">
                    <SelectValue />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <SelectLabel>Per Page</SelectLabel>
                        <SelectItem value="10"> 10 </SelectItem>
                        <SelectItem value="20"> 20 </SelectItem>
                        <SelectItem value="50"> 50 </SelectItem>
                        <SelectItem value="100"> 100 </SelectItem>
                    </SelectGroup>
                </SelectContent>
            </Select>
            <div class="text-sm font-medium text-[#586271]">
                Showing {{ pagination?.from ? pagination?.from : 0 }} to
                {{ pagination?.to ? pagination?.to : 0 }} of
                {{ pagination?.total ? pagination?.total : 0 }} entries
            </div>
        </div>
    </div>
</template>
