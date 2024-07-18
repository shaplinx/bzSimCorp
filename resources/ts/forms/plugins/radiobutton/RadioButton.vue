<template>
  <ButtonGroupInput :disabled="(context?.disabled as boolean)" class="mb-3" :allowCustom="context?.attrs.allowCustom" :customLabel="context?.attrs.customLabel" :buttons="context?.attrs.options" :modelValue="props.context?.value"  :mode="context?.attrs.mode" @update:modelValue="onInput"/>
</template>
<script setup lang="ts">
import { FormKitFrameworkContext } from "@formkit/core";
import { PropType } from "vue";
import ButtonGroupInput from "@/components/buttons/ButtonGroupInput.vue";

const props = defineProps({
  context: Object as PropType<FormKitFrameworkContext>,
});

const onInput = (val:any) => {
    props.context?.node.input(val)
}
</script>
<style scoped>
.formkit-inner {
    box-shadow: 0 !important;


}
</style>
