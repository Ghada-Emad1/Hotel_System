<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AddFloorModal from './AddFloorModal.vue';
import EditFloorModal from './EditFloorModal.vue';
import { Button } from '@/components/ui/button'; // ShadCN Button component
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import DataTable from '@/components/ui/data-table.vue'; // Import the reusable DataTable component

const props = defineProps({
    floors: Object, // Pagination object
    userId: Number,
    isAdmin: Boolean,
    filters: Object, // Receive filters from the controller
    managers: Array, // Receive managers list from the controller
});

const showAddModal = ref(false);
const selectedFloor = ref(null);

const filters = ref({
    search: props.filters.search || '',
    manager_id: props.filters.manager_id || '',
});

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'number', label: 'Number' },
    {
        key: 'manager.name',
        label: 'Manager',
        format: (value) => value || 'Admin',
        visible: props.isAdmin,
    },
];

const openEditModal = (floor) => {
    selectedFloor.value = floor;
};

const deleteFloor = (floorId) => {
    if (confirm('Are you sure you want to delete this floor?')) {
        useForm().delete(route('floor.destroy', floorId));
    }
};

const canModifyFloor = (floor) => {
    return props.isAdmin || floor.manager_id === props.userId;
};

const updatePage = (page) => {
    useForm().get(route('floor.index', { ...filters.value, page }), {}, { preserveState: true, preserveScroll: true });
};

// Watch filters and send requests dynamically
watch(filters, (newFilters) => {
    useForm().get(route('floor.index', {
        search: newFilters.search,
        manager_id: newFilters.manager_id,
    }), {}, {
        preserveState: true,
        preserveScroll: true,
    });
}, { deep: true });
</script>

<template>
    <AdminAppLayout>
        <div class="p-8 space-y-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Manage Floors</h1>
                <Button variant="default" class="px-6 py-3" @click="showAddModal = true">
                    Add Floor
                </Button>
            </div>

            <!-- Filters Section -->
            <div class="flex space-x-4 mb-6">
                <input v-model="filters.search" type="text" placeholder="Search by name or number"
                    class="border rounded px-4 py-3 text-sm" />
                <!-- Show the manager filter only if the user is an admin -->
                <select v-if="isAdmin" v-model="filters.manager_id" class="border rounded px-4 py-3 text-sm">
                    <option value="">All Managers</option>
                    <option v-for="manager in managers" :key="manager.id" :value="manager.id">
                        {{ manager.name }}
                    </option>
                </select>
            </div>

            <!-- Reusable DataTable Component -->
            <DataTable :data="floors.data" :columns="columns" :manualPagination="true"
                :currentPage="floors.current_page" :perPage="floors.per_page" :total="floors.total"
                @page-change="updatePage">
                <template #actions="{ row }">
                    <Button v-if="canModifyFloor(row)" variant="outline" size="sm" class="mr-2"
                        @click="openEditModal(row)">
                        Edit
                    </Button>
                    <Button v-if="canModifyFloor(row)" variant="destructive" size="sm" @click="deleteFloor(row.id)">
                        Delete
                    </Button>
                </template>
            </DataTable>

            <AddFloorModal v-if="showAddModal" @close="showAddModal = false" />
            <EditFloorModal v-if="selectedFloor" :floor="selectedFloor" @close="selectedFloor = null" />
        </div>
    </AdminAppLayout>
</template>
