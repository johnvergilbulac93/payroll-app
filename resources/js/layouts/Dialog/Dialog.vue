<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { ref, watch } from 'vue';

interface Props {
    modelValue?: boolean; // for v-model
    title?: string;
    description?: string;
    confirmText?: string;
    cancelText?: string;
}

const props = defineProps<Props>();
const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
    (e: 'confirm'): void;
    (e: 'cancel'): void;
}>();

const open = ref(!!props.modelValue);

// Sync with v-model
watch(
    () => props.modelValue,
    (val) => (open.value = val),
);
watch(open, (val) => emit('update:modelValue', val));

// Emit v-model updates
const closeDialog = () => {
    open.value = false;
    emit('update:modelValue', false);
};

const onConfirm = () => {
    emit('confirm');
    closeDialog();
};

const onCancel = () => {
    emit('cancel');
    closeDialog();
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ props.title || 'Are you sure?' }}</DialogTitle>
                <DialogDescription class="mt-1">
                    {{ props.description || 'This action cannot be undone.' }}
                </DialogDescription>
            </DialogHeader>

            <DialogFooter class="flex justify-end gap-2">
                <Button
                    variant="outline"
                    @click="onCancel"
                    class="cursor-pointer"
                >
                    {{ props.cancelText || 'Cancel' }}
                </Button>
                <Button
                    @click="onConfirm"
                    class="cursor-pointer"
                >
                    {{ props.confirmText || 'Confirm' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
