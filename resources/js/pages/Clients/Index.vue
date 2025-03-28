<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AddClientModal from './AddClientModal.vue';
import EditClientModal from './EditClientModal.vue';

const props = defineProps({
  clients: Object, // Pagination object
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
  if (confirm('Are you sure you want to approve this client?')) {
    router.post(route('receptionist_client.approve', id), {}, {
      onSuccess: () => {
        // Reload only the 'clients' data from the server
        router.reload({ only: ['clients'] });
      },
      onError: (errors) => {
        console.error(errors);
        alert('An error occurred while approving the client.');
      },
    });
  }
};

const deleteClient = (id) => {
  if (confirm('Are you sure you want to delete this client?')) {
    router.delete(route('receptionist_client.destroy', id), {
      onSuccess: () => {
        router.reload({ only: ['clients'] });
      },
      onError: (errors) => {
        console.error(errors);
        alert('An error occurred while deleting the client.');
      },
    });
  }
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
            <TableHead>Actions</TableHead>
            <TableHead>Approved</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="client in clients.data" :key="client.id">
            <TableCell>{{ client.name }}</TableCell>
            <TableCell>{{ client.email }}</TableCell>
            <TableCell>{{ client.country }}</TableCell>
            <TableCell>{{ client.gender }}</TableCell>
           
            <TableCell>
              <Button class="mr-2" @click="openEditModal(client)">Edit</Button>
              <Button variant="destructive" @click="deleteClient(client.id)">Delete</Button>
            </TableCell>
            <TableCell>
              <Button
                v-if="!client.is_approved"
                variant="success"
                @click="approveClient(client.id)"
              >
                Pending
              </Button>
              <span v-else class="text-green-600 font-bold">Approved</span>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>

      <AddClientModal v-if="showAddModal" @close="showAddModal = false" />
      <EditClientModal v-if="showEditModal" :client="selectedClient" @close="showEditModal = false" />
    </div>
  </AdminAppLayout>
</template>