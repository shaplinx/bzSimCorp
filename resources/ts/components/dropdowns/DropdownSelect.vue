<template>
  <VDropdown>
    <Button v-bind="props.buttonProps">
      <div class="flex flex-row flex-grow align-middle">
        <FontAwesomeIcon v-if="modelValue.icon" :icon="modelValue.icon"></FontAwesomeIcon
        ><span class="mr-auto">{{
          modelValue[props.labelkey] || placeholder
        }}</span>
      </div>
      <FontAwesomeIcon class="ml-4" icon="fa-square-caret-down"></FontAwesomeIcon>
    </Button>
    <template #popper>
      <DropdownMenuContent>
        <li
          v-close-popper
          v-for="option in props.options"
          @click="$emit('update:modelValue', option)"
        >
          <a
            ><FontAwesomeIcon v-if="option.icon" :icon="option.icon"></FontAwesomeIcon
            >{{ option[props.labelkey] }}</a
          >
        </li>
      </DropdownMenuContent>
    </template>
  </VDropdown>
</template>

<script setup lang="ts">
import Button from "../buttons/Button.vue";
import type { ButtonProps } from "@/components/@types/button";
import { DropdownOption } from "./dropdown";
import DropdownMenuContent from "./DropdownMenuContent.vue";
const props = withDefaults(
  defineProps<{
    options: DropdownOption[];
    labelkey?: string;
    modelValue: any;
    placeholder?: string;
    buttonProps?: ButtonProps;
  }>(),
  {
    labelkey: "label",
    placeholder: "Select One",
  }
);
</script>

<style scoped></style>
