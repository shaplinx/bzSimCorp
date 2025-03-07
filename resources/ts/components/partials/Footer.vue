<template>
  <footer
    class="w-full mt-auto h-16 flex flex-col items-center justify-center bg-base-200 text-base-content border-t-4 border-primary"
  >
    <p class="block">©<strong> {{ AppName }}</strong> All Rights Reserverd</p>
    <p class="block text-sm font-light">App Ver: {{ version }}</p>
  </footer>
</template>
<script setup lang="ts">
import {version} from '@/../package.json';
const AppName = import.meta.env.VITE_APP_NAME || 'BZ App';


</script>
