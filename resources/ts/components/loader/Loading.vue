<template>
    <span class="loading" :class="[
        variant? variants[variant] : variants.default,
        size? sizes[size] : sizes.default,
        color? colors[color] : colors.default,

    ]"></span>
</template>
<script setup lang="ts">
import type {LoadingProps} from "@/components/@types/loader"
const props = defineProps<LoadingProps>()

const colors =  {
    primary: "text-primary",
    secondary: "text-secondary",
    accent: "text-accent",
    neutral: "text-neutral",
    info: "text-info",
    success: "text-success",
    warning: "text-warning",
    error: "text-error",
    default: ""
}

const sizes = {
    lg: "loading-lg",
    md: "loading-md",
    sm: "loading-sm",
    xs: "loading-xs",
    default: "",
};

const variants = {
    spinner: "loading-spinner",
    dots: "loading-dots",
    ring: "loading-ring",
    ball: "loading-ball",
    bars: "loading-bars",
    infinity: "loading-infinity",
    default: "loading-spinner",
};
</script>
