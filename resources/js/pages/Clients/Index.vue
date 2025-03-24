<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
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

import AddReceptionistModal from './AddReceptionistModal.vue';
import EditReceptionist from './EditReceptionist.vue';

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
</script>
<template>
  <Head title="Manage Receptionists" />

  <AdminAppLayout>
    client
  </AdminAppLayout>
</template>