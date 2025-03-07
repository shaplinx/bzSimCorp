<template>
    <div :class="[
        'card',
        variant ? variants[variant] : variants.default,
        {
            'card-bordered': props.bordered,
            'image-full': props.imageFull,
            'card-compact': props.compact,
            'card-side': props.side,
            'glass': props.glass
        }
    ]">
        <slot :props="props">
            <CardBody>
                <CardImage v-if="props.image" :src="props.image"></CardImage>
                <CardTitle>{{ props.title }}</CardTitle>
                <p>{{ props.description }}</p>
                <CardActions>
                    <slot name="actions"></slot>
                </CardActions>
            </CardBody>
        </slot>
    </div>
</template>
<script setup lang="ts">
import type { CardProps } from "@/components/@types/card"
import CardBody from "./CardBody.vue";
import CardTitle from "./CardTitle.vue";
import CardActions from "./CardActions.vue";
import CardImage from "./CardImage.vue";

const props = defineProps<CardProps>()

const variants = {
    primary: "bg-primary",
    secondary: "bg-secondary",
    accent: "bg-accent",
    info: "bg-info",
    neutral: "bg-neutral",
    base: "bg-base-100",
    success: "bg-success",
    warning: "bg-warning",
    error: "bg-error",
    default: "",
}
</script>
