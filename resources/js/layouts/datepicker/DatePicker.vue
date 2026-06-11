<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { cn } from '@/lib/utils';
import type { DateValue } from '@internationalized/date';
import {
    DateFormatter,
    getLocalTimeZone,
    today,
    parseDate,
} from '@internationalized/date';
import { CalendarDays } from '@lucide/vue';
import { computed, ref, watch } from 'vue';

// Props
const props = defineProps<{
    placeholder?: string;
    class?: string;
}>();

// defineModel for v-model binding
// const date = defineModel<DateValue | undefined>('modelValue');

const modelValue = defineModel<string | undefined>('modelValue');

const internalDate = ref<DateValue>();


// Long format for display
const df = new DateFormatter('en-PH', {
    dateStyle: 'long', // "January 21, 2026"
});


const defaultPlaceholder = today(getLocalTimeZone());


const displayText = computed(() => {
    if (internalDate.value) {
        return df.format(
            internalDate.value.toDate(getLocalTimeZone())
        );
    }
    return 'Pick a date';
});


const updateDate = (value: DateValue | null) => {
    internalDate.value = value ?? undefined;

    if (!value) {
        modelValue.value = undefined;
        return;
    }

    const jsDate = value.toDate(getLocalTimeZone());

    const mm = String(jsDate.getMonth() + 1).padStart(2, '0');
    const dd = String(jsDate.getDate()).padStart(2, '0');
    const yyyy = jsDate.getFullYear();

    modelValue.value = `${yyyy}-${mm}-${dd}`;
};

watch(
    () => modelValue.value,
    (value) => {
        internalDate.value = value ? parseDate(value) : undefined;
    },
    { immediate: true }
);
</script>

<template>
    <Popover v-slot="{ close }">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                :class="cn(
                    'w-full justify-start text-left font-normal',
                    !internalDate && 'text-muted-foreground',
                )
                    "
            >
                <CalendarDays class="mr-2" />
                {{ displayText }}
            </Button>
        </PopoverTrigger>
        <PopoverContent
            class="w-auto p-0"
            align="start"
        >
            <Calendar
                v-model="internalDate"
                :default-placeholder="defaultPlaceholder"
                layout="month-and-year"
                initial-focus
                @update:model-value="
                    (val) => {
                        updateDate(val ?? null);
                        close();
                    }
                "
            />
        </PopoverContent>
    </Popover>
</template>
