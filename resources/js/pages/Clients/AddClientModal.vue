<template>
    <div class="fixed inset-0 bg-gray-800/60 flex justify-center items-center z-50">
      <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Add Client</h2>

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
            <label class="block font-medium mb-1">Country</label>
            <input v-model="form.country" type="text" class="w-full border rounded px-3 py-2" />
            <p class="text-sm text-red-500">{{ form.errors.country }}</p>
          </div>

          <div>
            <label class="block font-medium mb-1">Gender</label>
            <select v-model="form.gender" class="w-full border rounded px-3 py-2">
              <option disabled value="">Select</option>
              <option>Male</option>
              <option>Female</option>
            </select>
            <p class="text-sm text-red-500">{{ form.errors.gender }}</p>
          </div>

          <div class="flex justify-end gap-3 mt-4">
            <button @click="emit('close')" type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Add</button>
          </div>
        </form>
      </div>
    </div>
  </template>

  <script setup>
  import { useForm } from '@inertiajs/vue3';
  import { defineEmits } from 'vue';

  const emit = defineEmits(['close']);

  const form = useForm({
    name: '',
    email: '',
    password: '',
    national_id: '',
    country: '',
    gender: '',
  });

  const submit = () => {
    form.post(route('clients.store'), {
      onSuccess: () => emit('close'),
    });
  };
  </script>
