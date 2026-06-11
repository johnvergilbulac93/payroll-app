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

// defineProps({
//     title: {
//         type: String,
//         default: 'Title Here',
//     },
//     description: {
//         type: String,
//         default: 'Description Here',
//     },
//     buttonText: {
//         type: String,
//         default: 'Submit',
//     },
//     visible: {
//         type: Boolean,
//         default: false,
//     },
//     canAdd: {
//         type: Boolean,
//         default: true
//     }
// })
const props = withDefaults(defineProps<{
    title: string,
    description: string
    buttonText?: string
    visible: boolean
    canAdd?: boolean
}>(), {
    title: 'Title Here',
    description: 'Description Here',
    buttonText: 'Submit',
    visible: false,
    canAdd: true
})
const visible = defineModel<boolean>('visible', {
    default: false,
})
const emit = defineEmits(['save'])

</script>

<template>
    <Dialog v-model:open="visible">
        <DialogContent >
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
                <Button
                    v-if="canAdd"
                    type="submit"
                    class="cursor-pointer"
                    @click="emit('save')"
                >
                    {{ buttonText }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
