<script setup>
import { Head, router } from "@inertiajs/vue3";
import ClientAppLayout from "@/layouts/ClientAppLayout.vue";
import { Button } from "@/components/ui/button";
</script>

<template>
  <Head title="Payment Canceled" />
  
  <ClientAppLayout>
    <div class="p-8 text-center">
      <h1 class="text-2xl font-bold text-red-600">Payment Canceled!</h1>
      <p class="mt-4 text-gray-700">Your payment was not completed. If this was a mistake, please try again.</p>
      <Button 
          @click="router.get(route('client.available'))" 
          class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4  my-6 rounded">
          Try Again
        </Button>
    </div>
  </ClientAppLayout>
</template>