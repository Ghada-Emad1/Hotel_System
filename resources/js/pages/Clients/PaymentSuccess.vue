<script setup>
import { Head, router } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import AdminAppLayout from "@/layouts/AdminAppLayout.vue";
</script>

<template>
  <Head title="Payment Successful" />
  
  <AdminAppLayout>
    <div class="p-8 text-center">
      <h1 class="text-2xl font-bold text-green-600">Payment Successful!</h1>
      <p class="mt-4 text-gray-700">Your reservation has been confirmed.</p>
      <Button 
          @click="router.get(route('client.reservations'))" 
          class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4  my-6 rounded">
          View My Reservations
        </Button>
    </div>
  </AdminAppLayout>
</template>
