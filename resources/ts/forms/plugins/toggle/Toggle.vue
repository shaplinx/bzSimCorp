<template>
  <Toggle :modelValue="context?._value" @update:modelValue="onInput" v-bind="context?.attrs" :type="context?.attrs.color"/>
</template>
<script setup lang="ts">
import Toggle from "@/components/inputs/Toggle.vue"
import { FormKitFrameworkContext } from "@formkit/core";
import { PropType } from "vue";


const props = defineProps({
  context: Object as PropType<FormKitFrameworkContext>,
});

const onInput = (val: any) => {
  props.context?.node.input(val);
};
</script>
<style scoped>

</style>
