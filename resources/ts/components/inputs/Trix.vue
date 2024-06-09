<template>
    <div class="editor">
        <label v-if="label" :class="{required: required}">{{ label }}</label>
        <trix-editor
            :contenteditable="!disabledEditor"
            :class="['trix-content']"
            :placeholder="placeholder"
            :input="computedId"
            ref="trix"
            @trix-change="handleContentChange"
            @trix-file-accept="emitFileAccept"
            @trix-attachment-add="emitAttachmentAdd"
            @trix-attachment-remove="emitAttachmentRemove"
            @trix-selection-change="emitSelectionChange"
            @trix-initialize="handleInitialize"
            @trix-before-initialize="emitBeforeInitialize"
            @trix-focus="processTrixFocus"
            @trix-blur="processTrixBlur"
        />
        <input
            type="hidden"
            :name="inputName"
            :id="computedId"
            :value="editorContent"
        />
</div>
</template>

<script setup>
import Trix from 'trix'

const props = defineProps({
    disabledEditor: {
        type: Boolean,
        required: false,
        default: false
    },
    srcContent: {
        type: String,
        required: false,
        default: ''
    },
    inputId: {
        type: String,
        required: false,
        default: ''
    },
    inputName: {
        type: String,
        required: false,
        default: 'content'
    },
    placeholder: {
        type: String,
        required: false,
        default: ''
    },
    label: {
        type: String,
        required: false
    },
    required: {
        type: Boolean,
        required: false
    },
})
const trix = ref(null);
const editorContent = ref(props.srcContent)
const isActive = ref(null)
const isInitalized = ref(false)
const initalizeQueue = ref([])
const emits = defineEmits(['input', 'update', 'update:srcContent', 'trix-file-accept', 'trix-attachment-add', 'trix-attachment-remove', 'trix-selection-change', 'trix-initialize', 'trix-before-initialize', 'trix-focus', 'trix-blur'])
const handleContentChange = (event) => {
    editorContent.value = event.srcElement ? event.srcElement.value : event.target.value
    emits('input', editorContent.value)
}
const handleInitialize = () => {
    isInitalized.value = true
    initalizeQueue.value.forEach(item => item())
    decorateDisabledEditor(props.disabledEditor)
    emits('trix-initialize')
}
const handleInitialContentChange = (newContent, oldContent) => {
    newContent = newContent === undefined ? '' : newContent
    if(trix.value && trix.value.innerHTML !== newContent) {
        editorContent.value = newContent
    }
    if(!isActive.value) {
        reloadEditorContent(editorContent.value)
    }
}
const emitEditorState = (value) => {
    emits('update', editorContent.value)
    emits('update:srcContent', editorContent.value)
}
const emitFileAccept = (file) => {
    emits('trix-file-accept', file)
}
const emitAttachmentAdd = (file) => {
    emits('trix-attachment-add', file)
}
const emitAttachmentRemove = (file) => {
    emits('trix-attachment-remove', file)
}
const emitSelectionChange = (event) => {
    emits('trix-selection-change', trix.value.editor, event)
}
const emitBeforeInitialize = async (event) => {
    whenInitalized(() => {
        emits('trix-before-initialize', trix.value.editor, event)
    })
}
const processTrixFocus = (event) => {
    isActive.value = true
    emits('trix-focus', trix.value.editor, event)
}
const processTrixBlur = (event) => {
    isActive.value = false
    emits('trix-blur', trix.value.editor, event)
}
const whenInitalized = (func) => {
    if(isInitalized.value) {
        func()
    } else {
        initalizeQueue.value.push(func)
    }
}
const reloadEditorContent = async (newContent) => {
    whenInitalized(() => {
        trix.value.editor.loadHTML(newContent)
        trix.value.editor.setSelectedRange(getContentEndPosition())
    })
}
const decorateDisabledEditor = async (editorState) => {
    whenInitalized(() => {
        if (editorState) {
            trix.value.toolbarElement.style['pointer-events'] = 'none'
            trix.value.contentEditable = false
            trix.value.style['background'] = '#e9ecef'
        } else {
            trix.value.toolbarElement.style['pointer-events'] = 'unset'
            trix.value.style['pointer-events'] = 'unset'
            trix.value.style['background'] = '#ffffff'
        }
    })
}
const getContentEndPosition = () => {
    return trix.value.editor.getDocument().toString().length - 1
}
const randomId = () => {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
        var r = Math.random() * 16 | 0
        var v = c === 'x' ? r : (r & 0x3 | 0x8)
        return v.toString(16)
    })
}
const generatedId = computed(() => {
    return randomId()
})
const computedId = computed(() => {
    return props.inputId || generatedId.value
})
const initialContent = computed(() => {
    return props.srcContent
})
const isDisabled = computed(() => {
    return props.disabledEditor
})
watch(editorContent, emitEditorState)
watch(initialContent, handleInitialContentChange)
watch(isDisabled, decorateDisabledEditor)
/** Extra Toolbar Buttons **/
const addToolbarButton = async (name, options, func) => {
    whenInitalized(() => {
        options.icon = options.icon || '?'
        options.group = options.group || 'text'
        options.position = options.position || 'beforeend'
        options.id = options.id || randomId()
        if(options.trixAttribute && options.trixAttribute.type && options.trixAttribute.data && Trix.config[options.trixAttribute.type + 'Attributes']) {
            Trix.config[options.trixAttribute.type + 'Attributes'][name] = options.trixAttribute.data
        }
        if(options.html) {
            options.html = options.html.replace(/%id%/ig, options.id)
            if(func) {
                options.html = options.html.replace(/%func\((.*?)\)%/ig, `Trix.$extensions.${name}(event, '${name}', '${options.id}', $1)`)
            }
        }
        let toolbarId = trix.value.getAttribute('toolbar')

        if(func) {
            if(Trix.$extensions === undefined) Trix.$extensions = {}
            Trix.$extensions[name] = func
        }
        document.getElementById(toolbarId)
            .querySelector(`.trix-button-group.trix-button-group--${options.group}-tools`)
            .insertAdjacentHTML(options.position, `${options.divWrap ? '<div style="position:relative" class="trix-button trix-button--icon">' : ''}<button type="button" ${options.divWrap ? '' : 'class="trix-button trix-button--icon"'} data-trix-attribute="${name}" ${func ? `onClick="Trix.$extensions.${name}(event, '${name}', '${options.id}', 'click')"` : ''} ${options.title ? `title="${options.title}"` : ''}>${options.icon}</button>${options.html ? `${options.html}` : ''}${options.divWrap ? '</div>' : ''}`)
    })
}
/* Foreground and Background Colors - Based on https://github.com/basecamp/trix/issues/985 */
const foregroundColor = {
    icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M512 256c0 .9 0 1.8 0 2.7c-.4 36.5-33.6 61.3-70.1 61.3H344c-26.5 0-48 21.5-48 48c0 3.4 .4 6.7 1 9.9c2.1 10.2 6.5 20 10.8 29.9c6.1 13.8 12.1 27.5 12.1 42c0 31.8-21.6 60.7-53.4 62c-3.5 .1-7 .2-10.6 .2C114.6 512 0 397.4 0 256S114.6 0 256 0S512 114.6 512 256zM128 288c0-17.7-14.3-32-32-32s-32 14.3-32 32s14.3 32 32 32s32-14.3 32-32zm0-96c17.7 0 32-14.3 32-32s-14.3-32-32-32s-32 14.3-32 32s14.3 32 32 32zM288 96c0-17.7-14.3-32-32-32s-32 14.3-32 32s14.3 32 32 32s32-14.3 32-32zm96 96c17.7 0 32-14.3 32-32s-14.3-32-32-32s-32 14.3-32 32s14.3 32 32 32z"/></svg>',
    group: 'text',
    position: 'beforeend',
    title: 'Text colour',
    html: '<input type="color" style="position:absolute;top:0;left:0;height:100%;width:100%;opacity:0" id="%ID%-picker" onchange="%func(\'colorChanged\')%" />',
    divWrap: true,
    trixAttribute: {
        type: 'text',
        data: {
            styleProperty: 'color',
            inheritable: true
        }
    },
}
const backgroundColor = {
    icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M41.4 9.4C53.9-3.1 74.1-3.1 86.6 9.4L168 90.7l53.1-53.1c28.1-28.1 73.7-28.1 101.8 0L474.3 189.1c28.1 28.1 28.1 73.7 0 101.8L283.9 481.4c-37.5 37.5-98.3 37.5-135.8 0L30.6 363.9c-37.5-37.5-37.5-98.3 0-135.8L122.7 136 41.4 54.6c-12.5-12.5-12.5-32.8 0-45.3zm176 221.3L168 181.3 75.9 273.4c-4.2 4.2-7 9.3-8.4 14.6H386.7l42.3-42.3c3.1-3.1 3.1-8.2 0-11.3L277.7 82.9c-3.1-3.1-8.2-3.1-11.3 0L213.3 136l49.4 49.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0zM512 512c-35.3 0-64-28.7-64-64c0-25.2 32.6-79.6 51.2-108.7c6-9.4 19.5-9.4 25.5 0C543.4 368.4 576 422.8 576 448c0 35.3-28.7 64-64 64z"/></svg>',
    group: 'text',
    position: 'beforeend',
    title: 'Background colour',
    html: '<input type="color" style="position:absolute;top:0;left:0;height:100%;width:100%;opacity:0" id="%ID%-picker" onchange="%func(\'colorChanged\')%" />',
    divWrap: true,
    trixAttribute: {
        type: 'text',
        data: {
            styleProperty: 'backgroundColor',
            inheritable: true
        }
    },
}
const fgBgColorFunc = (event, name, id, data) => {
    var picker = document.getElementById(id + '-picker')
    if(data == 'colorChanged') {
        trix.value.editor.activateAttribute(name, picker.value)
    }
}
addToolbarButton('foreground', foregroundColor, fgBgColorFunc)
addToolbarButton('background', backgroundColor, fgBgColorFunc)
/* Text align center button - No function needed for this button */
addToolbarButton('textAlignCenter', {
    icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M352 64c0-17.7-14.3-32-32-32H128c-17.7 0-32 14.3-32 32s14.3 32 32 32H320c17.7 0 32-14.3 32-32zm96 128c0-17.7-14.3-32-32-32H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H416c17.7 0 32-14.3 32-32zM0 448c0 17.7 14.3 32 32 32H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H32c-17.7 0-32 14.3-32 32zM352 320c0-17.7-14.3-32-32-32H128c-17.7 0-32 14.3-32 32s14.3 32 32 32H320c17.7 0 32-14.3 32-32z"/></svg>',
    group: 'block',
    position: 'beforeend',
    title: 'Align text center',
    trixAttribute: {
        type: 'block',
        data: {
            tagName: 'centered',
        }
    }
})
</script>

<style lang="scss">
/* For the added button above */
centered {
    display: block;
    text-align: center;
}
/* Extra Trix Styles to support the above code*/
.trix-button-group {
    .trix-button{
        text-align: -webkit-center;
        text-align: -moz-center;
        svg {
            height: 20px;
            width: 20px;
            opacity: 0.6;
            display: block;
        }
        /* For buttons inside divWrap option */
        button {
            display: block;
            border: none;
            background: transparent;
            height: 100%;
            width: 100%;
        }
    }
}
/* My own theme */
.editor {
    width: 100%;
    trix-editor {
        border-radius: 12px;
        border-color: $border-color;
        min-height: 20rem;
        padding: map-get($spacer, 3) map-get($spacer, 2);
        background-color: #fff;
    }
    .trix-button-group {
        border-radius: 10px;
        border-color: $border-color;
        background-color: #fff;
        .trix-button {
            border-bottom: 0;
            border-color: $border-color;
            &::before {
                background-size: 50%;
            }
            &:first-child {
                border-top-left-radius: 10px;
                border-bottom-left-radius: 10px;
            }
            &:last-child {
                border-top-right-radius: 10px;
                border-bottom-right-radius: 10px;
            }
            &:hover:not(:disabled) {
                background-color: rgba(0,0,0,0.1);
            }
        }
    }
}
</style>
