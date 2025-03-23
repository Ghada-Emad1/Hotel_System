<template>
  <div>
    <nav>
      <ul>
        <li>
          <Link :href="route('dashboard')">Dashboard</Link>
        </li>
        <li>
          <Link :href="route('manage.managers')">Manage Managers</Link>
        </li>
        <li>
          <Link :href="route('manage.receptionists')">Manage Receptionists</Link>
        </li>
        <li>
          <Link :href="route('manage.clients')">Manage Clients</Link>
        </li>
        <li>
          <Link :href="route('manage.floors')">Manage Floors</Link>
        </li>
        <li>
          <Link :href="route('manage.rooms')">Manage Rooms</Link>
        </li>
      </ul>
    </nav>
    <slot />
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
nav ul {
  list-style-type: none;
  padding: 0;
  display: flex;
  gap: 20px;
}

nav ul li a {
  text-decoration: none;
  color: #3490dc;
  font-size: 18px;
}

nav ul li a:hover {
  text-decoration: underline;
}
</style>