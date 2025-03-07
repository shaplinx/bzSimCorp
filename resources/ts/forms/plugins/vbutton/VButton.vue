<template>
  <Button v-bind="context?.attrs" :label="context?.label" />
</template>
<script setup lang="ts">

import Button from "@/components/buttons/Button.vue";
import { FormKitFrameworkContext } from "@formkit/core";
import { PropType } from "vue";

defineProps({
  context: Object as PropType<FormKitFrameworkContext>,
});

</script>
