<template>
    <!-- Mobile top navigation -->
    <div class="lg:hidden">
        <div class="flex items-center justify-between bg-primary py-2 px-4 sm:px-6 lg:px-8">
            <div>
                <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=white" alt="Your Company" />
            </div>
            <div>
                <button type="button"
                    class="-mr-3 inline-flex cursor-pointer h-12 w-12 items-center justify-center rounded-md bg-primay text-primary-content hover:primary-focus focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                    @click="preferences.toggleMobileSidebar()">
                    <span class="sr-only">Open sidebar</span>
                    <FontAwesomeIcon :icon="faBars" />
                </button>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { faBars } from '@fortawesome/free-solid-svg-icons';
import { usePreferencesStore } from "@/stores/preferences";

const preferences = usePreferencesStore();

defineProps<{ isOpen: boolean }>()

</script>
