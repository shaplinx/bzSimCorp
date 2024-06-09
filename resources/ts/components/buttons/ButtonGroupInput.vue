<template>
    <div class="flex gap-2 align-middle">
  <div class="join" :class="{'join-vertical':mode === 'vertical', 'join-horizontal':mode === 'horizontal'}" >
    <Button
      v-for="(button, k) in buttons"
      :key="k"
      class="join-item"
      v-bind="button"
      :variant="modelValue == button.value ? button.variant : undefined"
      @click.prevent="updateModel(button.value)"
      :disabled="button.disabled"
      >{{ button.label }}</Button
    >
    <Button class="join-item" v-if="allowCustom" :variant="isCustom ? 'neutral' : undefined" @click.prevent="$emit('update:modelValue', null)"> {{  customLabel }}</Button>
  </div>
  <input :disabled="disabled" v-if="allowCustom && isCustom" class="input input-bordered w-full max-w-xs" type="text" :value="modelValue" @input="(val : any) =>  updateModel(val.target.value)" />
</div>
</template>
<script setup lang="ts">
import { PropType, defineProps } from "vue";
import type { ButtonProps } from "../@types/button";
import Button from "./Button.vue";
import {computed, watch} from "vue"


const props = defineProps({
  buttons: {
    type: Array as PropType<ButtonProps[]>,
    required: true,
  },
  modelValue: {
    type: [Number, String, Boolean],
  },
  mode: {
    type: String as PropType<"horizontal" | "vertical">,
    default: "horizontal",
  },
  allowCustom : {
    type:Boolean,
    default:false
  },
  customLabel: {
    type:String,
    default:"Others"
  },
  disabled: {
    type:Boolean,
    default:false

  }
});

const emits =defineEmits(["update:modelValue"])


const isCustom= computed(()=> {
    return !props.buttons.filter((btn) => btn.value === props.modelValue)?.length  || !props.modelValue === undefined
})

function updateModel(val : number | string | null | undefined) {
    if (!props.disabled) emits("update:modelValue", val)
}

watch(() => props.modelValue, (val) => {
if (val === undefined) updateModel(null)
} )


</script>
