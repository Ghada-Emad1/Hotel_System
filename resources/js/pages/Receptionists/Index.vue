<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { computed, defineProps } from 'vue';
import { Button } from '@/components/ui/button';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';

import AddReceptionistModal from './AddReceptionistModal.vue';
import EditReceptionist from './EditReceptionist.vue';
const page = usePage();
const user = computed(() => page.props.auth.user.roles[0]);
console.log(user.value);
const permissions = computed(() => page.props.auth.user.permissions || []);
const props = defineProps({
  receptionists: Array,
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

const banReceptionist = (id) => {
  if (confirm('Are you sure you want to ban this receptionist?')) {
    router.post(route('receptionist.ban', id));
  }
};

const unbanReceptionist = (id) => {
  if (confirm('Are you sure you want to unban this receptionist?')) {
    router.post(route('receptionist.unban', id));
  }
};
</script>

<template>
  <Head title="Manage Receptionists" />

  <AdminAppLayout>
    <div class="p-8 space-y-6">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Manage Receptionists</h1>
        <Button @click="showAddModal = true">Add Receptionist</Button>
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
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="receptionist in receptionists" :key="receptionist.id">
            <TableCell>{{ receptionist.name }}</TableCell>
            <TableCell>{{ receptionist.email }}</TableCell>
            <TableCell>{{ receptionist.national_id }}</TableCell>
            <TableCell>{{ receptionist.country }}</TableCell>
            <TableCell>{{ receptionist.gender }}</TableCell>
            <TableCell>
              <Button class="mr-2" @click="openEditModal(receptionist)">Edit</Button>
              <Button variant="destructive" class="mr-2" @click="deleteReceptionist(receptionist.id)">Delete</Button>
              <template v-if="user.value === 'receptionist'">
                <Button v-if="!receptionist.is_banned" variant="warning" @click="banReceptionist(receptionist.id)">Ban</Button>
                <Button v-else variant="success" @click="unbanReceptionist(receptionist.id)">Unban</Button>
              </template>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>

      <AddReceptionistModal v-if="showAddModal" @close="showAddModal = false" />
      <EditReceptionist
        v-if="showEditModal"
        :receptionist="selectedReceptionist"
        @close="showEditModal = false"
      />
    </div>
  </AdminAppLayout>
</template>