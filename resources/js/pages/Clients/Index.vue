<!-- resources/js/Pages/Admin/Managers/Index.vue -->
<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';

import AddClientModal from './AddClientModal.vue';
import EditClientModal from './EditClientModal.vue';

const props = defineProps({
  clients: Array,
});
const page = usePage();
const user = page.props.auth.user.roles[0];

const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedManager = ref(null);

const openEditModal = (client) => {
  selectedManager.value = client;
  showEditModal.value = true;
};

const deleteManager = (id) => {
  if (confirm('Are you sure you want to delete this client?')) {
    router.delete(route('client.destroy', id));
  }
};
console.log(props.clients);
const approveClient = (id) => {
  if (confirm('Are you sure you want to approve this client?')) {
    router.post(route('client.approve', id), {}, {
      onSuccess: () => {
        // Update the client's approval status in the UI
        const client = props.clients.find(client => client.id === id);
        console.log(client);
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
</script>

<template>
  <Head title="Manage Clients" />

  <AdminAppLayout>
    <div class="p-8 space-y-6">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Manage Clients</h1>
        <Button @click="showAddModal = true">Add Client</Button>
      </div>

      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Name</TableHead>
            <TableHead>Email</TableHead>
            <TableHead>National ID</TableHead>
            <TableHead>Country</TableHead>
            <TableHead>Gender</TableHead>
            <TableHead>Actions</TableHead>
           <TableHead :v-if="user=='receptionist'">Approve Client </TableHead>
          </TableRow>
        </TableHeader>
                <TableBody>
          <TableRow v-for="client in clients" :key="client.id">
            <TableCell>{{ client.name }}</TableCell>
            <TableCell>{{ client.email }}</TableCell>
            <TableCell>{{ client.national_id }}</TableCell>
            <TableCell>{{ client.country }}</TableCell>
            <TableCell>{{ client.gender }}</TableCell>
            <TableCell>
              <Button class="mr-2" @click="openEditModal(client)">Edit</Button>
              <Button variant="destructive" @click="deleteManager(client.id)">Delete</Button>
            </TableCell>
            <TableCell v-if="user === 'receptionist'">
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
      <EditClientModal
        v-if="showEditModal"
        :client="selectedManager"
        @close="showEditModal = false"
      />
    </div>
  </AdminAppLayout>
</template>
