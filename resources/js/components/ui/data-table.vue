<template>
    <div>
        <!-- Table -->
        <Table class="w-full border rounded-lg shadow-sm text-sm overflow-hidden">
            <TableHeader class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wide">
                <TableRow>
                    <TableHead v-for="column in columns" :key="column.key" class="px-4 py-3 text-left">
                        {{ column.label }}
                    </TableHead>
                    <TableHead v-if="hasActions" class="px-4 py-3 text-left">Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <!-- No Data Row -->
                <TableRow v-if="!data?.length">
                    <TableCell colspan="100%" class="py-6 text-center text-gray-500">
                        No data found.
                    </TableCell>
                </TableRow>
                <!-- Data Rows -->
                <TableRow v-else v-for="row in data" :key="row.id" class="hover:bg-gray-50">
                    <TableCell
                        v-for="column in columns"
                        :key="column.key"
                        class="px-4 py-3 border-b text-gray-800"
                    >
                        <!-- Render image if the column is an image -->
                        <template v-if="isImageColumn(column)">
                            <img
                                :src="resolveImagePath(getNestedValue(row, column.key))"
                                alt="Image"
                                class="w-10 h-10 rounded-full border"
                            />
                        </template>
                        <!-- Render Approved/Pending dynamically -->
                        <template v-else-if="column.key === 'is_approved'">
                            <span
                                v-if="getNestedValue(row, column.key)"
                                class="text-green-600 font-bold"
                            >
                                Approved
                            </span>
                            <button
                                v-else
                                class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600"
                                @click="$emit('approve', row.id)"
                            >
                                Pending
                            </button>
                        </template>
                        <!-- Render plain text or formatted content for other columns -->
                        <template v-else-if="column.format">
                            <span>{{ column.format(getNestedValue(row, column.key)) }}</span>
                        </template>
                        <template v-else>
                            {{ getNestedValue(row, column.key) }}
                        </template>
                    </TableCell>
                    <!-- Actions Column -->
                    <TableCell v-if="hasActions" class="px-4 py-3 border-b whitespace-nowrap">
                        <slot name="actions" :row="row">
                            <div></div>
                        </slot>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>

        <!-- Pagination -->
        <div v-if="manualPagination" class="flex items-center justify-between mt-6 px-4 text-sm">
            <button
                @click="handlePageChange(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-4 py-2 border rounded-md bg-white hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                Previous
            </button>
            <span class="text-gray-600">
                Page {{ currentPage }} of {{ totalPages }}
            </span>
            <button
                @click="handlePageChange(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-4 py-2 border rounded-md bg-white hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                Next
            </button>
        </div>
    </div>
</template>

<script setup>
import {
    Table,
    TableHeader,
    TableBody,
    TableRow,
    TableHead,
    TableCell,
} from "@/components/ui/table";
import { computed } from "vue";

const props = defineProps({
    data: Array,
    columns: Array,
    manualPagination: Boolean,
    currentPage: Number,
    perPage: Number,
    total: Number,
    hasActions: {
        type: Boolean,
        default: true,
    },
});

const emits = defineEmits(["page-change", "edit", "delete"]);

const totalPages = computed(() => {
    return props.perPage && props.total
        ? Math.ceil(props.total / props.perPage)
        : 1;
});

const handlePageChange = (page) => {
    if (page > 0 && page <= totalPages.value) {
        emits("page-change", page);
    }
};

// Helper function to resolve nested keys
function getNestedValue(obj, key) {
    return key.split('.').reduce((o, k) => (o && o[k] !== undefined ? o[k] : ''), obj);
}

// Helper function to check if a column contains an image
function isImageColumn(column) {
    return column.key.toLowerCase().includes('image');
}

// Helper function to resolve image paths
function resolveImagePath(value) {
    if (!value) {
        return '/storage/avatars/avatar.png'; // Default placeholder image
    }
    if (value.startsWith('/')) {
        return value; // Already a valid path
    }
    return `/storage/avatars/${value}`; // Resolve relative path
}
</script>
