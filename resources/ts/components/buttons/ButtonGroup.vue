<template>
    <div class="flex gap-2 align-middle">
        <div class="join" :class="[containerClass,{ 'join-vertical': mode === 'vertical', 'join-horizontal': mode === 'horizontal' }]">
            <template  v-for="(button, k) in buttons">
            <Button v-if="!hasNamedSlot(`button-${button.value ?? k}`)" class="join-item"  v-on="button.on ?? {}" :key="k" v-bind="button"
                :variant="button.variant"
                :disabled="button.disabled"
                :class="[buttonClass]">
                {{ button.label }}
            </Button>
            <slot v-else :name="`button-${button.value ?? k}`" :button="button" />
            </template>
        </div>
    </div>
</template>
<script setup lang="ts">
import { PropType, defineProps, getCurrentInstance } from "vue";
import type { ButtonProps } from "../@types/button";
import Button from "./Button.vue";

defineProps({
    buttons: {
        type: Array as PropType<ButtonProps[]>,
        required: true,
    },
    mode: {
        type: String as PropType<"horizontal" | "vertical">,
        default: "horizontal",
    },
    disabled: {
        type: Boolean,
        default: false
    },
    buttonClass: {
        type: String,
        required: false
    },
    containerClass: {
        type: String,
        required: false
    }
});

const instance = getCurrentInstance();

function hasNamedSlot(slotName: any) {
    return Object.prototype.hasOwnProperty.call(instance?.slots, slotName);
}


</script>
