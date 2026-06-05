<script setup lang="ts">
import {
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuSub,
    SidebarGroup,
    SidebarMenuSubItem,
    SidebarMenuSubButton

} from '@/components/ui/sidebar';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from "@/components/ui/collapsible";
import { useActiveUrl } from '@/composables/useActiveUrl';
import { type NavItem2 } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
    ChevronRight,
} from '@lucide/vue';
defineProps<{
    items: NavItem2[];
}>();

const { urlIsActive } = useActiveUrl();

</script>

<template>
    <SidebarGroup>
        <SidebarGroupLabel>Main Menu</SidebarGroupLabel>
        <SidebarMenu class="space-y-1">
            <template v-for="item in items" :key="item.Label">
                <SidebarMenuButton :is-active="urlIsActive(item.Url)"
                    v-if="!item.Children.length" as-child :tooltip="item.Label">
                    <Link :href="item.Url">
                        <component :is="item.Icon" />
                        <span class="text-sm">{{ item.Label }}</span>
                    </Link>
                </SidebarMenuButton>
                <Collapsible default-open class="group/collapsible " as-child v-else>
                    <CollapsibleTrigger class="cursor-pointer " as-child>
                        <SidebarMenuButton :tooltip="item.Label" class="cursor-pointer ">
                            <component :is="item.Icon" />
                            <span class="text-sm">{{ item.Label }}</span>
                            <ChevronRight class=" ml-auto transition-transform
                                    group-data-[state=open]/collapsible:rotate-90" />
                        </SidebarMenuButton>
                    </CollapsibleTrigger>
                    <CollapsibleContent>
                        <SidebarMenuSub>
                            <SidebarMenuSubItem class="space-y-1 ">
                                <SidebarMenuSubButton class="cursor-pointer" v-for="child in item.Children"
                                    :is-active="urlIsActive(item.Url)" :key="child.Label" as-child>
                                    <Link :href="child.Url">
                                        <span class="text-sm">{{ child.Label }}</span>
                                    </Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        </SidebarMenuSub>
                    </CollapsibleContent>
                </Collapsible>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>
