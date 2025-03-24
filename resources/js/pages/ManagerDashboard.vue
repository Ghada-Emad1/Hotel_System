<script setup lang="ts">
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { computed } from 'vue';

// Get the user permissions from the usePage hook
const page = usePage();
const user = computed(() => page.props.auth.user);
console.log("User DAta",page.props.auth.user.roles);
const can = computed(() => page.props.auth.user.permissions || {});


// Title based on the user role
const roleTitle = computed(() => {
    if (user.value.role === 'admin') return 'Admin Dashboard';
    if (user.value.role === 'manager') return 'Manager Dashboard';
    if (user.value.role === 'receptionist') return 'Receptionist Dashboard';
    return 'Dashboard';
});
</script>

<template>

    <Head :title="roleTitle" />

    <AdminAppLayout>

    </AdminAppLayout>
</template>
