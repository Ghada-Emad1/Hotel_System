<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AddReceptionistModal from './AddReceptionistModal.vue';
import EditReceptionistModal from './EditReceptionist.vue';
import { formatDate } from '@vueuse/core';

const props = defineProps({
    receptionists: Object,
    isAdmin: Boolean,
    userId: Number
});

const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedReceptionist = ref(null);

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
    }
  });
};

</script>

<template>
    <AdminAppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">Manage Receptionists</h1>

            <button @click="showAddModal = true" class="mb-4 bg-green-600 text-white px-4 py-2 rounded">
                Add Receptionist
            </button>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>National ID</TableHead>
                        <TableHead>Created At</TableHead>
                        <TableHead v-if="isAdmin">Manager</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="receptionist in receptionists.data" :key="receptionist.id">
                        <TableCell>{{ receptionist.name }}</TableCell>
                        <TableCell>{{ receptionist.email }}</TableCell>
                        <TableCell>{{ receptionist.national_id }}</TableCell>
                        <TableCell>{{ receptionist.created_at }}</TableCell>
                        <TableCell v-if="isAdmin">{{ receptionist.manager ? receptionist.manager.name : 'Admin' }}
                        </TableCell>
                        <TableCell v-if="isAdmin || receptionist.manager_id === userId">
                            <button @click="openEditModal(receptionist)"
                                class="bg-blue-500 text-white px-2 py-1 rounded">
                                Edit
                            </button>
                            <button @click="toggleBan(receptionist)" class="bg-yellow-500 text-white px-2 py-1 mx-2 rounded">
  {{ receptionist.banned_at ? 'Unban' : 'Ban' }}
</button>


                            <button @click="deleteReceptionist(receptionist.id)"
                                class="bg-red-500 text-white px-2 py-1 rounded">
                                Delete
                            </button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <AddReceptionistModal v-if="showAddModal" @close="showAddModal = false" />
            <EditReceptionistModal v-if="showEditModal" :receptionist="selectedReceptionist"
                @close="showEditModal = false" />
        </div>
    </AdminAppLayout>
</template>
