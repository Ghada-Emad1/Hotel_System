<script setup lang="ts">
import AppLayout from './AppLayout.vue';
import AppSideBar from '@/components/Admin/AppSideBar.vue';

interface Props {
    breadcrumbs?: Array<any>;
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
        <AppSideBar />
        
    </AppLayout>
</template>
