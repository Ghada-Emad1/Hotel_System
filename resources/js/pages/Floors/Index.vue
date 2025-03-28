<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AddFloorModal from './AddFloorModal.vue';
import EditFloorModal from './EditFloorModal.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button'; // ShadCN Button component
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';

const props = defineProps({
  floors: Object, // Pagination object
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

const updatePage = (page) => {
  useForm().get(route('floor.index', { page }), {}, { preserveState: true, preserveScroll: true });
};
</script>

<template>
  <AdminAppLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Manage Floors</h1>

      <Button variant="default" class="mb-4" @click="showAddModal = true">
        Add Floor
      </Button>

      <Table class="border">
        <TableHeader>
          <TableRow>
            <TableHead>Name</TableHead>
            <TableHead>Number</TableHead>
            <TableHead v-if="isAdmin">Manager</TableHead>
            <TableHead v-if="isAdmin || floors.data.some(floor => floor.manager_id === userId)">Actions</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="floor in floors.data" :key="floor.id">
            <TableCell>{{ floor.name }}</TableCell>
            <TableCell>{{ floor.number }}</TableCell>
            <TableCell v-if="isAdmin">
              {{ floor.manager ? floor.manager.name : 'Admin' }}
            </TableCell>
            <TableCell v-if="canModifyFloor(floor)">
              <Button variant="outline" size="sm" class="mr-2" @click="openEditModal(floor)">
                Edit
              </Button>
              <Button variant="destructive" size="sm" @click="deleteFloor(floor.id)">
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
          @click="updatePage(floors.current_page - 1)"
          :disabled="floors.current_page === 1"
        >
          Previous
        </Button>
        <span class="text-sm text-muted-foreground">
          Page {{ floors.current_page }} of {{ floors.last_page }}
        </span>
        <Button
          variant="outline"
          size="sm"
          @click="updatePage(floors.current_page + 1)"
          :disabled="floors.current_page === floors.last_page"
        >
          Next
        </Button>
      </div>

      <AddFloorModal v-if="showAddModal" @close="showAddModal = false" />
      <EditFloorModal v-if="selectedFloor" :floor="selectedFloor" @close="selectedFloor = null" />
    </div>
  </AdminAppLayout>
</template>
