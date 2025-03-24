<script setup>
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow
} from '@/components/ui/table';

// تعريف رؤوس الأعمدة
const headers = [
  { key: 'name', label: 'Name' },
  { key: 'email', label: 'Email' },
  { key: 'national_id', label: 'National ID' },
  { key: 'country', label: 'Country' },
  { key: 'gender', label: 'Gender' },
];

// بيانات ثابتة للمدراء
const managers = [
  {
    id: 1,
    name: 'John Doe',
    email: 'john@example.com',
    national_id: '123456789',
    country: 'USA',
    gender: 'Male',
  },
  {
    id: 2,
    name: 'Jane Smith',
    email: 'jane@example.com',
    national_id: '987654321',
    country: 'Canada',
    gender: 'Female',
  },
  {
    id: 3,
    name: 'Ali Hassan',
    email: 'ali@example.com',
    national_id: '456789123',
    country: 'Egypt',
    gender: 'Male',
  },
];

// إجراءات ثابتة لأزرار الجدول
const actions = [
  {
    label: 'Edit',
    handler: (manager) => {
      alert(`Edit clicked for: ${manager.name}`);
    },
  },
  {
    label: 'Delete',
    handler: (manager) => {
      if (confirm(`Are you sure you want to delete ${manager.name}?`)) {
        alert(`Deleted: ${manager.name}`);
      }
    },
  },
];
</script>

<template>
  <Head title="Manage Managers" />

  <AdminAppLayout>
    <div class="flex flex-col gap-4 p-8">
        <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manage Managers</h1>
        <Link href="#">
          <Button>Add Manager</Button>
        </Link>
      </div>

      <!-- جدول بيانات المدراء الثابت -->
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead v-for="header in headers" :key="header.key">
              {{ header.label }}
            </TableHead>
            <TableHead>Actions</TableHead>
          </TableRow>
        </TableHeader>

        <TableBody>
          <TableRow v-for="manager in managers" :key="manager.id">
            <TableCell>{{ manager.name }}</TableCell>
            <TableCell>{{ manager.email }}</TableCell>
            <TableCell>{{ manager.national_id }}</TableCell>
            <TableCell>{{ manager.country }}</TableCell>
            <TableCell>{{ manager.gender }}</TableCell>
            <TableCell>
              <Button
                v-for="action in actions"
                :key="action.label"
                class="mr-2"
                @click="action.handler(manager)"
              >
                {{ action.label }}
              </Button>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>
  </AdminAppLayout>
</template>