<script setup lang="ts">
import { Checkbox } from '@/components/ui/checkbox'
import { IconOctagon, IconOctagonFilled } from '@tabler/icons-vue';

import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible'
import { computed, ref } from 'vue';
import { ChevronRight } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { Permission } from '@/types/permission';
const props = defineProps<{
    node: any
    selected: number[]
}>()

const emit = defineEmits(['toggle'])

const getAllDescendantIds = (node: any): number[] => {
    if (!node.children?.length) return [Number(node.id)]
    return [
        Number(node.id),
        ...node.children.flatMap((child: any) => getAllDescendantIds(child))
    ]
}
const isChecked = computed(() => {
    const allIds = getAllDescendantIds(props.node).filter(id => id !== Number(props.node.id))

    if (!allIds.length) {
        return props.selected.includes(Number(props.node.id))
    }
    return allIds.every(id => props.selected.includes(id))
})

const handleToggle = () => {
    const allIds = getAllDescendantIds(props.node)
    const allSelected = allIds.every(id => props.selected.includes(id))
    emit('toggle', { ids: allIds, action: allSelected ? 'uncheck' : 'check' })
}
const isIndeterminate = computed(() => {
    const allIds = getAllDescendantIds(props.node).filter(id => id !== Number(props.node.id))
    if (!allIds.length) return false
    const someSelected = allIds.some(id => props.selected.includes(id))
    const allSelected = allIds.every(id => props.selected.includes(id))
    return someSelected && !allSelected
})
</script>

<template>
    <Collapsible
        class="h-auto space-y-1 w-full"
        v-slot="{ open }"
    >
        <div class="group hover:bg-sidebar-accent rounded  flex items-center justify-between border  px-2">
            <CollapsibleTrigger
                as-child
                class="hover:bg-sidebar-accent cursor-pointer  w-full"
            >
                <div class="flex items-center">
                    <Button
                        variant="ghost"
                        size="icon"
                        class="size-8 cursor-pointer"
                        v-if="node.children.length"
                    >
                        <ChevronRight
                            class=" transition-transform"
                            :class="{ 'rotate-90': open }"
                        />
                    </Button>
                    <IconOctagon
                        class="h-3"
                        :class="{ 'ml-8': node.type === 'module' && !node.children.length }"
                        v-if="node.type === 'module'"
                    />
                    <IconOctagon
                        class="h-3"
                        v-if="node.type === 'group'"
                    />
                    <IconOctagon
                        class="h-2 ml-6"
                        v-if="node.type === 'action'"
                    />
                    <h4
                        class="text-sm font-semibold p-1.5"
                        v-if="node.type === 'module'"
                    >
                        {{ node.name }}
                    </h4>
                    <h4
                        class="text-sm font-semibold ml-1"
                        v-if="node.type === 'group'"
                    >
                        {{ node.name }}
                    </h4>
                    <h4
                        class="text-sm font-semibold p-1"
                        v-if="node.type === 'action'"
                    >
                        {{ node.name }}
                    </h4>
                </div>
            </CollapsibleTrigger>
            <Checkbox
                class="h-5 w-5 p-0 flex items-center justify-center border-2 cursor-pointer"
                :model-value="isIndeterminate ? 'indeterminate' : isChecked"
                @click="handleToggle"
            />
        </div>


        <CollapsibleContent class="flex flex-col ">
            <div
                v-if="node.children?.length"
                class="ml-6 border-l pl-2 space-y-0.5"
            >
                <TreeNode
                    v-for="child in node.children"
                    :key="child.id"
                    :node="child"
                    :selected="selected"
                    @toggle="emit('toggle', $event)"
                />
            </div>
        </CollapsibleContent>
    </Collapsible>

</template>