<template>
  <RouterView v-slot="{ Component }">
    <Transition
      enter-active-class="transition-all duration-150"
      enter-from-class="-x-translate-40 opacity-0"
      enter-to-class="opacity-100 x-translate-0"
      leave-active-class="transition-all duration-150"
      leave-from-class="opacity-100 x-translate-0"
      leave-to-class="-x-translate-40 opacity-0"
      mode="out-in"
    >
      <component :is="Component" />
    </Transition>
  </RouterView>
</template>
<script setup lang="ts"></script>
