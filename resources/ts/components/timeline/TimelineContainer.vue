<template>
  <ol class="relative border-s" :class="variant ? variants[variant] : variants.default">
    <slot></slot>
  </ol>
</template>
<script setup lang="ts">
import type { TimelineContentProps} from "@/components/@types/timeline"

defineProps<TimelineContentProps>()

const variants = {
    primary: "border-primary-focus",
    secondary: "border-secondary-focus",
    accent: "border-accent-focus",
    info: "border-info-focus",
    neutral: "border-neutral-focus",
    base: "border-base-300",
    success: "border-success-focus",
    warning: "border-warning-focus",
    error: "border-error-focus",
    default: "border-base-300",
}
</script>


