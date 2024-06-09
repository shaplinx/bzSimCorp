<template>
    <div>
        <div role="tablist" class="tabs tabs-boxed max-w-fit mb-2">
            <a role="tab" class="tab" :class="{ 'tab-active': tab === 'code' }" @click="tabClick('code')">Code</a>
            <a role="tab" class="tab" :class="{ 'tab-active': tab === 'preview' }"
                @click="tabClick('preview')">Preview</a>
        </div>

        <div class="border rounded-lg border-base-300 overflow-hidden">
            <spinner-overlay v-show="loading"></spinner-overlay>
            <codemirror v-if="tab === 'code'" :model-value="modelValue"
                @update:model-value="(val) => { $emit('update:modelValue', val) }" placeholder="Code gose here..."
                :style="{ height: '400px' }" :autofocus="true" :indent-with-tab="true" :tabSize="2"
                :extensions="extensions" />
            <div class="p-4 w-full h-full" v-if="tab === 'preview'"><div v-html="modelValue"></div></div>

        </div>

    </div>
</template>

<script setup lang="ts">
import { EditorView } from "@codemirror/view"
import { tags as t } from "@lezer/highlight"
import { Codemirror } from 'vue-codemirror'
import { HighlightStyle, syntaxHighlighting , LanguageSupport, LRLanguage} from "@codemirror/language"
import {parser as htmlParserLezer} from "@lezer/html"
import {ejsLanguage } from "codemirror-lang-ejs";
import {format} from 'prettier';
import htmlParserPrettier from 'prettier/plugins/html';
import { ref } from "vue";
import SpinnerOverlay from "@/components/loader/SpinnerOverlay.vue";
import {parseMixed} from "@lezer/common"
import unescape from "lodash/unescape"


const props = defineProps({
    modelValue: String
})
const emits = defineEmits(["update:modelValue"])


const tab = ref<'preview' | 'code'>('code')
const loading = ref(false)

async function formatHtml() {
    loading.value = true
    return format(props.modelValue || '', {
        parser: 'html',
        plugins: [htmlParserPrettier],
    }).then((val) => {
        emits("update:modelValue", unescape(val))
    }).finally(()=> {
        loading.value = false
    });
}

function tabClick(state : "preview" | "code") {
    tab.value = state;
    formatHtml()
}




const info = "hsl(var(--in))",
    secondary = "hsl(var(--s))",
    cyan = "hsl(var(--ic))",
    error = "hsl(var(--er))",
    baseText = "hsl(var(--bc))",
    darkText = "hsl(var(--nc))",
    neutral = "hsl(var(--nf))", // Brightened compared to original to increase contrast
    accent = "hsl(var(--a))",
    success = "hsl(var(--su))",
    warning = "hsl(var(--wa))",
    primary = "hsl(var(--p))",
    darkBackground = "hsl(var(--n))",
    highlightBackground = "hsl(var(--b1))",
    highlightDarkBackground = "hsl(var(--nf))",
    background = "hsl(var(--b1))",
    tooltipBackground = "hsl(var(--b2))",
    selection = "hsl(var(--b3))",
    cursor = "hsl(var(--bc))"

/// The colors used in the theme, as CSS color strings.

const theme = EditorView.theme({
    "&": {
        color: baseText,
        backgroundColor: background
    },

    ".cm-content": {
        caretColor: cursor
    },

    ".cm-cursor, .cm-dropCursor": { borderLeftColor: cursor },
    "&.cm-focused > .cm-scroller > .cm-selectionLayer .cm-selectionBackground, .cm-selectionBackground, .cm-content ::selection": { backgroundColor: selection },

    ".cm-panels": { backgroundColor: darkBackground, color: baseText },
    ".cm-panels.cm-panels-top": { borderBottom: "2px solid black" },
    ".cm-panels.cm-panels-bottom": { borderTop: "2px solid black" },

    ".cm-searchMatch": {
        backgroundColor: "#72a1ff59",
        outline: "1px solid #457dff"
    },
    ".cm-searchMatch.cm-searchMatch-selected": {
        backgroundColor: "#6199ff2f"
    },

    ".cm-activeLine": { backgroundColor: "#6699ff0b" },
    ".cm-selectionMatch": { backgroundColor: "#aafe661a" },

    "&.cm-focused .cm-matchingBracket, &.cm-focused .cm-nonmatchingBracket": {
        backgroundColor: "#bad0f847"
    },

    ".cm-gutters": {
        backgroundColor: darkBackground,
        color: darkText,
        border: "none"
    },

    ".cm-activeLineGutter": {
        backgroundColor: highlightDarkBackground
    },

    ".cm-foldPlaceholder": {
        backgroundColor: "transparent",
        border: "none",
        color: "#ddd"
    },

    ".cm-tooltip": {
        border: "none",
        backgroundColor: tooltipBackground
    },
    ".cm-tooltip .cm-tooltip-arrow:before": {
        borderTopColor: "transparent",
        borderBottomColor: "transparent"
    },
    ".cm-tooltip .cm-tooltip-arrow:after": {
        borderTopColor: tooltipBackground,
        borderBottomColor: tooltipBackground
    },
    ".cm-tooltip-autocomplete": {
        "& > ul > li[aria-selected]": {
            backgroundColor: highlightBackground,
            color: baseText
        }
    }
}, { dark: false })

const highlightStyle = HighlightStyle.define([
    {
        tag: t.keyword,
        color: primary
    },
    {
        tag: [t.name, t.deleted, t.character, t.propertyName, t.macroName],
        color: secondary
    },
    {
        tag: [t.function(t.variableName), t.labelName],
        color: accent
    },
    {
        tag: [t.color, t.constant(t.name), t.standard(t.name)],
        color: warning
    },
    {
        tag: [t.definition(t.name), t.separator],
        color: baseText
    },
    {
        tag: [t.typeName, t.className, t.number, t.changed, t.annotation, t.modifier, t.self, t.namespace],
        color: info
    },
    {
        tag: [t.operator, t.operatorKeyword, t.url, t.escape, t.regexp, t.link, t.special(t.string)],
        color: cyan
    },
    {
        tag: [t.meta, t.comment],
        color: neutral
    },
    {
        tag: t.strong,
        fontWeight: "bold"
    },
    {
        tag: t.emphasis,
        fontStyle: "italic"
    },
    {
        tag: t.strikethrough,
        textDecoration: "line-through"
    },
    {
        tag: t.link,
        color: neutral,
        textDecoration: "underline"
    },
    {
        tag: t.heading,
        fontWeight: "bold",
        color: secondary
    },
    {
        tag: [t.atom, t.bool, t.special(t.variableName)],
        color: warning
    },
    {
        tag: [t.processingInstruction, t.string, t.inserted],
        color: success
    },
    {
        tag: t.invalid,
        color: error
    },
])

let ejsHtmlParser = ejsLanguage.parser.configure({
  wrap: parseMixed(node => {
    return node.name == "Text" ? {parser: htmlParserLezer} : null
  })})

let ejsHtmlLang =  LRLanguage.define({parser:ejsHtmlParser})

const extensions = [new LanguageSupport(ejsHtmlLang), theme, syntaxHighlighting(highlightStyle)]

</script>
