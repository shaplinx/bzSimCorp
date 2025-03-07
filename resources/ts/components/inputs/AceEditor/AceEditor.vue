<template>
    <v-ace-editor
    :value="modelValue"
    @update:value="val => $emit('update:modelValue',val)"
    lang="html"
    theme="chrome"
    style="height: 300px" />
</template>
<script setup lang="ts">
import { VAceEditor } from 'vue3-ace-editor';
import 'ace-builds/src-noconflict/mode-ejs';
import 'ace-builds/src-noconflict/theme-chrome?url';

defineProps({
    modelValue: {
        type: String,
        default: ''
    }
})



</script>
