<template>
  <input
    type="checkbox"
    :class="[
        typeMap[type ?? 'neutral'],
        sizeMap[size ?? 'md'],
        'transition-all'
        ]"
    @click="$emit('update:modelValue', !modelValue)"
    :value="modelValue"
  />
</template>
<script setup lang="ts">
import { defineProps, PropType } from "vue";

defineProps({
  modelValue: Boolean,
  size: String as PropType<"xs" | "sm" | "md" | "lg">,
  type: String as PropType<"neutral" | "primary" | "secondary" | "accent">,
});

const typeMap= {
    neutral:"toggle",
    primary:"toggle toggle-primary",
    secondary:"toggle toggle-secondary",
    accent:"toggle toggle-accent",
    success:"toggle toggle-success",
    warning:"toggle toggle-warning",
    info:"toggle toggle-info",
    error:"toggle toggle-error"
}
const sizeMap= {
    xs:"toggle-xs",
    sm:"toggle-sm",
    md:"toggle-md",
    lg:"toggle-lg",
}

</script>
