<template>
    <div>
        <div class="flex gap-2 my-2 flex-row-reverse items-start">
            <slot name="buttons" :buttons="buttons">
                <div v-if="buttons" class="btn-group">
                    <Button v-for="button in buttons" v-bind="button">
                        {{ button.label }}
                    </Button>
                </div>
            </slot>
            <slot name="title">
                <h2 v-if="props.title" class="text-xl font-semibold mr-auto leading-tight text-base-content mb-2">
                    {{ props.title }}
                </h2>
            </slot>
        </div>
        <div>
            <div :class="containerClass ? containerClass : 'min-w-full bg-base-100 rounded-box pb-5 shadow-md'">
                <slot> </slot>
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
import { type ButtonProps } from "@/components/@types/button";
import Button from "../buttons/Button.vue";
import { type PropType } from "vue";
import type { IconDefinition } from "@fortawesome/free-brands-svg-icons"



const props = defineProps({
    title: String,
    buttons: Array as PropType<Array<ButtonProps & { icon: IconDefinition | string }>>,
    useFilter: Boolean,
    filterTitle: String,
    containerClass: String,
});
</script>
