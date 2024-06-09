<template>
    <ckeditor :editor="classicEditor" :modelValue="modelValue" @update:modelValue="(val) => $emit('update:modelValue', val)">
    </ckeditor>
</template>
<script setup lang="ts">
import classicEditor from "@/services/ckeditor/ckeditor";
defineProps({
    modelValue: {
        type: String,
        default: ''
    }
})
</script>
<style>
:root {
    --ck-color-base-foreground: hsl(var(--bc));
    --ck-color-base-background: hsl(var(--b1));
    --ck-color-base-border: hsl(var(--bc));
    --ck-color-base-action: hsl(var(--su));
    --ck-color-base-focus: hsl(var(--b3));
    --ck-color-base-text: hsl(var(--bc));
    --ck-color-base-active: hsl(var(--p));
    --ck-color-base-active-focus: hsl(var(--pf));
    --ck-color-base-error: hsl(var(--er));

    /* -- Generic colors ------------------------------------------------------------------------ */

    --ck-color-focus-border-coordinates: var(--bc);
    --ck-color-focus-border: hsl(var(--ck-color-focus-border-coordinates));
    --ck-color-focus-outer-shadow: hsl(var(--b2));
    --ck-color-focus-disabled-shadow: hsla(var(--b3));
    --ck-color-focus-error-shadow: hsla(var(--erf));
    --ck-color-text: var(--ck-color-base-text);
    --ck-color-shadow-drop: hsla(var(--b3));
    --ck-color-shadow-drop-active: hsla(var(--b3));
    --ck-color-shadow-inner: hsla(var(--b3));

    /* -- Buttons ------------------------------------------------------------------------------- */

    --ck-color-button-default-background: transparent;
    --ck-color-button-default-hover-background: hsl(var(--b3));
    --ck-color-button-default-active-background: hsl(var(--b3));
    --ck-color-button-default-disabled-background: transparent;

    --ck-color-button-on-background: hsl(var(--p));
    --ck-color-button-on-hover-background: hsl(var(--pf));
    --ck-color-button-on-active-background: hsl(var(--p));
    --ck-color-button-on-disabled-background: hsl(var(--b1));
    --ck-color-button-on-color: hsl(var(--pc));


    --ck-color-button-action-background: var(--ck-color-base-action);
    --ck-color-button-action-hover-background: hsl(var(--su));
    --ck-color-button-action-active-background: hsl(var(--suc));
    --ck-color-button-action-disabled-background: transparent;
    --ck-color-button-action-text: var(--ck-color-base-background);

    --ck-color-button-save: hsl(var(--su));
    --ck-color-button-cancel: hsl(var(--er));

    --ck-color-switch-button-off-background: hsl(var(--b2));
    --ck-color-switch-button-off-hover-background: hsl(var(--b3));
    --ck-color-switch-button-on-background: var(--ck-color-button-action-background);
    --ck-color-switch-button-on-hover-background: hsl(var(--su));
    --ck-color-switch-button-inner-background: var(--ck-color-base-background);
    --ck-color-switch-button-inner-shadow: hsla(var(--b3));

    /* -- Dropdown ------------------------------------------------------------------------------ */

    --ck-color-dropdown-panel-background: var(--ck-color-base-background);
    --ck-color-dropdown-panel-border: var(--ck-color-base-border);

    /* -- Input --------------------------------------------------------------------------------- */

    --ck-color-input-background: var(--ck-color-base-background);
    --ck-color-input-border: var(--ck-color-base-border);
    --ck-color-input-error-border: var(--ck-color-base-error);
    --ck-color-input-text: var(--ck-color-base-text);
    --ck-color-input-disabled-background: hsl(var(--b2));
    --ck-color-input-disabled-border: var(--ck-color-base-border);
    --ck-color-input-disabled-text: hsl(var(--bc));

    /* -- List ---------------------------------------------------------------------------------- */

    --ck-color-list-background: var(--ck-color-base-background);
    --ck-color-list-button-hover-background: var(--ck-color-button-default-hover-background);
    --ck-color-list-button-on-background: var(--ck-color-button-on-color);
    --ck-color-list-button-on-background-focus: var(--ck-color-button-on-color);
    --ck-color-list-button-on-text: var(--ck-color-base-background);

    /* -- Panel --------------------------------------------------------------------------------- */

    --ck-color-panel-background: var(--ck-color-base-background);
    --ck-color-panel-border: var(--ck-color-base-border);

    /* -- Toolbar ------------------------------------------------------------------------------- */

    --ck-color-toolbar-background: var(--ck-color-base-background);
    --ck-color-toolbar-border: var(--ck-color-base-border);

    /* -- Tooltip ------------------------------------------------------------------------------- */

    --ck-color-tooltip-background: var(--ck-color-base-text);
    --ck-color-tooltip-text: var(--ck-color-base-background);

    /* -- Engine -------------------------------------------------------------------------------- */

    --ck-color-engine-placeholder-text: hsl(var(--bc));

    /* -- Upload -------------------------------------------------------------------------------- */

    --ck-color-upload-bar-background: hsl(var(--p));

    /* -- Link -------------------------------------------------------------------------------- */

    --ck-color-link-default: hsl(var(--p));
    --ck-color-link-selected-background: hsla(var(--pc));
    --ck-color-link-fake-selection: hsla(var(--pc));

    /* -- Search result highlight ---------------------------------------------------------------- */

    --ck-color-highlight-background: hsl(var(--wa));

    /* -- Generic colors ------------------------------------------------------------------------- */

    --ck-color-light-red: hsl(var(--er));
}
</style>
