<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
} from '@/components/ui/input-group';
import { SearchIcon, UserPlus } from 'lucide-vue-next';
import { ref, watch } from 'vue';

type Column = {
    label: string;
    key: string;
};

const emit = defineEmits(['create', 'search']);
withDefaults(
    defineProps<{
        columns: Column[];
        rows: Record<string, any>[];
        rowKey?: string;
        canSearch?: boolean;
        canAdd?: boolean;
        buttonText?: string;
        searchValue?: string;
    }>(),
    {
        canSearch: true,
        canAdd: true,
        buttonText: 'Item',
        searchValue: '',
    },
);

const search = ref();

watch(search, (value) => {
    emit('search', value);
});
</script>

<template>
    <div class="mb-4 flex items-center justify-between space-x-4">
        <template v-if="canSearch">
            <InputGroup class="sm:w-full lg:w-1/2">
                <InputGroupInput v-model="search" placeholder="Search..." />
                <InputGroupAddon>
                    <SearchIcon />
                </InputGroupAddon>
            </InputGroup>
        </template>
        <template v-if="canAdd">
            <Button @click="emit('create')" class="cursor-pointer">
                <UserPlus /> Add {{ buttonText }}</Button
            >
        </template>
    </div>
    <div class="relative overflow-x-auto rounded-md border-t border-r border-l">
        <table class="text-body w-full text-left text-sm rtl:text-right">
            <thead class="border-b bg-[#f9fafb] text-sm text-[#586271]">
                <tr>
                    <th
                        scope="col"
                        class="px-6 py-3 font-medium"
                        v-for="col in columns"
                        :key="col.key"
                    >
                        {{ col.label }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="border-b hover:bg-[#f9fafb]"
                    v-for="row in rows"
                    :key="row[rowKey]"
                >
                    <td class="px-6 py-3" v-for="col in columns" :key="col.key">
                        <!-- Slot for custom cell -->
                        <slot
                            :name="`cell-${col.key}`"
                            :row="row"
                            :value="row[col.key]"
                        >
                            {{ row[col.key] }}
                        </slot>
                    </td>
                </tr>
                <tr
                    class="border-b hover:bg-[#f9fafb]"
                    v-if="rows.length === 0"
                >
                    <td class="px-6 py-3 text-center" :colspan="columns.length">
                        <span
                            class="text-xs font-medium text-[#586271] uppercase"
                            >No data found</span
                        >
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
