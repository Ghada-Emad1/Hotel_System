<script setup>
import { defineProps } from "vue";
import { Head, router } from "@inertiajs/vue3";

import { Button } from "@/components/ui/button";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import AdminAppLayout from "@/layouts/AdminAppLayout.vue";

const props = defineProps({
  reservations: Object,
});

const updatePage = (page) => {
  if (page >= 1 && page <= props.reservations.last_page) {
    router.get(
      route("client.reservations", { page }), 
      {}, 
      { preserveState: true, preserveScroll: true }
    );
  }
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2
  }).format(price / 100);
};
</script>

<template>
  <Head title="My Reservations" />

  <AdminAppLayout>
    <div class="p-4 md:p-8 space-y-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl font-bold">My Reservations</h1>
        <Button 
          @click="router.get(route('client.reservations.available'))" 
          variant="default"
          class="bg-black  text-white"
        >
          Make reservation Now
        </Button>
      </div>

      <div class="border rounded-lg overflow-hidden">
        <Table>
          <TableHeader class="bg-black">
            <TableRow >
              <TableHead class="font-medium text-white">Accompany Number</TableHead>
              <TableHead class="font-medium text-white">Room Number</TableHead>
              <TableHead class="font-medium text-white">Paid Price</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow 
              v-for="reservation in reservations.data" 
              :key="reservation.id"
              class="hover:bg-gray-50"
            >
              <TableCell>{{ reservation.accompany_number }}</TableCell>
              <TableCell>{{ reservation.room.number }}</TableCell>
              <TableCell>{{ formatPrice(reservation.paid_price) }}</TableCell>
            </TableRow>
            <TableRow v-if="reservations.data.length === 0">
              <TableCell colspan="3" class="text-center py-4 text-gray-500">
                No reservations found
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>

      <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-4">
        <div class="flex-1">
          <Button 
            @click="updatePage(reservations.current_page - 1)" 
            :disabled="reservations.current_page === 1"
            variant="outline"
            class="w-full sm:w-auto"
          >
            Previous
          </Button>
        </div>
        <span class="text-sm text-gray-600 mx-4">
          Page {{ reservations.current_page }} of {{ reservations.last_page }}
        </span>
        <div class="flex-1 flex justify-end">
          <Button 
            @click="updatePage(reservations.current_page + 1)" 
            :disabled="reservations.current_page === reservations.last_page"
            variant="outline"
            class="w-full sm:w-auto"
          >
            Next
          </Button>
        </div>
      </div>
    </div>
  </AdminAppLayout>
</template>