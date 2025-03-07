<template>
    <div :class="[
        'badge',
        props.variant ? colors[props.variant] : colors.default,
        props.size ? sizes[props.size] : sizes.default,
        {
            'badge-outline': props.outline
        }
    ]">
        <slot :props="props">
            {{ props.label }}
        </slot>
    </div>
</template>
<script setup lang="ts">
import { BadgeProps } from "../@types/badge";

const props = defineProps<BadgeProps>()


const colors = {
    primary: "badge-primary",
    secondary: "badge-secondary",
    accent: "badge-accent",
    info: "badge-info",
    neutral: "badge-neutral",
    success: "badge-success",
    warning: "badge-warning",
    error: "badge-error",
    ghost: "badge-ghost",
    default: "",
};

const sizes = {
    lg: "badge-lg",
    md: "badge-md",
    sm: "badge-sm",
    xs: "badge-xs",
    default: "",
};
</script>
