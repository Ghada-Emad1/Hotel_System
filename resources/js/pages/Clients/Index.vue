<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import DataTable from '@/components/ui/data-table.vue'; // Import the reusable DataTable component
import AddClientModal from './AddClientModal.vue';
import EditClientModal from './EditClientModal.vue';

const props = defineProps({
  clients: Object, // Pagination object
  isManager: Boolean,
  isReceptionist: Boolean,
  countries: Array, // Receive countries from the controller
  filters: Object, // Receive filters from the controller
});

const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedClient = ref(null);

const filters = ref({
  search: props.filters.search || '',
  country: props.filters.country || '',
  gender: props.filters.gender || '',
});

const columns = [
  { key: 'name', label: 'Name' },
  { key: 'email', label: 'Email' },
  { key: 'country', label: 'Country' },
  { key: 'gender', label: 'Gender' },
  {
    key: 'is_approved',
    label: 'Approved',
    format: (value) =>
      value
        ? '<span class="text-green-600 font-bold">Approved</span>'
        : '<button class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">Pending</button>',
  },
];

const openEditModal = (client) => {
  selectedClient.value = client;
  showEditModal.value = true;
};

const approveClient = (id) => {
  if (confirm('Are you sure you want to approve this client?')) {
    router.post(route('receptionist_client.approve', id), {}, {
      onSuccess: () => {
        router.reload({ only: ['clients'] });
      },
      onError: (errors) => {
        console.error(errors);
        alert('An error occurred while approving the client.');
      },
    });
  }
};

const deleteClient = (id) => {
  if (confirm('Are you sure you want to delete this client?')) {
    router.delete(route('receptionist_client.destroy', id), {
      onSuccess: () => {
        router.reload({ only: ['clients'] });
      },
      onError: (errors) => {
        console.error(errors);
        alert('An error occurred while deleting the client.');
      },
    });
  }
};

const updatePage = (page) => {
  router.get(route('receptionist_client.index', { ...filters.value, page }), {}, { preserveState: true, preserveScroll: true });
};

// Watch filters and send requests dynamically
watch(filters, (newFilters) => {
  router.get(route('receptionist_client.index', {
    search: newFilters.search,
    country: newFilters.country,
    gender: newFilters.gender,
  }), {}, {
    preserveState: true,
    preserveScroll: true,
  });
}, { deep: true });
</script>

<template>
  <AdminAppLayout>
    <div class="p-8 space-y-6">
      <!-- Header Section -->
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Manage Clients</h1>
        <button @click="showAddModal = true" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700">
          Add Client
        </button>
      </div>

      <!-- Filters Section -->
      <div class="flex space-x-4 mb-6">
        <input
          v-model="filters.search"
          type="text"
          placeholder="Search by name, email, or ID"
          class="border rounded px-4 py-3 text-sm"
        />
        <select v-model="filters.country" class="border rounded px-4 py-3 text-sm">
          <option value="">All Countries</option>
          <option v-for="country in countries" :key="country" :value="country">
            {{ country }}
          </option>
        </select>
        <select v-model="filters.gender" class="border rounded px-4 py-3 text-sm">
          <option value="">All Genders</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>

      <!-- Reusable DataTable Component -->
      <DataTable
        :data="clients.data"
        :columns="columns"
        :manualPagination="true"
        :currentPage="clients.current_page"
        :perPage="clients.per_page"
        :total="clients.total"
        @page-change="updatePage"
        @approve="approveClient"
      >
        <template #actions="{ row }">
          <div class="flex gap-2">
            <button
              class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700"
              @click="openEditModal(row)"
            >
              Edit
            </button>
            <button
              class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700"
              @click="deleteClient(row.id)"
            >
              Delete
            </button>
          </div>
        </template>
      </DataTable>

      <!-- Modals -->
      <AddClientModal v-if="showAddModal" :countries="countries" @close="showAddModal = false" />
      <EditClientModal v-if="showEditModal" :client="selectedClient" :countries="countries" @close="showEditModal = false" />
    </div>
  </AdminAppLayout>
</template>