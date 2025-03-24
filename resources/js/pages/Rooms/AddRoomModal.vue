<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({ floors: Array });
const emit = defineEmits(['close']);

const form = useForm({
  floor_id: '',
  number: '',
  capacity: '',
  price: '',
});

const submit = () => {
  form.post(route('rooms.store'), {
    onSuccess: () => {
      emit('close');
      form.reset();
    },
  });
};
</script>

<template>
  <div class="fixed inset-0 bg-black/40 flex justify-center items-center z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
      <h2 class="text-lg font-bold mb-4">Add Room</h2>
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block mb-1 font-medium">Floor</label>
          <select v-model="form.floor_id" class="w-full border rounded px-3 py-2">
            <option disabled value="">Select Floor</option>
            <option v-for="floor in floors" :key="floor.id" :value="floor.id">{{ floor.name }}</option>
          </select>
          <p class="text-sm text-red-500">{{ form.errors.floor_id }}</p>
        </div>
        <div>
          <label class="block mb-1 font-medium">Room Number</label>
          <input v-model="form.number" type="number" class="w-full border rounded px-3 py-2" />
          <p class="text-sm text-red-500">{{ form.errors.number }}</p>
        </div>
        <div>
          <label class="block mb-1 font-medium">Capacity</label>
          <input v-model="form.capacity" type="number" class="w-full border rounded px-3 py-2" />
          <p class="text-sm text-red-500">{{ form.errors.capacity }}</p>
        </div>
        <div>
          <label class="block mb-1 font-medium">Price ($)</label>
          <input v-model="form.price" type="number" step="0.01" class="w-full border rounded px-3 py-2" />
          <p class="text-sm text-red-500">{{ form.errors.price }}</p>
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" @click="emit('close')" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Add</button>
        </div>
      </form>
    </div>
  </div>
</template>
