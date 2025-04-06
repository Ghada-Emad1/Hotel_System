<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
      <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <h1 class="text-2xl font-bold text-red-600">Pending Approval</h1>
        <p class="mt-4 text-gray-700">Your account is currently under review. Please wait for an administrator to approve your registration.</p>
        <button @click="logout" class="mt-4 px-4 py-2 bg-black text-white rounded hover:bg-opacity-75">
          Logout
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/vue3'
  import { router } from '@inertiajs/vue3'
  
  const logout = () => {
    router.post(route('logout'));
  }
  </script>