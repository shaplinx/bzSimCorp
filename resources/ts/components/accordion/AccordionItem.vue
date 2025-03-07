<template>
    <div class="collapse collapse-arrow join-item border-base-300 border">
        <input type="radio" :name="name" :checked="modelValue" @change="(e : any) => $emit('update:modelValue', e.target.checked)" />
        <div class="collapse-title "><slot name="title" :label="label">{{ label }}</slot></div>
        <div class="collapse-content">
            <slot name="content" :content="content">{{ content }}</slot>
            <slot></slot>
        </div>
    </div>
</template>
<script setup lang="ts">
defineProps<{
    label?: any
    content?: any
    modelValue?: boolean
    name?:string
}>()

</script>
