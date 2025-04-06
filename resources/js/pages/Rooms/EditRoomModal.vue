<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({ room: Object, floors: Array });
const emit = defineEmits(['close']);

const form = useForm({
  floor_id: props.room.floor_id,
  capacity: props.room.capacity,
  price: (props.room.price / 100).toFixed(2), // Convert cents to dollars
});

const submit = () => {
  form.put(route('rooms.update', props.room.id), {
    onSuccess: () => emit('close'),
  });
};
</script>

<template>
  <div class="fixed inset-0 bg-black/40 flex justify-center items-center z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
      <h2 class="text-lg font-bold mb-4">Edit Room</h2>
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block mb-1 font-medium">Floor</label>
          <select v-model="form.floor_id" class="w-full border rounded px-3 py-2">
            <option v-for="floor in floors" :key="floor.id" :value="floor.id">{{ floor.name }}</option>
          </select>
          <p class="text-sm text-red-500">{{ form.errors.floor_id }}</p>
        </div>
        <div>
          <label class="block mb-1 font-medium">Room Number</label>
          <input :value="props.room.number" disabled type="text" class="w-full border rounded px-3 py-2 bg-gray-100" />
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
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
        </div>
      </form>
    </div>
  </div>
</template>
