<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
  client: Object,
  countries: Array, // Receive countries as a prop
});
const emit = defineEmits(['close']);

const form = useForm({
  name: props.client.name,
  email: props.client.email,
  country: props.client.country,
  gender: props.client.gender,
  avatar_image: null,
  _method: 'PUT',
});

const storedImage = props.client.avatar_image
  ? `/storage/avatars/${props.client.avatar_image}`
  : '/storage/avatars/avatar.png';

const previewUrl = ref(storedImage);

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.avatar_image = file;
    previewUrl.value = URL.createObjectURL(file);
  }
};

watch(() => props.client, (newVal) => {
  previewUrl.value = newVal.avatar_image
    ? `/storage/avatars/${newVal.avatar_image}`
    : '/storage/avatars/avatar.png';
}, { immediate: true });

const submit = () => {
  form.post(route('receptionist_client.update', props.client.id), {
    forceFormData: true,
    onSuccess: () => emit('close'),
  });
};
</script>

<template>
  <div class="fixed inset-0 bg-gray-800/60 flex justify-center items-center z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
      <h2 class="text-xl font-bold mb-4">Edit Client</h2>

      <div class="text-center">
        <img :src="previewUrl" class="w-20 h-20 rounded-full object-cover mb-2" />
      </div>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block font-medium mb-1">Name</label>
          <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" />
        </div>

        <div>
          <label class="block font-medium mb-1">Email</label>
          <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2" />
        </div>

        <div>
          <label class="block font-medium mb-1">National ID</label>
          <input :value="props.client.national_id" disabled type="text" class="w-full border rounded px-3 py-2 bg-gray-100" />
        </div>

        <div>
          <label class="block font-medium mb-1">Country</label>
          <select v-model="form.country" class="w-full border rounded px-3 py-2">
            <option disabled value="">Select a country</option>
            <option v-for="country in countries" :key="country" :value="country">
              {{ country }}
            </option>
          </select>
          <p v-if="form.errors.country" class="text-red-500 text-sm">{{ form.errors.country }}</p>
        </div>

        <div>
          <label class="block font-medium mb-1">Gender</label>
          <select v-model="form.gender" class="w-full border rounded px-3 py-2">
            <option disabled value="">Select</option>
            <option>Male</option>
            <option>Female</option>
          </select>
        </div>

        <div>
          <label class="block font-medium mb-1">Avatar Image</label>
          <input type="file" @change="handleFileChange" />
        </div>

        <div class="flex justify-end gap-3 mt-4">
          <button @click="emit('close')" type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update</button>
        </div>
      </form>
    </div>
  </div>
</template>
