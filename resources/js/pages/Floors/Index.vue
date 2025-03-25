<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AddFloorModal from './AddFloorModal.vue';
import EditFloorModal from './EditFloorModal.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
const props = defineProps({
  floors: Array,
  userId: Number,
  isAdmin: Boolean,
});

const showAddModal = ref(false);
const selectedFloor = ref(null);

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
</script>

<template>
    <AdminAppLayout>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Manage Floors</h1>

    <button @click="showAddModal = true" class="bg-green-600 text-white px-4 py-2 rounded mb-4">
      Add Floor
    </button>

    <Table class="border">
      <TableHeader>
        <TableRow>
          <TableHead>Name</TableHead>
          <TableHead>Number</TableHead>
          <TableHead v-if="isAdmin">Manager</TableHead>
          <TableHead v-if="isAdmin || floors.some(floor => floor.manager_id === userId)">Actions</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="floor in floors" :key="floor.id">
          <TableCell>{{ floor.name }}</TableCell>
          <TableCell>{{ floor.number }}</TableCell>
          <TableCell v-if="isAdmin">
            {{ floor.manager ? floor.manager.name : 'Admin' }}
          </TableCell>
          <TableCell v-if="canModifyFloor(floor)">
            <button class="text-blue-600 mr-2" @click="openEditModal(floor)">Edit</button>
            <button class="text-red-600" @click="deleteFloor(floor.id)">Delete</button>
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>

    <AddFloorModal v-if="showAddModal" @close="showAddModal = false" />
    <EditFloorModal v-if="selectedFloor" :floor="selectedFloor" @close="selectedFloor = null" />
  </div>
</AdminAppLayout>
</template>
