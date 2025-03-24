<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import DataTable from '@/components/DataTable.vue';

defineProps({
    managers: Array,
});

const headers = [
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'national_id', label: 'National ID' },
    { key: 'country', label: 'Country' },
    { key: 'gender', label: 'Gender' },
];

const actions = [
    {
        label: 'Edit',
        handler: (manager) => {
            window.location.href = route('admin.managers.edit', manager.id);
        },
    },
    {
        label: 'Delete',
        handler: (manager) => {
            if (confirm('Are you sure you want to delete this manager?')) {
                window.location.href = route('admin.managers.destroy', manager.id);
            }
        },
    },
];
</script>

<template>
    <Head title="Manage Managers" />

    <AdminAppLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Manage Managers</h1>
                <Link :href="route('admin.managers.create')">
                    <Button>Add Manager</Button>
                </Link>
            </div>

            <!-- DataTable -->
            <DataTable :headers="headers" :data="managers" :actions="actions" />
        </div>
    </AdminAppLayout>
</template>