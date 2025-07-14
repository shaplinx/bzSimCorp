<template>
  <Button v-bind="context?.attrs" :disabled="!!context?.disabled" :label="context?.label" />
</template>
<script setup lang="ts">

import Button from "@/components/buttons/Button.vue";
import { FormKitFrameworkContext } from "@formkit/core";
import { PropType } from "vue";

const props = defineProps({
  context: Object as PropType<FormKitFrameworkContext>,
});

</script>
