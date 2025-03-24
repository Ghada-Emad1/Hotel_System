<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AddRoomModal from './AddRoomModal.vue';
import EditRoomModal from './EditRoomModal.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

const props = defineProps({
  rooms: Array,
  floors: Array,
  isAdmin: Boolean,
  userId: Number,
});

const showAddModal = ref(false);
const selectedRoom = ref(null);

const openEditModal = (room) => {
  selectedRoom.value = room;
};

const deleteRoom = (roomId) => {
  if (confirm('Are you sure you want to delete this room?')) {
    useForm().delete(route('rooms.destroy', roomId));
  }
};
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Manage Rooms</h1>

    <button @click="showAddModal = true" class="bg-green-600 text-white px-4 py-2 rounded mb-4">
      Add Room
    </button>

    <Table class="border">
      <TableHeader>
        <TableRow>
          <TableHead>Floor</TableHead>
          <TableHead>Number</TableHead>
          <TableHead>Capacity</TableHead>
          <TableHead>Price</TableHead>
          <TableHead v-if="isAdmin">Manager</TableHead>
          <TableHead v-if="isAdmin || rooms.some(room => room.manager_id === userId)">Actions</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="room in rooms" :key="room.id">
          <TableCell>{{ room.floor.name }}</TableCell>
          <TableCell>{{ room.number }}</TableCell>
          <TableCell>{{ room.capacity }}</TableCell>
          <TableCell>${{ (room.price / 100).toFixed(2) }}</TableCell>
          <TableCell v-if="isAdmin">{{ room.manager?.name }}</TableCell>
          <TableCell v-if="isAdmin || room.manager_id === userId">
            <button class="text-blue-600 mr-2" @click="openEditModal(room)">Edit</button>
            <button class="text-red-600" @click="deleteRoom(room.id)">Delete</button>
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>

    <AddRoomModal v-if="showAddModal" :floors="floors" @close="showAddModal = false" />
    <EditRoomModal v-if="selectedRoom" :room="selectedRoom" :floors="floors" @close="selectedRoom = null" />
  </div>
</template>
