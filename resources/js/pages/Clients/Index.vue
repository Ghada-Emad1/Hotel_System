<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AddClientModal from './AddClientModal.vue';
import EditClientModal from './EditClientModal.vue';

const props = defineProps({
  clients: Object,
  isManager: Boolean,
  isReceptionist: Boolean,
});

const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedClient = ref(null);

const openEditModal = (client) => {
  selectedClient.value = client;
  showEditModal.value = true;
};

const approveClient = (id) => {
  router.post(route('client.approve', id));
};

const rejectClient = (id) => {
  router.delete(route('client.reject', id));
};
</script>

<template>
  <AdminAppLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Manage Clients</h1>

      <button @click="showAddModal = true" class="mb-4 bg-green-600 text-white px-4 py-2 rounded">
        Add Client
      </button>

      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Name</TableHead>
            <TableHead>Email</TableHead>
            <TableHead>Country</TableHead>
            <TableHead>Gender</TableHead>
            <TableHead v-if="isManager">Approved By</TableHead>
            <TableHead>Actions</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="client in clients.data" :key="client.id">
            <TableCell>{{ client.name }}</TableCell>
            <TableCell>{{ client.email }}</TableCell>
            <TableCell>{{ client.country }}</TableCell>
            <TableCell>{{ client.gender }}</TableCell>
            <TableCell v-if="isManager">{{ client.approval?.approved_by?.name || 'Not Approved' }}</TableCell>
            <TableCell>
              <button @click="openEditModal(client)" class="bg-blue-500 text-white px-2 py-1 rounded">
                Edit
              </button>
              <button v-if="isReceptionist && !client.approval" @click="approveClient(client.id)" class="bg-green-500 text-white px-2 py-1 ml-2 rounded">
                Approve
              </button>
              <button v-if="isManager && client.approval" @click="rejectClient(client.id)" class="bg-red-500 text-white px-2 py-1 ml-2 rounded">
                Reject
              </button>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>

      <AddClientModal v-if="showAddModal" @close="showAddModal = false" />
      <EditClientModal v-if="showEditModal" :client="selectedClient" @close="showEditModal = false" />
    </div>
  </AdminAppLayout>
</template>
