<script setup>
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import AdminAppLayout from "@/layouts/AdminAppLayout.vue";

const props = defineProps({
  room: Object,
});

const accompanyNumber = ref(1);
const errorMessage = ref(null);
const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

if (!csrfToken) {
  console.error("CSRF token not found. Ensure the meta tag is present in the HTML head.");
}
console.log("Room ID:", props.room?.id);
console.log("Price:", props.room?.price);



const submitReservation = async () => {
  try {
    const response = await fetch('/client/create-checkout-session', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken, // Include the CSRF token
      },
      body: JSON.stringify({
        room_id: props.room.id,
        accompany_number: accompanyNumber.value,
        amount: props.room.price * 100, // Convert price to cents
      }),
    });

    if (!response.ok) {
      throw new Error('Failed to create checkout session.');
    }

    const data = await response.json();
    window.location.href = data.checkoutUrl; // Redirect to Stripe checkout
  } catch (error) {
    console.error(error);
    alert('An error occurred while processing your reservation.');
  }
};
</script>

<template>
  <Head :title="`Book Room ${room.number}`" />
  <AdminAppLayout>
    <div class="p-8 space-y-6">
      <h1 class="text-2xl font-bold">Book Room {{ room.number }}</h1>
      <div class="border p-4 rounded-lg shadow">
        <p><strong>Room Number:</strong> {{ room.number }}</p>
        <p><strong>Capacity:</strong> {{ room.capacity }}</p>
        <p><strong>Price:</strong> ${{ room.price }}</p>
      </div>
      <form @submit.prevent="submitReservation" class="space-y-4">
        <div>
          <label class="block font-semibold">Number of Accompanying Guests</label>
          <input v-model="accompanyNumber" type="number" min="1" :max="room.capacity" class="border p-2 rounded w-full" />
          <p v-if="errorMessage" class="text-red-500">{{ errorMessage }}</p>
        </div>
        <Button type="submit" class="bg-green-500 text-white">Proceed with Reservation & Payment</Button>
      </form>
    </div>
  </AdminAppLayout>
</template>
