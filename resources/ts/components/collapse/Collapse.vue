<template>
    <div :class="containerClass">
        <input type="checkbox" :class="checkboxClass" @change="$emit('update:show',isOpen)" v-model="isOpen"/>
        <div :class="titleClass">
            <slot name="title" :title="title" :content="content">
                {{ title }}
            </slot>
        </div>
        <div :class="contentClass">
            <slot name="default" :title="title" :content="content">
                {{ content }}
            </slot>
        </div>
    </div>
</template>
<script lang="ts" setup>

import { ref, watch } from 'vue';

const props = withDefaults(defineProps<{
    containerClass?: string,
    checkboxClass?: string,
    titleClass?: string,
    contentClass?: string,
    title?: string
    content?: string
    show?: boolean
}>(), {
    containerClass: "bg-base-100 collapse",
    checkboxClass: "peer",
    titleClass: "collapse-title",
    contentClass: "collapse-content",
})

const isOpen = ref(props.show)

watch(()=> props.show, (val)=> isOpen.value = val ? true:false)

</script>
