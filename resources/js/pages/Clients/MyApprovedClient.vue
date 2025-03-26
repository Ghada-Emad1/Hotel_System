
<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';


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
    router.post(route('receptionist.myapprove_clients..approve', id), {}, {
      onSuccess: () => {
        // Update the client's approval status in the UI
        const client = props.clients.data.find(client => client.id === id);
        if (client) {
          client.is_approved = true; // Update the UI
        }
      },
      onError: (errors) => {
        console.error(errors);
        alert('An error occurred while approving the client.');
      },
    });
  }
};



</script>

<template>
  <AdminAppLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">UnApproved Clients</h1>

      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Name</TableHead>
            <TableHead>Email</TableHead>
            <TableHead>Country</TableHead>
            <TableHead>Gender</TableHead>
            <TableHead v-if="isManager">Approved By</TableHead>
            <TableHead>Approved</TableHead>
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

     </div>
  </AdminAppLayout>
</template>