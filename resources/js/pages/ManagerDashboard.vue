<script setup lang="ts">
import ManagerAppLayout from '@/layouts/ManagerAppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

// جلب بيانات المستخدم من Inertia
const page = usePage();
const user = computed(() => page.props.auth.user);



const roleName = computed(() => {
    if (user.value.role === 'admin') return 'Admin Dashboard';
    if (user.value.role === 'manager') return 'Manager Dashboard';
    if (user.value.role === 'receptionist') return 'Receptionist Dashboard';
    return 'Dashboard';
});
</script>




<template>
    <Head :title="roleTitle" />

    <ManagerAppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold">Welcome, {{ user.name }}</h1>
            <p class="text-gray-600">This is your manager dashboard.</p>

            <!-- إضافة الإحصائيات والبيانات الخاصة بالمدير -->
            <div class="grid grid-cols-3 gap-4 mt-4">
                <div class="bg-white p-4 shadow rounded">
                    <h2 class="text-lg font-bold">Total Receptionists</h2>
                    <p class="text-3xl">12</p>
                </div>
                <div class="bg-white p-4 shadow rounded">
                    <h2 class="text-lg font-bold">Total Rooms</h2>
                    <p class="text-3xl">50</p>
                </div>
                <div class="bg-white p-4 shadow rounded">
                    <h2 class="text-lg font-bold">Pending Reservations</h2>
                    <p class="text-3xl">5</p>
                </div>
            </div>
        </div>
    </ManagerAppLayout>
</template>
