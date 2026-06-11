<script setup lang="ts">
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,

} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as attendanceIndex } from '@/routes/attendance/processing';
import { index as employeeIndex } from '@/routes/employees';
import { index as userIndex } from '@/routes/users/accounts';
import { index as loanIndex } from '@/routes/loan';
import { Link } from '@inertiajs/vue3';
import {
    FileMinus2,
    FolderCog,
    LayoutDashboard,
    Shield,
    UserCog,
    Users,
    Settings,
} from '@lucide/vue';
import AppLogo from './AppLogo.vue';
import NavMain from './NavMain.vue';


const navItems = [
    {
        Label: "Dashboard",
        Icon: LayoutDashboard,
        Url: dashboard(),
        Children: []
    },
    {
        Label: "User Management",
        Icon: UserCog,
        Url: userIndex(),
        Children: [],
    },
    {
        Label: "Deduction Management",
        Icon: FileMinus2,
        Url: '',
        Children: [],
    },
    {
        Label: "Employee Management",
        Icon: Users,
        Url: '',
        Children: [
            {
                Label: "Employee 201",
                Url: employeeIndex(),
            },
            {
                Label: "Employee Loan",
                Url: loanIndex(),
            }
        ],
    },
    {
        Label: "Attendance Processing",
        Icon: Settings,
        Url: attendanceIndex(),
        Children: [],
    },
    {
        Label: "Payroll Processing",
        Icon: FolderCog,
        Url: '',
        Children: [],
    },

];
</script>

<template>
    <Sidebar
        collapsible="icon"
        variant="floating"
    >
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        size="lg"
                        as-child
                    >
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>
        <SidebarContent>
            <NavMain :items="navItems" />
        </SidebarContent>
        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
