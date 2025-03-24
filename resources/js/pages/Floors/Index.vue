<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AddFloorModal from './AddFloorModal.vue';
import EditFloorModal from './EditFloorModal.vue';
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';

const props = defineProps({
  floors: Array,
  isAdmin: Boolean,
});

const showAddModal = ref(false);
const selectedFloor = ref(null);

const openEditModal = (floor) => {
  selectedFloor.value = floor;
};

const deleteFloor = (floorId) => {
  if (confirm('Are you sure you want to delete this floor?')) {
    useForm().delete(route('floors.destroy', floorId));
  }
};
</script>

<template>
 <AdminAppLayout>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Manage Floors</h1>

    <button @click="showAddModal = true" class="bg-green-600 text-white px-4 py-2 rounded mb-4">
      Add Floor
    </button>

    <table class="w-full border">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2">Name</th>
          <th class="p-2">Number</th>
          <th v-if="isAdmin" class="p-2">Manager</th>
          <th class="p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="floor in floors" :key="floor.id">
          <td class="p-2">{{ floor.name }}</td>
          <td class="p-2">{{ floor.number }}</td>
          <td v-if="isAdmin" class="p-2">{{ floor.manager?.name }}</td>
          <td class="p-2 space-x-2">
            <button class="text-blue-600" @click="openEditModal(floor)">Edit</button>
            <button class="text-red-600" @click="deleteFloor(floor.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <AddFloorModal v-if="showAddModal" @close="showAddModal = false" />
    <EditFloorModal v-if="selectedFloor" :floor="selectedFloor" @close="selectedFloor = null" />
  </div>
  </AdminAppLayout> 
</template>
