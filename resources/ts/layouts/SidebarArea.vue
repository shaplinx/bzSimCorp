<template>
    <div class="flex h-full">
        <MobileNav :navigation="NavItems" :is-open="preferences.mobileSidebarShown"></MobileNav>
        <DesktopNav :navigation="NavItems"></DesktopNav>
    </div>
</template>
<script lang="ts" setup>
import { mainSideMenu } from "@/menus/mainSideMenu";
import MobileNav from '@/components/navs/MobileNav.vue';
import DesktopNav from "@/components/navs/DesktopNav.vue"
// import HeaderNav from "@/components/navs/HeaderNav.vue";
import { usePreferencesStore } from '@/stores/preferences';

const preferences = usePreferencesStore();
const NavItems: Base.Component.Menu.MenuItem[] = mainSideMenu

</script>
