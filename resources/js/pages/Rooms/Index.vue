<template>
  <AdminAppLayout>
    <div class="p-8 space-y-6">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Manage Rooms</h1>
        <Button v-if="props.isAdmin || props.userId" variant="default" class="px-6 py-3" @click="showAddModal = true">
          Add Room
        </Button>
      </div>

      <!-- Filters -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <input
          v-model="filters.search"
          type="text"
          placeholder="Search by number, capacity, or floor"
          class="border rounded px-4 py-3 text-sm"
        />
        <select v-model="filters.floor_id" class="border rounded px-4 py-3 text-sm">
          <option value="">All Floors</option>
          <option v-for="floor in floors" :key="floor.id" :value="floor.id">
            {{ floor.name }}
          </option>
        </select>
        <!-- Show "All Managers" filter only for admins -->
        <select v-if="props.isAdmin" v-model="filters.manager_id" class="border rounded px-4 py-3 text-sm">
          <option value="">All Managers</option>
          <option v-for="manager in managers" :key="manager.id" :value="manager.id">
            {{ manager.name }}
          </option>
        </select>
      </div>

      <!-- DataTable -->
      <DataTable
        :columns="[
          { label: 'Floor', key: 'floor.name' },
          { label: 'Number', key: 'number' },
          { label: 'Capacity', key: 'capacity' },
          { label: 'Price', key: 'price', format: (value) => `$${(value / 100).toFixed(2)}` },
          { label: 'Manager', key: 'manager.name' },
        ]"
        :data="filteredRooms"
        :manualPagination="true"
        :total="rooms.total"
        :currentPage="rooms.current_page"
        :perPage="rooms.per_page"
        @page-change="updatePage"
      >
        <template #actions="{ row }">
          <!-- Render actions only if the user can modify the room -->
          <div v-if="canModifyRoom(row)">
            <Button variant="outline" size="sm" class="mr-2" @click="openEditModal(row)">
              Edit
            </Button>
            <Button variant="destructive" size="sm" @click="deleteRoom(row.id)">
              Delete
            </Button>
          </div>
        </template>
      </DataTable>

      <AddRoomModal v-if="showAddModal" :floors="floors" @close="showAddModal = false" />
      <EditRoomModal v-if="selectedRoom" :room="selectedRoom" :floors="floors" @close="selectedRoom = null" />
    </div>
  </AdminAppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AddRoomModal from './AddRoomModal.vue';
import EditRoomModal from './EditRoomModal.vue';
import DataTable from '@/components/ui/data-table.vue'; // Ensure the correct path and extension
import { Button } from '@/components/ui/button'; // ShadCN Button component
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';

const props = defineProps({
  rooms: Object, // Updated to handle paginated data
  floors: Array,
  managers: Array, // Receive managers for filtering
  userId: Number,
  isAdmin: Boolean,
  filters: Object, // Receive filters from the controller
});

const showAddModal = ref(false);
const selectedRoom = ref(null);

const filters = ref({
  search: props.filters.search || '',
  floor_id: props.filters.floor_id || '',
  manager_id: props.filters.manager_id || '',
});

// Function to determine if the user can modify a room
const canModifyRoom = (room) => {
  // Allow only admins or the manager who created the room
  return props.isAdmin || (room.manager_id === props.userId);
};

// Filter rooms dynamically based on the filters (but show all rooms to both admins and managers)
const filteredRooms = computed(() => {
  let rooms = props.rooms.data;

  // Apply search filter
  if (filters.value.search) {
    const search = filters.value.search.toLowerCase();
    rooms = rooms.filter((room) =>
      room.number.toString().includes(search) ||
      room.capacity.toString().includes(search) ||
      room.floor.name.toLowerCase().includes(search)
    );
  }

  // Apply floor filter
  if (filters.value.floor_id) {
    rooms = rooms.filter((room) => room.floor_id === parseInt(filters.value.floor_id));
  }

  // Apply manager filter (only for admins)
  if (props.isAdmin && filters.value.manager_id) {
    rooms = rooms.filter((room) => room.manager_id === parseInt(filters.value.manager_id));
  }

  return rooms;
});

const openEditModal = (room) => {
  selectedRoom.value = room;
};

const deleteRoom = (roomId) => {
  if (confirm('Are you sure you want to delete this room?')) {
    useForm().delete(route('rooms.destroy', roomId));
  }
};

// Pagination function
const updatePage = (page) => {
  useForm().get(route('rooms.index', { ...filters.value, page }), {}, { preserveState: true, preserveScroll: true });
};

// Watch filters and send requests dynamically
watch(filters, (newFilters) => {
  useForm().get(route('rooms.index', newFilters), {}, { preserveState: true, preserveScroll: true });
}, { deep: true });
</script>

