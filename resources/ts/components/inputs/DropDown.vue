<template>
  <DropdownMenuVue v-bind="$attrs">
    <Button outline :size="size">{{ options.find((x:any)=> x.value === modelValue)?.label ?? $t("menu.selectDate") }}</Button>

    <template #popper>
      <li
        v-close-popper
        v-for="option in options"
        @click="$emit('update:modelValue', option)"
      >
        <a
          ><FontAwesomeIcon v-if="option.icon" :icon="option.icon"></FontAwesomeIcon>{{ option.label }}</a
        >
      </li>
    </template>
  </DropdownMenuVue>
</template>
<script lang="ts" setup>
import { defineProps } from "vue";
import DropdownMenuVue from "@/components/dropdowns/DropdownMenu.vue";
import Button from "../buttons/Button.vue";

defineProps(["modelValue", "options", "size"]);
</script>
