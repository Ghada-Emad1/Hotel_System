<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch, ref } from 'vue';

const props = defineProps({ manager: Object });
const emit = defineEmits(['close']);

const form = useForm({
  name: props.manager.name,
  email: props.manager.email,
  avatar_image: null,
  country: props.manager.country,
  gender: props.manager.gender,
  _method: 'PUT',
});

const previewUrl = ref(
  props.manager.avatar_image
    ? `/storage/avatars/${props.manager.avatar_image}`
    : '/storage/avatars/default.png'
);

watch(() => form.avatar_image, (file) => {
  if (file && typeof file !== 'string') {
    previewUrl.value = URL.createObjectURL(file);
  }
});

const submit = () => {
  form.post(route('managers.update', props.manager.id), {
    forceFormData: true,
    onSuccess: () => emit('close'),
  });
};
</script>

<template>
  <div class="fixed inset-0 bg-gray-800/60 z-50 flex justify-center items-center">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-xl space-y-4">
      <h2 class="text-xl font-bold">Edit Manager</h2>

      <div>
        <img :src="previewUrl" class="w-20 h-20 rounded-full object-cover mb-2" />
      </div>

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
          <label class="block font-medium mb-1">National ID</label>
          <input :value="props.manager.national_id" disabled type="text" class="w-full border rounded px-3 py-2 bg-gray-100" />
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
          <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update</button>
        </div>
      </form>
    </div>
  </div>
</template>
