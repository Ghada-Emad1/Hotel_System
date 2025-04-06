<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({ floor: Object });
const emit = defineEmits(['close']);

const form = useForm({
  name: props.floor.name,
});

const submit = () => {
  form.put(route('floor.update', props.floor.id), {
    onSuccess: () => {
      emit('close');
      location.reload();
    },
  });
};
</script>

<template>
  <div class="fixed inset-0 bg-black/40 flex justify-center items-center z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
      <h2 class="text-lg font-bold mb-4">Edit Floor</h2>
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block mb-1 font-medium">Name</label>
          <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" />
          <p class="text-sm text-red-500">{{ form.errors.name }}</p>
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" @click="emit('close')" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
        </div>
      </form>
    </div>
  </div>
</template>
