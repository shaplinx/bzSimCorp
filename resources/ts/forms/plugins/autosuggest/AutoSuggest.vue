<template>
    <AutoSuggest :modelValue="context?._value" v-bind="context?.attrs" :classes="{
        options: context?.classes.options,
        option: context?.classes.option,
        input: context?.classes.input,
    }" @search="context?.onSearch" @update:modelValue="(val, item) =>
        context?.node.input(item ? context.attrs.reduce?.(item) ?? item : val)
    " @selected="onSelected" />
</template>
<script setup lang="ts">
import AutoSuggest from "@/components/inputs/AutoSuggest.vue";
import { FormKitFrameworkContext } from "@formkit/core";
import { PropType } from "vue";

const props = defineProps({
    context: Object as PropType<FormKitFrameworkContext>,
});

const onSelected = (val: string, item: any) => {
    props.context?.attrs.onSelect?.(val, item, props.context.node);
};
</script>
