<script setup lang="ts">

import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminAppLayout from '@/layouts/AdminAppLayout.vue';
import { onMounted, ref } from "vue";
import { Chart, registerables } from "chart.js";

// Get the user permissions from the usePage hook
const page = usePage();
const user = computed(() => page.props.auth.user);
console.log("User DAta",page.props.auth.user.roles);
const can = computed(() => page.props.auth.user.permissions || {});


// Title based on the user role
const roleTitle = computed(() => {
    if (user.value.role === 'admin') return 'Admin Dashboard';
    if (user.value.role === 'manager') return 'Manager Dashboard';
    if (user.value.role === 'receptionist') return 'Receptionist Dashboard';
    return 'Dashboard';
});



Chart.register(...registerables);

const chartRef = ref(null);
const chartInstance = ref(null);

const fetchChartData = async () => {
  try {
    const response = await fetch("/api/charts/reservations");
    const data = await response.json();

    if (chartInstance.value) {
      chartInstance.value.destroy(); // Destroy previous instance
    }

    chartInstance.value = new Chart(chartRef.value, {
      type: "line",
      data: {
        labels: data.dates,
        datasets: [
          {
            label: "Reservations per Day",
            data: data.counts,
            borderColor: "rgba(54, 162, 235, 1)",
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            borderWidth: 2,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: { beginAtZero: true },
        },
      },
    });
  } catch (error) {
    console.error("Error fetching chart data:", error);
  }
};

onMounted(fetchChartData);
</script>

<template>

    <Head :title="roleTitle" />

    <AdminAppLayout>
        
        <div class="w-full max-w-2xl mx-auto p-4 bg-white shadow rounded-lg">
    <h2 class="text-lg font-semibold mb-4">Reservations Overview</h2>
    <canvas ref="chartRef"></canvas>
  </div>
    </AdminAppLayout>
</template>