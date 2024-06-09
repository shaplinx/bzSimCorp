<template>
  <Transition
    enter-active-class="transition-all"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-show="show"
      class="absolute bg-base-content rounded-box bg-opacity-30 backdrop-blur-sm z-10 h-full w-full flex items-center justify-center"
    >
      <div class="flex items-center">
        <!-- loading icon -->
        <svg
          class="animate-spin h-10 w-10 text-base-300"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
        >
          <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
          ></circle>
          <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
          ></path>
        </svg>
        <!-- end loading icon -->
      </div>
    </div>
  </Transition>
</template>
<script setup lang="ts">
import { defineProps } from "vue";
defineProps({
  show: Boolean,
});
</script>
