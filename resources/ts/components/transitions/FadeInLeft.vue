<template>
  <Transition
    enter-from-class="-translate-x-24 opacity-0"
    enter-active-class="transition duration-150 transform"
    enter-to-class="translate-x-0 opacity-100"
    leave-from-class="translate-x-100 opacity-100"
    leave-active-class="transition duration-150 transform"
    leave-to-class="-translate-x-24 opacity-0"
    :mode="mode"
  >
    <slot></slot>
  </Transition>
</template>
<script lang="ts" setup>
import { defineProps, PropType } from "vue";

defineProps({
  mode: String as PropType<"default" | "in-out" | "out-in" | undefined>,
});
</script>
