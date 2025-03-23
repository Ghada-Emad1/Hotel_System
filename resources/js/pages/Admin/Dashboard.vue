<template>
  <div>
    <h1>Admin Dashboard</h1>
    <nav>
      <ul>
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
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
nav ul {
  list-style-type: none;
  padding: 0;
}

nav ul li {
  margin: 10px 0;
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