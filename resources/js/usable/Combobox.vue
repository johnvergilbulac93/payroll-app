<script setup lang="ts">
import { CheckIcon, ChevronsUpDownIcon } from '@lucide/vue'
import { computed, ref } from 'vue'
import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/components/ui/command'
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover'

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
    labelText: {
        type: String,
        default: 'Please select an item.',
    },
})

const open = ref(false)
const value = defineModel<string>({
    default: '',
})

const selectedFramework = computed(() =>
    props.items.find(item => item.value === value.value),
)

function selectItem(selectedValue: string) {
    value.value = selectedValue === value.value ? '' : selectedValue
    open.value = false
}
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button variant="outline" role="combobox" :aria-expanded="open" class="w-50 justify-between">
                {{ selectedFramework?.label || props.labelText }}
                <ChevronsUpDownIcon class="opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-50 p-0">
            <Command>
                <CommandInput class="h-9" placeholder="Search..." />
                <CommandList>
                    <CommandEmpty>No record found.</CommandEmpty>
                    <CommandGroup>
                        <CommandItem v-for="item in items" :key="item.value" :value="item.value" @select="(ev) => {
                            selectItem(ev.detail.value as string)
                        }">
                            {{ item.label }}
                            <CheckIcon :class="cn(
                                'ml-auto',
                                value === item.value ? 'opacity-100' : 'opacity-0',
                            )" />
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
