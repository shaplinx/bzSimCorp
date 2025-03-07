<template>
  <header
    class="bg-base-200 text-base-content w-full flex justify-center flex-col md:flex-row flex-grow md:flex-grow-0 items-center md:justify-between shadow"
  >
    <div class="max-w-7xl py-4 px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tigh text-center md:text-left">
        Dashboard
      </h1>
      <BreadCrumbs class="mt-2" />
    </div>
  </header>
</template>
<script lang="ts" setup>
import BreadCrumbs from "@/components/navs/BreadCrumbs.vue";

</script>
