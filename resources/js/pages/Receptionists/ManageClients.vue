<!-- <script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AddClientModal from './AddClientModal.vue';
import EditClientModal from './EditClientModal.vue';

const props = defineProps({
  clients: Array,
  isManager: Boolean,
  isReceptionist: Boolean,
});

// Create a local reactive copy of clients
const clientsList = ref([...props.clients.data]);

const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedClient = ref(null);
console.log(props.clients);
const openEditModal = (client) => {
  selectedClient.value = client;
  showEditModal.value = true;
};



const approveClient = (id) => {
  if (confirm('Are you sure you want to approve this client?')) {
    router.post(route('client.approve', id), {}, {
      onSuccess: () => {
        // ✅ Check if `clients` is an object with a `data` array
        const clientList = props.clients?.data || [];

        const client = clientList.find(client => client.id === id);
        if (client) {
          client.is_approved = true;
        }
      },
      onError: (errors) => {
        console.error(errors);
        alert('An error occurred while approving the client.');
      },
    });
  }
};



const rejectClient = (id) => {
  router.delete(route('client.reject', id), {
    onSuccess: () => {
      clientsList.value = clientsList.value.filter(client => client.id !== id);
    }
  });
};

const deleteClient = (id) => {
  if (confirm('Are you sure you want to delete this client?')) {
    router.delete(route('client.destroy', id), {
      onSuccess: () => {
        clientsList.value = clientsList.value.filter(client => client.id !== id);
      }
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
            <TableHead v-if="isManager">Approved By</TableHead>
            <TableHead>Actions</TableHead>
            <TableHead>Approved</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="client in clientsList" :key="client.id">
            <TableCell>{{ client.name }}</TableCell>
            <TableCell>{{ client.email }}</TableCell>
            <TableCell>{{ client.country }}</TableCell>
            <TableCell>{{ client.gender }}</TableCell>
            <TableCell v-if="isManager">{{ client.approval?.approved_by?.name || 'Not Approved' }}</TableCell>
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
                Approve
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
</template> -->
