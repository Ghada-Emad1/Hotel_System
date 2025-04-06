<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

import AddManagerModal from './AddManagerModal.vue';
import EditManagerModal from './EditManagerModal.vue';

const props = defineProps({
    managers: Object, // Updated to accept a paginated object
});

const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedManager = ref(null);

const openEditModal = (manager) => {
    selectedManager.value = manager;
    showEditModal.value = true;
};

const deleteManager = (id) => {
    if (confirm('Are you sure you want to delete this manager?')) {
        router.delete(route('manager.destroy', { manager: id }), {
            onSuccess: () => {
                console.log('Manager deleted successfully');
            },
            onError: (errors) => {
                console.error('Error deleting manager:', errors);
            },
        });
    }
};

const updatePage = (page) => {
    router.get(route('manager.index', { page }), {}, { preserveState: true, preserveScroll: true });
};
</script>
<template>

    <Head title="Manage Managers" />

    <AdminAppLayout>
        <div class="p-8 space-y-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Manage Managers</h1>
                <Button @click="showAddModal = true">Add Manager</Button>
            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Avatar</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>National ID</TableHead>
                        <TableHead>Country</TableHead>
                        <TableHead>Gender</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="manager in managers.data" :key="manager.id">
                        <!-- Avatar Image -->
                        <TableCell>
                            <img :src="manager.avatar_image ? `/storage/avatars/${manager.avatar_image}` : '/storage/avatars/avatar.png'"
                                alt="Avatar" class="w-10 h-10 rounded-full border" />
                        </TableCell>
                        <TableCell>{{ manager.name }}</TableCell>
                        <TableCell>{{ manager.email }}</TableCell>
                        <TableCell>{{ manager.national_id }}</TableCell>
                        <TableCell>{{ manager.country }}</TableCell>
                        <TableCell>{{ manager.gender }}</TableCell>
                        <TableCell>
                            <Button class="mr-2" @click="openEditModal(manager)">Edit</Button>
                            <Button variant="destructive" @click="deleteManager(manager.id)">Delete</Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Pagination Controls -->
            <div class="flex items-center justify-between mt-4">
                <Button variant="outline" size="sm" @click="updatePage(managers.current_page - 1)"
                    :disabled="managers.current_page === 1">
                    Previous
                </Button>
                <span class="text-sm text-muted-foreground">
                    Page {{ managers.current_page }} of {{ managers.last_page }}
                </span>
                <Button variant="outline" size="sm" @click="updatePage(managers.current_page + 1)"
                    :disabled="managers.current_page === managers.last_page">
                    Next
                </Button>
            </div>

            <AddManagerModal v-if="showAddModal" @close="showAddModal = false" />
            <EditManagerModal v-if="showEditModal" :manager="selectedManager" @close="showEditModal = false" />
        </div>
    </AdminAppLayout>

</template>