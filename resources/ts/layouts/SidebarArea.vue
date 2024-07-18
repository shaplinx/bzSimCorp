<template>
    <div class="flex h-full">
        <MobileNav :navigation="NavItems" :is-open="preferences.mobileSidebarShown"></MobileNav>
        <DesktopNav :navigation="NavItems"></DesktopNav>
    </div>
</template>
<script lang="ts" setup>

import MobileNav from '@/components/navs/MobileNav.vue';
import DesktopNav from "@/components/navs/DesktopNav.vue"
// import HeaderNav from "@/components/navs/HeaderNav.vue";
import { faGaugeHigh, faTable, faUsers, faPlusCircle, faFolder} from "@fortawesome/free-solid-svg-icons";
import { usePreferencesStore } from '@/stores/preferences';

const preferences = usePreferencesStore();
const NavItems: Base.Component.Menu.MenuItem[] = [
    {
        id: "dashboard",
        icon: faGaugeHigh,
        label: "Dashboard",
        to: { name: "Dashboard" }
    },
    {
        id: "user",
        icon: faTable,
        label: "Data Table",
        to: { name: "DataTable" },
        child: [
            {
                id:"createUser",
                icon: faPlusCircle,
                label:"Create"
            }
        ]
    },
    {
        id: "forms",
        icon: faFolder,
        label: "Forms Showcase",
        to: { name: "FormShowcase" },

    },
]

</script>
