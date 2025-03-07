<template>
    <AccordionWrapper>
        <AccordionItem v-for="(item,key) in items" :label="item.label" :content="item.content" :modelValue="isOpen.includes(key)" @update:modelValue="val => onCheckboxChange(val,key)"></AccordionItem>
    </AccordionWrapper>
</template>
<script setup lang="ts">
import { watch, ref } from 'vue';
import AccordionItem from './AccordionItem.vue';
import AccordionWrapper from './AccordionWrapper.vue';
const props = defineProps<{
    items?: {
        label: any,
        content: any
    }[]
    isOpen: number[],
    multiple?: boolean
}>()

const isOpen = ref(props.isOpen)
watch(()=> props.isOpen, (val)=> {
    isOpen.value = val
})

function onCheckboxChange(val :boolean, key: number) {
    if (!props.multiple) return isOpen.value= [key]
    if (val) {
        return isOpen.value.push(key)
    }
    isOpen.value = isOpen.value.filter(o => o !== key)
}
</script>
