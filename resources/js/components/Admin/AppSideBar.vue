<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { NavItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { User } from 'lucide-vue-next';
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { BookOpen, Folder } from 'lucide-vue-next';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuItem, SidebarMenuButton, SidebarMenuLink, SidebarMenuLinkButton, SidebarMenuLinkIcon, SidebarMenuLinkText, SidebarMenuLinkTooltip, SidebarGroup, SidebarGroupLabel } from '@/components/ui/sidebar';

import NavMain from '../NavMain.vue';
import NavFooter from '../NavFooter.vue';
import NavUser from '../NavUser.vue';
// Get user data from Inertia.js props
const page = usePage();
const user = computed(() => page.props.auth.user);

// Extract user permissions (fallback to empty array if undefined)
const permissions = computed(() => page.props.auth.user.permissions || []);
console.log('User Permissions:', permissions.value);

const dashboard = {
    title: 'Dashboard',
    href: '/admin/dashboard',
    icon: User,
};

// Define navigation items with required permissions
const mainNav = computed(() => {
    const items: NavItem[] = [dashboard];
    if (permissions.value.includes('manage_managers')) {
        items.push({
            title: 'Manage Managers',
            href: '/admin/managers',
            icon: User,
        });
    }
    if (permissions.value.includes('manage_receptionists')) {
        items.push({
            title: 'Manage Receptionists',
            href: '/admin/receptionists',
            icon: User,
        });
    }
    if (permissions.value.includes('manage_clients')) {
        items.push({
            title: 'Manage Clients',
            href: '/admin/clients',
            icon: User,
        });
    }
    if (permissions.value.includes('manage_floors')) {
        items.push({
            title: 'Manage Floors',
            href: '/admin/floors',
            icon: User,
        });
    }
    if (permissions.value.includes('manage_rooms')) {
        items.push({
            title: 'Manage Rooms',
            href: '/admin/rooms',
            icon: User,
        });
    }
    if (permissions.value.includes('view_reports')) {
        items.push({
            title: 'View Reports',
            href: '/admin/reports',
            icon: User,
        });
    }
    return items;
});


const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits',
        icon: BookOpen,
    },
];
console.log('Main Nav', mainNav.value);
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('admin.dashboard')">
                        <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNav" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>