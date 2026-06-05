<script setup lang="ts">
import { Button } from '@/components/ui/button'
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

defineProps({
    title: {
        type: String,
        default: 'Title Here',
    },
    description: {
        type: String,
        default: 'Description Here',
    },
    buttonText: {
        type: String,
        default: 'Submit',
    },
    visible: {
        type: Boolean,
        default: false,
    },
})
const visible = defineModel<boolean>('visible', {
    default: false,
})
const emit = defineEmits(['save'])
</script>

<template>
    <Dialog v-model:open="visible">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>
                    {{ description }}
                </DialogDescription>
            </DialogHeader>
            <slot />
            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="outline">
                        Cancel
                    </Button>
                </DialogClose>
                <Button type="submit" @click="emit('save')">
                    {{ buttonText }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
