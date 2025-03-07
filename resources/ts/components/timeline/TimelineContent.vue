<template>
  <div
    class="flex-col md:items-center gap-1 justify-between p-4 border rounded-box shadow-sm md:flex-row flex"
    :class="[
        variant ? variants[variant] : variants.default
    ]"
  >
  <slot name="title" :props="$props"></slot>

    <div
      class="p-3 mt-2 font-normal rounded-box w-10/12 sm:w-full"
      :class="[
        variant ? contentVariants[variant] : contentVariants.default
      ]"
    >
    <slot :props="$props"></slot>

    </div>
  </div>
</template>
<script setup lang="ts">
import type { TimelineContentProps} from "@/components/@types/timeline"

defineProps<TimelineContentProps>()

const variants = {
    primary: "bg-primary border-primary-focus",
    secondary: "bg-secondary border-secondary-focus",
    accent: "bg-accent border-accent-focus",
    info: "bg-info border-info-focus",
    neutral: "bg-neutral border-neutral-focus",
    base: "bg-base-100 border-base-200",
    success: "bg-success border-success-focus",
    warning: "bg-warning border-warning-focus",
    error: "bg-error border-error-focus",
    default: "bg-base-100 border-base-200",
}

const contentVariants = {
    primary: "text-primary-content bg-primary-focus",
    secondary: "text-secondary-content bg-secondary-focus",
    accent: "text-accent-content bg-accent-focus",
    info: "text-info-content bg-info-focus",
    neutral: "text-neutral-content bg-neutral-focus",
    base: "text-base-content bg-base-200",
    success: "text-success-content bg-success-focus",
    warning: "text-warning-content bg-warning-focus",
    error: "text-error-content bg-error-focus",
    default: "text-base-100-content bg-base-200",
}
</script>
