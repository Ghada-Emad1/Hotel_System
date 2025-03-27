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
  rooms: Object,
});

</script>

<template>
  <Head title="Available Rooms" />

  <AdminAppLayout>
    <div class="p-8 space-y-6">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Available Rooms</h1>
        <Button 
          @click="router.get(route('client.reservations'))" 
          class="bg-black text-white font-bold py-2 px-4 rounded">
          ← Back to My Reservations
        </Button>
      </div>

      <Table v-if="rooms && rooms.data">
        <TableHeader>
          <TableRow>
            <TableHead>Room Number</TableHead>
            <TableHead>Capacity</TableHead>
            <TableHead>Price</TableHead>
            <TableHead>Action</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="room in rooms.data" :key="room.id">
            <TableCell>{{ room.number }}</TableCell>
            <TableCell>{{ room.capacity }}</TableCell>
            <TableCell>${{ room.price }}</TableCell>
            <TableCell>
                <Button class="bg-red-700 hover:bg-red-400 text-white" 
                    @click="router.get(route('client.reservations.book', { room: room.id }))"
                    >
                    Make Reservation
            </Button>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>

      <p v-else class="text-center text-gray-500">No available rooms found.</p>

      <div class="flex justify-between mt-4">
        <Button 
          @click="router.get(route('client.reservations.available', { page: rooms.current_page - 1 }))" 
          :disabled="rooms.current_page === 1">
          Previous
        </Button>
        <span class="mx-4">Page {{ rooms.current_page }} of {{ rooms.last_page }}</span>
        <Button 
          @click="router.get(route('client.reservations.available', { page: rooms.current_page + 1 }))" 
          :disabled="rooms.current_page === rooms.last_page">
          Next
        </Button>
      </div>
    </div>
  </AdminAppLayout>
</template>
