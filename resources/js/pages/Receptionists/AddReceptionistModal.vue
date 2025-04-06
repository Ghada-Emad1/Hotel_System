<script setup>
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['close']);

const form = useForm({
  name: '',
  email: '',
  national_id: '',
  country: '',
  gender: '',
  avatar_image: null,
  password: ''
});

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.avatar_image = file;
  }
};

const submit = () => {
  form.post(route('receptionist.store'), {
    forceFormData: true,
    onSuccess: () => {
      form.reset();
      emit('close');
    }
  });
};
</script>

<template>
  <div class="fixed inset-0 bg-gray-800/60 flex justify-center items-center z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
      <h2 class="text-xl font-bold mb-4">Add Receptionist</h2>
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block font-medium mb-1">Name</label>
          <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" />
          <p v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</p>
        </div>
        <div>
          <label class="block font-medium mb-1">Email</label>
          <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2" />
          <p v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</p>
        </div>
        <div>
          <label class="block font-medium mb-1">National ID</label>
          <input v-model="form.national_id" type="text" class="w-full border rounded px-3 py-2" />
          <p v-if="form.errors.national_id" class="text-red-500 text-sm">{{ form.errors.national_id }}</p>
        </div>
        <div>
          <label class="block font-medium mb-1">Country</label>
          <input v-model="form.country" type="text" class="w-full border rounded px-3 py-2" />
          <p v-if="form.errors.country" class="text-red-500 text-sm">{{ form.errors.country }}</p>
        </div>
        <div>
          <label class="block font-medium mb-1">Gender</label>
          <select v-model="form.gender" class="w-full border rounded px-3 py-2">
            <option disabled value="">Select</option>
            <option>Male</option>
            <option>Female</option>
          </select>
          <p v-if="form.errors.gender" class="text-red-500 text-sm">{{ form.errors.gender }}</p>
        </div>
        <div>
          <label class="block font-medium mb-1">Avatar Image</label>
          <input type="file" @change="handleFileChange" />
          <p v-if="form.errors.avatar_image" class="text-red-500 text-sm">{{ form.errors.avatar_image }}</p>
        </div>
        <div>
          <label class="block font-medium mb-1">Password</label>
          <input v-model="form.password" type="password" class="w-full border rounded px-3 py-2" />
          <p v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</p>
        </div>
        <div class="flex justify-end gap-3 mt-4">
          <button @click="emit('close')" type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Add</button>
        </div>
      </form>
    </div>
  </div>
</template>
