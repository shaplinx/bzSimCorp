<template>
    <div class="flex flex-col sm:flex-row lg:felx-col xl:flex-row xl:items-center justify-start gap-2">
        <span class="font-semibold text-left flex-shrink xl:text-right xl:w-5/12">
            <slot name="key" :field="field"> {{ field }}</slot>
        </span>
        <span class="hidden sm:block xl:text-center flex-shrink xl:w-1/12" :class="loading && 'opacity-0'">:</span>
        <span class="xl:text-md xl:text-left flex-grow xl:w-6/12">
            <slot name="value" :value="value"> {{ value }}</slot>
        </span>
    </div>
</template>
<script setup lang="ts">
import { defineProps } from 'vue';

defineProps({
    field:[String, Number],
    value:[String, Number],
    loading:Boolean
})
</script>
