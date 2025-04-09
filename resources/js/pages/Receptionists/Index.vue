<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import DataTable from '@/components/ui/data-table.vue'; // Import the reusable DataTable component
import AddReceptionistModal from './AddReceptionistModal.vue';
import EditReceptionistModal from './EditReceptionist.vue';
import { Button } from '@/components/ui/button';

const props = defineProps({
    receptionists: Object,
    isAdmin: Boolean,
    userId: Number,
    countries: Array, // Receive countries from the controller
    filters: Object, // Receive filters from the controller
});

const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedReceptionist = ref(null);

const filters = ref({
    search: props.filters.search || '',
    country: props.filters.country || '',
    gender: props.filters.gender || '',
});

const columns = [
    {
        key: 'avatar_image',
        label: 'Avatar',
        format: (value) =>
            value
                ? `<img src="/storage/avatars/${value}" class="w-10 h-10 rounded-full border" alt="Avatar" />`
                : `<img src="/storage/avatars/avatar.png" class="w-10 h-10 rounded-full border" alt="Avatar" />`,
    },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'national_id', label: 'National ID' },
    { key: 'created_at_formatted', label: 'Created At' },
    {
        key: 'manager.name',
        label: 'Manager',
        visible: props.isAdmin,
        format: (value) => value || 'Admin',
    },
];

const openEditModal = (receptionist) => {
    selectedReceptionist.value = receptionist;
    showEditModal.value = true;
};

const deleteReceptionist = (id) => {
    if (confirm('Are you sure you want to delete this receptionist?')) {
        router.delete(route('receptionist.destroy', id));
    }
};

const toggleBan = (receptionist) => {
    const action = receptionist.banned_at ? 'unban' : 'ban';
    router.post(route(`receptionist.${action}`, receptionist.id), {
        preserveScroll: true,
        onSuccess: () => {
            receptionist.banned_at = receptionist.banned_at ? null : new Date();
        },
    });
};

const updatePage = (page) => {
    router.get(route('receptionist.index', { ...filters.value, page }), {}, { preserveState: true, preserveScroll: true });
};

// Watch filters and send requests dynamically
watch(filters, (newFilters) => {
    router.get(route('receptionist.index', {
        search: newFilters.search,
        country: newFilters.country,
        gender: newFilters.gender,
    }), {}, {
        preserveState: true,
        preserveScroll: true,
    });
}, { deep: true });
</script>

<template>
    <AdminAppLayout>
        <div class="p-6 space-y-6">
            <!-- Header Section -->
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Manage Receptionists</h1>
                <Button variant="default" class="px-4 py-2" @click="showAddModal = true">
                    Add Receptionist
                </Button>
            </div>

            <!-- Filters Section -->
            <div class="flex flex-wrap gap-4 mb-4">
                <input
                    v-model="filters.search"
                    type="text"
                    placeholder="Search by name, email, or ID"
                    class="border rounded px-4 py-2 w-full md:w-auto"
                />
                <select v-model="filters.country" class="border rounded px-4 py-2 w-full md:w-auto">
                    <option value="">All Countries</option>
                    <option v-for="country in countries" :key="country" :value="country">
                        {{ country }}
                    </option>
                </select>
                <select v-model="filters.gender" class="border rounded px-4 py-2 w-full md:w-auto">
                    <option value="">All Genders</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <!-- Reusable DataTable Component -->
            <DataTable
                :data="receptionists.data"
                :columns="columns"
                :manualPagination="true"
                :currentPage="receptionists.current_page"
                :perPage="receptionists.per_page"
                :total="receptionists.total"
                @page-change="updatePage"
            >
                <template #actions="{ row }">
                    <div class="flex gap-2">
                        <!-- Edit Button -->
                        <Button variant="outline" size="sm" @click="openEditModal(row)">
                            Edit
                        </Button>

                        <!-- Ban/Unban Button -->
                        <button
                            :class="row.banned_at ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-yellow-500 hover:bg-yellow-600 text-white'"
                            class="px-2 py-1 rounded text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2"
                            @click="toggleBan(row)"
                        >
                            {{ row.banned_at ? 'Unban' : 'Ban' }}
                        </button>

                        <!-- Delete Button -->
                        <Button variant="destructive" size="sm" @click="deleteReceptionist(row.id)">
                            Delete
                        </Button>
                    </div>
                </template>
            </DataTable>

            <!-- Modals -->
            <AddReceptionistModal v-if="showAddModal" :countries="countries" @close="showAddModal = false" />
            <EditReceptionistModal v-if="showEditModal" :receptionist="selectedReceptionist" :countries="countries" @close="showEditModal = false" />
        </div>
    </AdminAppLayout>
</template>
