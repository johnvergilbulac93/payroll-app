<script setup lang="ts">
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useActiveUrl } from '@/composables/useActiveUrl';
import { dashboard } from '@/routes';
import { index as attendanceIndex } from '@/routes/attendance/processing';
import { index as employeeIndex } from '@/routes/employees';
import { index as userIndex } from '@/routes/user';

import { Link } from '@inertiajs/vue3';
import {
    Banknote,
    FileMinus2,
    FolderCog,
    LayoutDashboard,
    Shield,
    UserCog,
    Users,
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const { urlIsActive } = useActiveUrl();
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <!-- <NavMain :items="mainNavItems" /> -->
            <SidebarGroup class="px-2 py-0">
                <SidebarGroupLabel>Main Menu</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            as-child
                            :is-active="urlIsActive(dashboard())"
                            tooltip="Dashboard"
                        >
                            <Link :href="dashboard()">
                                <LayoutDashboard />
                                <span>Dashboard</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
            <SidebarGroup class="px-2 py-0">
                <SidebarGroupLabel>System Administration</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            as-child
                            tooltip="User Maintenance"
                            :is-active="urlIsActive(userIndex())"
                        >
                            <Link :href="userIndex()">
                                <Users />
                                <span>User Maintenance</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                    <SidebarMenuItem>
                        <SidebarMenuButton as-child tooltip="Access Rights">
                            <Link :href="dashboard()">
                                <Shield />
                                <span>Access Rights</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>

            <SidebarGroup class="px-2 py-0">
                <SidebarGroupLabel>Table Maintenance</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton as-child tooltip="W/Tax">
                            <Link :href="dashboard()">
                                <FileMinus2 />
                                <span>W/Tax</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                    <SidebarMenuItem>
                        <SidebarMenuButton as-child tooltip="SSS Premium">
                            <Link :href="dashboard()">
                                <FileMinus2 />
                                <span>SSS Premium</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                    <SidebarMenuItem>
                        <SidebarMenuButton as-child tooltip="PhilHeath Premium">
                            <Link :href="dashboard()">
                                <FileMinus2 />
                                <span>PhilHeath Premium</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>

            <SidebarGroup class="px-2 py-0">
                <SidebarGroupLabel class=""
                    >Master File Maintenance</SidebarGroupLabel
                >
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton as-child tooltip="Employee 201">
                            <Link :href="employeeIndex()">
                                <Users />
                                <span>Employee 201</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                    <SidebarMenuItem>
                        <SidebarMenuButton as-child tooltip="Loans">
                            <Link :href="dashboard()">
                                <Banknote />
                                <span>Loans</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
            <SidebarGroup class="px-2 py-0">
                <SidebarGroupLabel>Attendance Processing</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            as-child
                            tooltip="Process Attendance"
                        >
                            <Link :href="attendanceIndex()">
                                <UserCog />
                                <span>Process Attendance</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
            <SidebarGroup class="px-2 py-0">
                <SidebarGroupLabel>Payroll Processing</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            as-child
                            tooltip="Payroll Computations & Append to Paymaster Table"
                        >
                            <Link :href="dashboard()">
                                <FolderCog />
                                <span
                                    >Payroll Computations & Append to Paymaster
                                    Table</span
                                >
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            as-child
                            tooltip="Update Employee Payroll & Deduction History Tables"
                        >
                            <Link :href="dashboard()">
                                <FolderCog />
                                <span
                                    >Update Employee Payroll & Deduction History
                                    Tables</span
                                >
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
