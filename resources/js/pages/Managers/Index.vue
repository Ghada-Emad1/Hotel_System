<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import DataTable from '@/components/ui/data-table.vue'; // Import the reusable DataTable component

import AddManagerModal from './AddManagerModal.vue';
import EditManagerModal from './EditManagerModal.vue';

const props = defineProps({
    managers: Object, // Updated to accept a paginated object
    filters: Object, // Receive filters from the controller
    countries: Array, // Receive countries from the controller
});

const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedManager = ref(null);

const filters = ref({
    search: props.filters.search || '',
    country: props.filters.country || '',
    gender: props.filters.gender || '',
});

const columns = [
    {
        key: 'avatar_image',
        label: 'Avatar',
        format: (value) => value || 'avatar.png', // Return only the image path
    },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'national_id', label: 'National ID' },
    { key: 'country', label: 'Country' },
    { key: 'gender', label: 'Gender' },
];

const openEditModal = (manager) => {
    selectedManager.value = manager;
    showEditModal.value = true;
};

const deleteManager = (id) => {
    if (confirm('Are you sure you want to delete this manager?')) {
        router.delete(route('manager.destroy', { manager: id }), {
            onSuccess: () => {
                console.log('Manager deleted successfully');
            },
            onError: (errors) => {
                console.error('Error deleting manager:', errors);
            },
        });
    }
};

const updatePage = (page) => {
    router.get(route('manager.index', { ...filters.value, page }), {}, { preserveState: true, preserveScroll: true });
};

// Watch filters and send requests dynamically
watch(filters, (newFilters) => {
    router.get(route('manager.index', {
        search: newFilters.search,
        country: newFilters.country,
        gender: newFilters.gender
    }), {}, {
        preserveState: true,
        preserveScroll: true,
    });
}, { deep: true });
</script>

<template>

    <Head title="Manage Managers" />

    <AdminAppLayout>
        <div class="p-8 space-y-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Manage Managers</h1>
                <Button variant="default" class="px-4 py-2" @click="showAddModal = true">
                    Add Manager
                </Button>
            </div>

            <!-- Filters Section -->
            <div class="flex space-x-4 mb-4">
                <input v-model="filters.search" type="text" placeholder="Search by name, email, or ID"
                    class="border rounded px-4 py-2" />
                <select v-model="filters.country" class="border rounded px-4 py-2">
                    <option value="">All Countries</option>
                    <option v-for="country in countries" :key="country" :value="country">
                        {{ country }}
                    </option>
                </select>
                <select v-model="filters.gender" class="border rounded px-4 py-2">
                    <option value="">All Genders</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <!-- Reusable DataTable Component -->
            <DataTable :data="managers.data" :columns="columns" :manualPagination="true"
                :currentPage="managers.current_page" :perPage="managers.per_page" :total="managers.total"
                @page-change="updatePage">
                <template #actions="{ row }">
                    <div class="flex gap-2">
                        <Button variant="outline" size="sm" @click="openEditModal(row)">
                            Edit
                        </Button>
                        <Button variant="destructive" size="sm" @click="deleteManager(row.id)">
                            Delete
                        </Button>
                    </div>
                </template>
            </DataTable>

            <!-- Modals -->
            <AddManagerModal v-if="showAddModal" :countries="countries" @close="showAddModal = false" />
            <EditManagerModal v-if="showEditModal" :manager="selectedManager" :countries="countries"
                @close="showEditModal = false" />
        </div>
    </AdminAppLayout>
</template>
