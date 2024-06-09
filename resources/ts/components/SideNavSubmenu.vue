<template>
<div class="fixed h-screen w-56 bg-slate-500"></div>
</template>
<script setup lang="ts">
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import NavItemVue from "./NavItem.vue";
import { ref, defineProps } from "vue";

defineProps({ compact: Boolean });
</script>
