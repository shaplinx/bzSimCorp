<template>
  <span class="absolute flex items-center justify-center w-8 h-8 rounded-full -start-4 ring-8" :class="[variant ? variants[variant] : variants.default]">
    <FontAwesomeIcon class="rounded-full shadow-lg" :class="[iconClass]"  :icon="icon" />
  </span>
</template>
<script setup lang="ts">
import type { TimelineIconProps} from "@/components/@types/timeline"

defineProps<TimelineIconProps>()

const variants = {
    primary: "bg-primary ring-primary-focus",
    secondary: "bg-secondary ring-secondary-focus",
    accent: "bg-accent ring-accent-focus",
    info: "bg-info ring-info-focus",
    neutral: "bg-neutral ring-neutral-focus",
    base: "bg-base-100 ring-base-200",
    success: "bg-success ring-success-focus",
    warning: "bg-warning ring-warning-focus",
    error: "bg-error ring-error-focus",
    default: "bg-base-100 ring-base-200",
}
</script>
