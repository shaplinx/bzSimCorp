<template>
    <div class="mt-8">
        <div class="mt-6 ">
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
            <div v-if="useFilter" class="mb-2">
                    <FormKit @keydown.enter.prevent class="" :actions="false" :model-value="modelValue"
                        @input="(val: any) => $emit('update:modelValue', val)" type="form" @submit.prevent>
                        <div class="flex gap-2 mb-0 flex-col flex-wrap flex-grow sm:flex-row sm:flex-grow">
                            <FormKitSchema v-if="props.filterSchema" :schema="props.filterSchema" />
                        </div>
                    </FormKit>
            </div>

            <div>
                <div :class="containerClass ? containerClass : 'min-w-full bg-base-100 rounded-box pb-5 shadow-md'" >
                    <slot> </slot>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
import type { FormKitSchemaDefinition, FormKitGroupValue } from "@formkit/core";
import { FormKitSchema, FormKit} from "@formkit/vue";
import { type ButtonProps } from "@/components/@types/button";
import Button from "../buttons/Button.vue";
import { type PropType } from "vue";
import type { IconDefinition } from "@fortawesome/free-brands-svg-icons"



const props = defineProps({
    title: String,
    buttons: Array as PropType<Array<ButtonProps & { icon: IconDefinition | string }>>,
    useFilter: Boolean,
    filterTitle: String,
    filterSchema: Array as PropType<FormKitSchemaDefinition>,
    modelValue: Object as PropType<FormKitGroupValue>,
    containerClass:String,
});
</script>
