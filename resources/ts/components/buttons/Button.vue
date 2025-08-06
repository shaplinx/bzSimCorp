<template>
    <component  :is="tag ?? 'button'" :disabled="loading" @click="handleOnClick" :class="[
        'btn',
        variant ? variants[variant] : variants.default,
        size ? sizes[size] : sizes.default,
        shape ? shapes[shape] : shapes.default,

        {
            'btn-wide': wide,
            'btn-disabled': disabled,
            'btn-block': block,
            'btn-outline': outline,
            'btn-active': active,
            'no-animation': noAnimation || finalLoading,
        }
    ]">
        <div class="flex align-middle justify-between gap-3">
            <LoadingVue v-show="finalLoading" :size="size"></LoadingVue>
            <span class="my-auto">
                <font-awesome-icon v-show="!finalLoading" v-if="icon" :icon="icon" :class="shape ? '': 'mr-2'"></font-awesome-icon>
                <slot name="default" :props="props"  v-if="showContent">
                    {{ props.label }}
                </slot>
            </span>

        </div>
    </component>
</template>
<script setup lang="ts">
import {  computed, ref } from "vue";
import type { ButtonProps } from "../@types/button";
import LoadingVue from "@/components/loader/Loading.vue"
const props = defineProps<ButtonProps>();

const variants = {
    primary: "btn-primary",
    secondary: "btn-secondary",
    accent: "btn-accent",
    info: "btn-info",
    neutral: "btn-neutral",
    success: "btn-success",
    warning: "btn-warning",
    error: "btn-error",
    ghost: "btn-ghost",
    glass: "glass",
    link: "btn-link",
    default: "",
};

const sizes = {
    lg: "btn-lg",
    md: "btn-md",
    sm: "btn-sm",
    xs: "btn-xs",
    default: "",
};

const shapes = {
    circle: "btn-circle",
    square: "btn-square",
    default: "",
};

const clickLoading = ref(false);

const finalLoading = computed(() => clickLoading.value || props.loading);

const handleOnClick = async (e: any) => {
    if (finalLoading.value || typeof props.onClick !== 'function') return;
    clickLoading.value = true;
    try {
        await props.onClick(e);
    } catch (error) {
        console.error('Click handler failed:', error);
    } finally {
        clickLoading.value = false;
    }
};

const showContent = computed(() => {
    if (['circle', 'square'].includes(props.shape!)) {
        return !finalLoading.value;
    }
    return true;
});

</script>

