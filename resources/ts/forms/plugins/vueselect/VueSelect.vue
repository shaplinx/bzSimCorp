<template>
    <div class="vue-select-container">
        <VueSelect :modelValue="props.context?._value || null" @update:modelValue="(value: any) => updated(value)"
            v-bind="props.context?.attrs" :label="props.context?.attrs.displayLabel"
            :delay="props.context?.attrs.debounce" :classes="classes" :disabled="(props.context?.disabled as boolean)"
            ref="vueselect">
            <template #option="{ option }, isPointed, isSelected, search">
                <div v-tooltip="props.context?.attrs.statusTooltip?.[renderStatus(option)]">
                    <FontAwesomeIcon v-if="renderStatus(option) == 'warning'" class="text-warning"
                        :icon="faTriangleExclamation"></FontAwesomeIcon>
                    <FontAwesomeIcon v-if="renderStatus(option) == 'error'" class="text-error"
                        :icon="faTriangleExclamation"></FontAwesomeIcon>
                    {{ option[props.context?.attrs.displayLabel] }}
                </div>
            </template>
        </VueSelect>
    </div>
</template>
<script setup lang="ts">
import VueSelect from "@vueform/multiselect";
import { FormKitFrameworkContext } from "@formkit/core";
import { PropType, ref } from "vue";
import { faTriangleExclamation } from "@fortawesome/free-solid-svg-icons";
const props = defineProps({
    context: Object as PropType<FormKitFrameworkContext>,
});

const vueselect = ref<any>(null);
const updated = (val: any) => {
    props.context?.node.input(val);
};

props.context?.node.on("input", ({ payload }) => {
    if (vueselect.value) {
        props.context?.attrs.onChange?.(payload, vueselect.value);
    }
});

function renderStatus(option: any): string {
    let statusProp = props.context?.attrs.statusProp ?? 'statusProp'
    if (!(option?.[statusProp])) return 'default'
    if (option[statusProp] !== 'warning' && option[statusProp] !== 'error') return 'default'
    return option[statusProp];
}


const classes = {
    container:
        "relative formkit-invalid:input-error formkit-errors:input-error mx-auto w-full flex items-center justify-end box-border cursor-pointer transition-all duration-300 bg-base-200 text-base-content leading-snug outline-none flex-shrink-1 px-4 text-base-content min-h-[3rem] leading-6 border border-transparent py-1 bg-base-100 rounded-btn",
    containerDisabled: "cursor-not-allowed  opacity-100",
    containerOpen: "rounded-b-none rounded-t-lg",
    containerOpenTop: "rounded-t-none rounded-b-lg",
    containerActive:
        "ring ring-primary ring-2 formkit-invalid:ring-error formkit-errors:ring-error ",
    singleLabel:
        "flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pr-16 box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5",
    singleLabelText:
        "overflow-ellipsis overflow-hidden block whitespace-nowrap max-w-full",
    multipleLabel:
        "flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5",
    search:
        "max-w-full  absolute inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base-content font-sans bg-base-200 rounded  rtl:pl-0 rtl:pr-3.5",
    tags: "flex-grow flex-shrink flex flex-wrap items-center mt-1 pl-2 rtl:pl-0 rtl:pr-2",
    tag: "bg-green-500 text-white text-sm font-semibold py-0.5 pl-2 rounded mr-1 mb-1 flex items-center whitespace-nowrap rtl:pl-0 rtl:pr-2 rtl:mr-0 rtl:ml-1",
    tagDisabled: "pr-2 opacity-50 rtl:pl-2",
    tagRemove:
        "flex items-center justify-center p-1 mx-0.5 rounded-sm hover:bg-black hover:bg-opacity-10 group",
    tagRemoveIcon:
        "bg-multiselect-remove bg-center bg-no-repeat opacity-30 inline-block w-3 h-3 group-hover:opacity-60",
    tagsSearchWrapper:
        "text-base-content inline-block relative mx-1 mb-1 flex-grow flex-shrink h-full",
    tagsSearch:
        "absolute bg-base-200 inset-0 border-0 outline-none focus:ring-0 appearance-none p-0 text-base-content font-sans box-border w-full",
    tagsSearchCopy: "invisible whitespace-pre-wrap inline-block h-px",
    placeholder:
        "flex items-center text-sm h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug text-base-content rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5",
    caret:
        "bg-multiselect-caret bg-center bg-no-repeat w-2.5 h-4 py-px box-content mr-1 relative z-10  flex-shrink-0 flex-grow-0 transition-transform transform pointer-events-none rtl:mr-0 rtl:ml-3.5",
    caretOpen: "rotate-180 pointer-events-auto",
    clear:
        "pr-3.5 relative z-10 ml-8 transition duration-300 flex-shrink-0 flex-grow-0 flex hover:opacity-80 rtl:pr-0 rtl:pl-3.5",
    clearIcon:
        "bg-multiselect-remove bg-center bg-no-repeat w-2.5 h-4 py-px box-content inline-block",
    spinner:
        "bg-multiselect-spinner bg-center bg-no-repeat w-4 h-4 z-10 mr-3.5 animate-spin flex-shrink-0 flex-grow-0 rtl:mr-0 rtl:ml-3.5",
    inifite: "flex items-center justify-center w-full",
    inifiteSpinner:
        "bg-multiselect-spinner bg-center bg-no-repeat w-4 h-4 z-10 animate-spin flex-shrink-0 flex-grow-0 m-3.5",
    dropdown:
        "max-h-60 absolute -left-px -right-px bottom-0 transform translate-y-full border border-base-300 -mt-px overflow-y-scroll z-50 bg-base-200 flex flex-col rounded-b",
    dropdownTop: "-translate-y-full top-px bottom-auto rounded-b-none rounded-t",
    dropdownHidden: "hidden",
    options: "flex flex-col p-0 m-0 list-none",
    optionsTop: "",
    group: "p-0 m-0",
    groupLabel:
        "flex text-sm box-border items-center justify-start text-left py-1 px-3 font-semibold bg-gray-200 cursor-default leading-normal",
    groupLabelPointable: "cursor-pointer",
    groupLabelPointed: "bg-gray-300 text-base-content",
    groupLabelSelected: "bg-green-600 text-white",
    groupLabelDisabled: "bg-gray-100 text-base-content cursor-not-allowed",
    groupLabelSelectedPointed: "bg-green-600 text-white opacity-90",
    groupLabelSelectedDisabled:
        "text-green-100 bg-green-600 bg-opacity-50 cursor-not-allowed",
    groupOptions: "p-0 m-0",
    option:
        "flex items-center justify-start box-border text-left cursor-pointer text-base-content leading-snug py-2 px-3",
    optionPointed: "text-base-content bg-base-100",
    optionSelected: "text-success-content bg-success",
    optionDisabled: "text-base-content cursor-not-allowed",
    optionSelectedPointed: "text-success-content bg-success opacity-90",
    optionSelectedDisabled:
        "text-success-content/80 bg-success bg-opacity-50 cursor-not-allowed",
    noOptions: "py-2 px-3 text-base-content bg-base-200 text-left",
    noResults: "py-2 px-3 text-base-content bg-base-200 text-left",
    fakeInput:
        "bg-transparent absolute left-0 right-0 -bottom-px w-full h-px border-0 p-0 appearance-none outline-none text-transparent",
    spacer: "h-9 py-px box-content",
};
</script>
<style>
.multiselect-wrapper {
    position: relative;
    margin-left: auto;
    margin-right: auto;
    box-sizing: border-box;
    display: flex;
    width: 100%;
    cursor: pointer;
    align-items: center;
    justify-content: flex-end;
    outline: 2px solid transparent;
    outline-offset: 2px;
}
.multiselect-assistive-text {
    position: absolute;
    margin: -1px;
    height: 1px;
    width: 1px;
    overflow: hidden;
    clip: rect(0 0 0 0)
}
</style>
