<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AddRoomModal from './AddRoomModal.vue';
import EditRoomModal from './EditRoomModal.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button'; // ShadCN Button component
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';

const props = defineProps({
  rooms: Object, // Updated to handle paginated data
  floors: Array,
  userId: Number,
  isAdmin: Boolean,
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

// Function to check if the user can modify a room
const canModifyRoom = (room) => {
  return props.isAdmin || room.manager_id === props.userId;
};

// Function to get the manager name or show "Admin" if created by an admin
const getManagerName = (room) => {
  return room.manager ? room.manager.name : 'Admin';
};

// Pagination function
const updatePage = (page) => {
  useForm().get(route('rooms.index', { page }), {}, { preserveState: true, preserveScroll: true });
};
</script>

<template>
  <AdminAppLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Manage Rooms</h1>

      <Button v-if="isAdmin || userId" variant="default" class="mb-4" @click="showAddModal = true">
        Add Room
      </Button>

      <Table class="border">
        <TableHeader>
          <TableRow>
            <TableHead>Floor</TableHead>
            <TableHead>Number</TableHead>
            <TableHead>Capacity</TableHead>
            <TableHead>Price</TableHead>
            <TableHead v-if="isAdmin">Manager</TableHead>
            <TableHead v-if="isAdmin || rooms.data.some(room => room.manager_id === userId)">Actions</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="room in rooms.data" :key="room.id">
            <TableCell>{{ room.floor.name }}</TableCell>
            <TableCell>{{ room.number }}</TableCell>
            <TableCell>{{ room.capacity }}</TableCell>
            <TableCell>${{ (room.price / 100).toFixed(2) }}</TableCell>
            <TableCell v-if="isAdmin">
              {{ getManagerName(room) }}
            </TableCell>
            <TableCell v-if="isAdmin || room.manager_id === userId">
              <Button variant="outline" size="sm" class="mr-2" @click="openEditModal(room)">
                Edit
              </Button>
              <Button variant="destructive" size="sm" @click="deleteRoom(room.id)">
                Delete
              </Button>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>

      <!-- Pagination Controls -->
      <div class="flex items-center justify-between mt-4">
        <Button
          variant="outline"
          size="sm"
          @click="updatePage(rooms.current_page - 1)"
          :disabled="rooms.current_page === 1"
        >
          Previous
        </Button>
        <span class="text-sm text-muted-foreground">
          Page {{ rooms.current_page }} of {{ rooms.last_page }}
        </span>
        <Button
          variant="outline"
          size="sm"
          @click="updatePage(rooms.current_page + 1)"
          :disabled="rooms.current_page === rooms.last_page"
        >
          Next
        </Button>
      </div>

      <AddRoomModal v-if="showAddModal" :floors="floors" @close="showAddModal = false" />
      <EditRoomModal v-if="selectedRoom" :room="selectedRoom" :floors="floors" @close="selectedRoom = null" />
    </div>
  </AdminAppLayout>
</template>
