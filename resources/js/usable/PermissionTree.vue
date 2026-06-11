<script setup lang="ts">
import { ref, watch } from 'vue'
import TreeNode from './TreeNode.vue'
import { Permission } from '@/types/permission.js';



const props = defineProps<{
    data: Permission[]
    modelValue: number[]
}>()

const emit = defineEmits(['update:modelValue'])

const selected = ref<number[]>([])


const flattenChecked = (nodes: Permission[]): number[] => {
    return nodes.flatMap(n => [
        ...(n.checked ? [n.id] : []),
        ...flattenChecked(n.children ?? [])
    ])
}

watch(() => props.data, (val) => {
    if (val?.length) {
        selected.value = flattenChecked(val)
    }
}, { immediate: true, deep: true })

const toggle = ({ ids, action }: { ids: number[], action: 'check' | 'uncheck' }) => {
    if (action === 'check') {
        selected.value = [...new Set([...selected.value, ...ids])]
    } else {
        selected.value = selected.value.filter(id => !ids.includes(id))
    }
    emit('update:modelValue', selected.value)
}
</script>

<template>
    <TreeNode
        v-for="item in data"
        :key="item.id"
        :node="item"
        :selected="selected"
        @toggle="toggle"
    />
</template>