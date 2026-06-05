<script setup lang="ts">
import DraggableRow from "./DraggableRow.vue"
import { router } from '@inertiajs/vue3';

import { RestrictToVerticalAxis } from "@dnd-kit/abstract/modifiers"
import {
    IconChevronDown,
    IconChevronLeft,
    IconChevronRight,
    IconChevronsLeft,
    IconChevronsRight,
    IconLayoutColumns,
    IconPlus,
} from "@tabler/icons-vue"
import {
    FlexRender,
    getCoreRowModel,
    useVueTable,
    ColumnDef,
    ColumnFiltersState,
    SortingState,
    VisibilityState,
} from "@tanstack/vue-table"
import { DragDropProvider } from "dnd-kit-vue"
import { ref, watch } from 'vue'
import { Button } from "@/components/ui/button"
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu"

import { Label } from "@/components/ui/label"
import { InputGroup, InputGroupInput, InputGroupAddon } from "@/components/ui/input-group"
import { SearchIcon } from '@lucide/vue';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select"
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table"

const emit = defineEmits(['add', 'per-page', 'search'])
const props = withDefaults(defineProps<{
    data: any[]
    columns: ColumnDef<any, any>[]
    buttonText?: string
}>(), {
    buttonText: 'Add Section',
})

const page = ref('10')
const search = ref('')
watch(search, (value) => {
    emit('search', value);
});
watch(page, (value) => {
    emit('per-page', Number(value));
});

const sorting = ref<SortingState>([])
const columnFilters = ref<ColumnFiltersState>([])
const columnVisibility = ref<VisibilityState>({})
const rowSelection = ref({})


const table = useVueTable({
    get data() {
        return props.data.data ?? []
    },
    get columns() {
        return props.columns ?? []
    },
    getCoreRowModel: getCoreRowModel(),
    // getPaginationRowModel: getPaginationRowModel(),
    // getSortedRowModel: getSortedRowModel(),
    // getFilteredRowModel: getFilteredRowModel(),
    // manualFiltering: true,
    onSortingChange: (updaterOrValue) => {
        sorting.value = typeof updaterOrValue === "function"
            ? updaterOrValue(sorting.value)
            : updaterOrValue
    },
    onColumnFiltersChange: (updaterOrValue) => {
        columnFilters.value = typeof updaterOrValue === "function"
            ? updaterOrValue(columnFilters.value)
            : updaterOrValue
    },
    onColumnVisibilityChange: (updaterOrValue) => {
        columnVisibility.value = typeof updaterOrValue === "function"
            ? updaterOrValue(columnVisibility.value)
            : updaterOrValue
    },
    onRowSelectionChange: (updaterOrValue) => {
        rowSelection.value = typeof updaterOrValue === "function"
            ? updaterOrValue(rowSelection.value)
            : updaterOrValue
    },
    state: {
        get sorting() { return sorting.value },
        get columnFilters() { return columnFilters.value },
        get columnVisibility() { return columnVisibility.value },
        get rowSelection() { return rowSelection.value },
    },
})
</script>

<template>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <InputGroup class="sm:w-full lg:w-1/2">
                <InputGroupInput v-model="search" placeholder="Search..." />
                <InputGroupAddon>
                    <SearchIcon />
                </InputGroupAddon>
            </InputGroup>
            <div class="flex items-center gap-2">
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" class="cursor-pointer">
                            <IconLayoutColumns />
                            <span class="hidden lg:inline">Customize Columns</span>
                            <span class="lg:hidden">Columns</span>
                            <IconChevronDown />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end" class="w-56">
                        <template
                            v-for="column in table.getAllColumns().filter((column) => typeof column.accessorFn !== 'undefined' && column.getCanHide())"
                            :key="column.id">
                            <DropdownMenuCheckboxItem class="capitalize" :model-value="column.getIsVisible()"
                                @update:model-value="(value) => {

                                    column.toggleVisibility(!!value)
                                }">
                                {{ column.id }}
                            </DropdownMenuCheckboxItem>
                        </template>
                    </DropdownMenuContent>
                </DropdownMenu>
                <Button variant="outline" class="cursor-pointer" @click="emit('add')">
                    <IconPlus />
                    <span class="hidden lg:inline">{{ buttonText }}</span>
                </Button>
            </div>
        </div>
        <div class="overflow-hidden rounded-lg border">
            <DragDropProvider :modifiers="[RestrictToVerticalAxis]">
                <Table>
                    <TableHeader class="bg-muted sticky top-0 z-10">
                        <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                            <TableHead v-for="header in headerGroup.headers" :key="header.id"
                                :col-span="header.colSpan">
                                <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header"
                                    :props="header.getContext()" />
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody class="**:data-[slot=table-cell]:first:w-8">
                        <template v-if="table.getRowModel().rows.length">
                            <DraggableRow v-for="row in table.getRowModel().rows" :key="row.id" :row="row"
                                :index="row.index" />
                        </template>
                        <TableRow v-else>
                            <TableCell :colspan="columns.length" class="h-24  text-center">
                                <span class="">
                                    No record found
                                </span>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </DragDropProvider>
        </div>
        <div class="flex items-center justify-between ">
            <div class="text-muted-foreground hidden flex-1 text-sm lg:flex">
                {{ table.getFilteredSelectedRowModel().rows.length }} of
                {{ table.getFilteredRowModel().rows.length }} row(s) selected.
            </div>
            <div class="flex w-full items-center gap-8 lg:w-fit">
                <div class="hidden items-center gap-2 lg:flex">
                    <Label for="rows-per-page" class="text-sm font-medium">
                        Rows per page
                    </Label>
                    <Select v-model="page">
                        <SelectTrigger class="w-20" size="sm">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="10"> 10 </SelectItem>
                            <SelectItem value="20"> 20 </SelectItem>
                            <SelectItem value="50"> 50 </SelectItem>
                            <SelectItem value="100"> 100 </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="flex w-fit items-center justify-center text-sm font-medium">
                    Page {{ data?.from ? data?.from : 0 }} of
                    {{ data?.to ? data?.to : 0 }}
                </div>
                <div class="ml-auto flex items-center gap-2 lg:ml-0">
                    <Button variant="outline" class=" cursor-pointer hidden h-8 w-8 p-0 lg:flex"
                        :disabled="!data.prev_page_url" @click="router.visit(data.first_page_url)">
                        <span class="sr-only">Go to first page</span>
                        <IconChevronsLeft />
                    </Button>
                    <Button variant="outline" class=" cursor-pointer size-8" size="icon" :disabled="!data.prev_page_url"
                        @click="router.visit(data.prev_page_url)">
                        <span class="sr-only">Go to previous page</span>
                        <IconChevronLeft />
                    </Button>
                    <Button variant="outline" class=" cursor-pointer size-8" size="icon" :disabled="!data.next_page_url"
                        @click="router.visit(data.next_page_url)">
                        <span class="sr-only">Go to next page</span>
                        <IconChevronRight />
                    </Button>
                    <Button variant="outline" class=" cursor-pointer hidden size-8 lg:flex" size="icon"
                        :disabled="!data.next_page_url" @click="router.visit(data.last_page_url)">
                        <span class=" sr-only">Go to last page</span>
                        <IconChevronsRight />
                    </Button>
                </div>
            </div>
        </div>
    </div>

</template>
