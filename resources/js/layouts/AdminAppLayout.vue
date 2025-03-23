<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

defineProps<{
    breadcrumbs: BreadcrumbItem[];
}>();
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 shadow">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Admin Dashboard</h2>
                <nav class="mt-6">
                    <Link href="/dashboard" class="block py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                        Dashboard
                    </Link>
                    <!-- Add more sidebar links here -->
                </nav>
            </div>
        </aside>

        <!-- Main content -->
        <main class="ml-64 p-6">
            <!-- Breadcrumbs -->
            <nav class="flex mb-4">
                <Link v-for="(item, index) in breadcrumbs" :key="index" :href="item.href" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                    {{ item.title }}
                </Link>
            </nav>

            <!-- Page content -->
            <slot />
        </main>
    </div>
</template>