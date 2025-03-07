<template>
  <ul class="dropdown-content menu p-2 shadow rounded-md w-52" :class="Colors[color || 'default']">
    <slot ></slot>
  </ul>
</template>

<script setup lang="ts">
import { DropdownColorClasses, DropdownColor } from "./dropdown";

defineProps<{
  color?: DropdownColor;
}>();

const Colors: DropdownColorClasses = {
  primary: "bg-primary text-primary-content",
  secondary: "bg-secondary text-secondary-content",
  accent: "bg-accent text-accent-content",
  info: "bg-info text-info-content",
  success: "bg-success text-success-content",
  warning: "bg-warning text-warning-content",
  error: "bg-error text-error-content",
  default: "bg-base-100 text-base-content",
};
</script>

