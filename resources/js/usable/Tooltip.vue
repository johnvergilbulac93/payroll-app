<script setup lang="ts">
import { Button } from '@/components/ui/button'
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip'

defineProps({
    description: {
        type: String,
        default: 'Text Here',
    },

})
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger as-child>
                <slot />
            </TooltipTrigger>
            <TooltipContent>
                <p>{{ description }}</p>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
