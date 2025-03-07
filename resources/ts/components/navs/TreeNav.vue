<template>
  <nav aria-label="Sidebar">
    <div class="space-y-1 px-2">
      <TreeNavItem v-for="item in navigation" :item="item"></TreeNavItem>
    </div>
  </nav>
</template>
<script setup lang="ts">
import TreeNavItem from './TreeNavItem.vue';

defineProps<{
  navigation: Base.Component.Menu.MenuItem[],
}>()

</script>
