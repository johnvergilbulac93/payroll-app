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
} from '@internationalized/date';
import { CalendarDays } from 'lucide-vue-next';
import { computed } from 'vue';

// Props
const props = defineProps<{
    placeholder?: string;
    class?: string;
}>();

// defineModel for v-model binding
const date = defineModel<DateValue | undefined>('modelValue');

// Long format for display
const df = new DateFormatter('en-PH', {
    dateStyle: 'long', // "January 21, 2026"
});

// Numeric format for form submission or storage

const defaultPlaceholder = today(getLocalTimeZone());

const displayText = computed(() => {
    if (date.value) {
        return df.format(date.value.toDate(getLocalTimeZone()));
    }
    return 'Pick a date';
});

const updateDate = (value: DateValue) => {
    date.value = value;
};
</script>

<template>
    <Popover v-slot="{ close }">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                :class="
                    cn(
                        'w-full justify-start text-left font-normal',
                        !date && 'text-muted-foreground',
                    )
                "
            >
                <CalendarDays class="mr-2" />
                {{ displayText }}
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0" align="start">
            <Calendar
                v-model="date"
                :default-placeholder="defaultPlaceholder"
                layout="month-and-year"
                initial-focus
                @update:model-value="
                    (val) => {
                        updateDate(val);
                        close();
                    }
                "
            />
        </PopoverContent>
    </Popover>
</template>
