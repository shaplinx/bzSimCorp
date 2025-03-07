<template>
  <VDropdown v-bind="attrs"  >
    <slot> </slot>

    <template #popper="{ hide }" >
      <DropdownMenuContent class="gap-1">
        <slot name="popper" :option="props.options" :hide="hide">
          <li
            @click="()=> onMenuClick(option,hide)"
            v-for="option in props.options"
            :class="[
                props.activeKey === option.value && activeClass ? activeClass : '',
                {'disabled' : option.disabled}
                ]"
          >
            <a
              ><FontAwesomeIcon v-if="option.icon" :icon="option.icon"></FontAwesomeIcon
              >{{ option[props.labelkey] }}</a
            >
          </li></slot
        >
      </DropdownMenuContent>
    </template>
  </VDropdown>
</template>

<script setup lang="ts">
import { DropdownOption } from "./dropdown";
import DropdownMenuContent from "./DropdownMenuContent.vue";
const props = withDefaults(
  defineProps<{
    options?: DropdownOption[];
    label?: string;
    labelkey?: string;
    id?: any
    activeKey?:any;
    activeClass?:string;
    attrs?: any;
  }>(),
  { labelkey: "label" }
);

function onMenuClick(option : DropdownOption, hide : any ) {
    if (option.disabled) return
    option.action?.(props.id)
    hide()
}
</script>


<style scoped>
.v-popper--theme-dropdown .v-popper__inner {
    background: oklch(var(--b1));
    border: 1px solid oklch(var(--b))

}
.v-popper--theme-dropdown .v-popper__arrow-inner {

    border-color: oklch(var(--b1));
    opacity: 0;
}
.v-popper--theme-dropdown .v-popper__arrow-outer {
    border-color: oklch(var(--b2));
    opacity: 0;

}
</style>
