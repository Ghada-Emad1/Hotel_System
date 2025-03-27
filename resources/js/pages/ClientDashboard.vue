<script setup lang="ts">

import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';

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
        
        Client Dashbaord
    </AdminAppLayout>
</template>