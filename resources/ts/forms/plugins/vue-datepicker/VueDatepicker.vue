<template>
  <Datepicker
    v-bind="context?.attrs"
    @update:modelValue="(val:any) => input(val)"
    :modelValue="context?.value"
    :utc="true"
    timezone="Asia/Jakarta"
  />
</template>

<script setup lang="ts">
import Datepicker from "@vuepic/vue-datepicker";
import { FormKitFrameworkContext } from "@formkit/core";
import { PropType } from "vue";

const props = defineProps({
  context: Object as PropType<FormKitFrameworkContext>,
});

const input = (val: any) => {
  props.context?.node.input(val);
};
</script>
