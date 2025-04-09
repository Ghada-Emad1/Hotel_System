<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
  receptionist: Object,
  countries: Array, // Receive countries as a prop
});
const emit = defineEmits(['close']);

const form = useForm({
  name: props.receptionist.name,
  email: props.receptionist.email,
  country: props.receptionist.country,
  gender: props.receptionist.gender,
  avatar_image: null,
  _method: 'PUT'
});

const previewUrl = ref(
  props.receptionist.avatar_image ? `/storage/avatars/${props.receptionist.avatar_image}` : '/storage/avatars/avatar.png'
);

watch(
  () => form.avatar_image,
  (file) => {
    if (file && typeof file !== 'string') {
      previewUrl.value = URL.createObjectURL(file);
    }
  }
);

const submit = () => {
  form.post(route('receptionist.update', props.receptionist.id), {
    forceFormData: true,
    onSuccess: () => emit('close')
  });
};
</script>

<template>
  <div class="fixed inset-0 bg-gray-800/60 flex justify-center items-center z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
      <h2 class="text-xl font-bold mb-4">Edit Receptionist</h2>

      <div class="flex justify-center">
        <img :src="previewUrl" class="w-20 h-20 rounded-full object-cover mb-4" />
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
          <input :value="props.receptionist.national_id" disabled type="text" class="w-full border rounded px-3 py-2 bg-gray-100" />
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
          <input type="file" @change="form.avatar_image = $event.target.files[0]" />
        </div>

        <div class="flex justify-end gap-3 mt-4">
          <button @click="emit('close')" type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update</button>
        </div>
      </form>
    </div>
  </div>
</template>
