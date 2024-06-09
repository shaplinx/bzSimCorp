<template>
    <editor-content :editor="editor" />
</template>

<script setup lang="ts">
import StarterKit from '@tiptap/starter-kit'
import { Editor, EditorContent } from '@tiptap/vue-3'

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
})

const emits = defineEmits(['update:modelValue'])

watch(() => props.modelValue, (value) => {
    const isSame = editor.getHTML() === value

    if (isSame) {
        return
    }
    editor.commands.setContent(value, false)
},
)

const editor = new Editor({
    extensions: [
        StarterKit,
    ],
    content: props.modelValue,
    onUpdate: () => {
        emits('update:modelValue', editor.getHTML())
    },
})
onBeforeUnmount(() => [
    editor.destroy()
])
</script>
