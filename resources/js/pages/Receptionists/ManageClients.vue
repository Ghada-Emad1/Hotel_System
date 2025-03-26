<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  clients: Array,
});
const page = usePage();
const user = page.props.clients;
console.log('User Role:', user);

</script>

<template>
  <Head title="Manage Clients" />

  <AdminAppLayout>
    Manage Client
  </AdminAppLayout>
</template>