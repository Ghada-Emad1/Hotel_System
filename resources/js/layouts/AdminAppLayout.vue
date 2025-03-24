<script setup lang="ts">

import type { BreadcrumbItemType } from '@/types';
import AppLayout from './AppLayout.vue';
import AppSideBar from '@/components/Admin/AppSideBar.vue';


interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>

    <AppLayout :breadcrumbs="breadcrumbs">
         <slot /> 
        <AppSideBar/>
       
    </AppLayout>
</template>