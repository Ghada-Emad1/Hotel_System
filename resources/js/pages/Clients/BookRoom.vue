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

const submitReservation = async () => {
  if (accompanyNumber.value > props.room.capacity) {
    errorMessage.value = "The number of accompanying guests cannot exceed the room capacity.";
    return;
  }

  try {
    const response = await fetch("/create-checkout-session", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
      body: JSON.stringify({
        room_id: props.room.id,
        accompany_number: accompanyNumber.value,
        amount: props.room.price * 100, // Stripe يتعامل بالسنتات
      }),
    });

    if (!response.ok) {
      errorMessage.value = "Failed to create checkout session.";
      return;
    }

    const { checkoutUrl } = await response.json();
    window.location.href = checkoutUrl; // 
  } catch (error) {
    errorMessage.value = "An error occurred. Please try again.";
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
