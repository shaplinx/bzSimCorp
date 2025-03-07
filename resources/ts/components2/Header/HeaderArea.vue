<template>
    <MobileTopNav :is-open="preferences.mobileSidebarShown"></MobileTopNav>
    <HeaderNav></HeaderNav>
</template>

<script setup lang="ts">
import HeaderNav from '@/components/navs/HeaderNav.vue';
import MobileTopNav from '@/components/navs/MobileTopNav.vue';
import { usePreferencesStore } from "@/stores/preferences";

const preferences = usePreferencesStore();

</script>
