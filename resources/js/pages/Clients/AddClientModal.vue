<script setup>
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['close']);

const form = useForm({
  name: '',
  email: '',
  password: '',
  national_id: '',
  avatar_image: null,
  country: '',
  gender: '',
});

const submit = () => {
  form.post(route('client.store'), {
    forceFormData: true,
    onSuccess: () => emit('close'),
  });
};
</script>

<template>
  <div class="fixed inset-0 bg-gray-800/60 z-50 flex justify-center items-center">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md max-h-[90vh] overflow-y-auto space-y-4">

      <h2 class="text-xl font-bold">Add Manager</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block font-medium mb-1">Name</label>
          <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" />
          <p class="text-sm text-red-500">{{ form.errors.name }}</p>
        </div>
        <div>
          <label class="block font-medium mb-1">Email</label>
          <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2" />
          <p class="text-sm text-red-500">{{ form.errors.email }}</p>
        </div>
        <div>
          <label class="block font-medium mb-1">Password</label>
          <input v-model="form.password" type="password" class="w-full border rounded px-3 py-2" />
          <p class="text-sm text-red-500">{{ form.errors.password }}</p>
        </div>
        <div>
          <label class="block font-medium mb-1">National ID</label>
          <input v-model="form.national_id" type="text" class="w-full border rounded px-3 py-2" />
          <p class="text-sm text-red-500">{{ form.errors.national_id }}</p>
        </div>
        <div>
          <label class="block font-medium mb-1">Avatar Image</label>
          <input type="file" @change="form.avatar_image = $event.target.files[0]" />
          <p class="text-sm text-red-500">{{ form.errors.avatar_image }}</p>
        </div>
        <div>
          <label class="block font-medium mb-1">Country</label>
          <input v-model="form.country" type="text" class="w-full border rounded px-3 py-2" />
        </div>
        <div>
          <label class="block font-medium mb-1">Gender</label>
          <select v-model="form.gender" class="w-full border rounded px-3 py-2">
            <option disabled value="">Select</option>
            <option>Male</option>
            <option>Female</option>
          </select>
        </div>

        <div class="flex justify-end gap-3 mt-4">
          <button @click="emit('close')" type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
        </div>
      </form>
    </div>
  </div>
</template>
