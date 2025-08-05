<template>
    <!-- Static sidebar for desktop -->
    <div class="hidden lg:flex lg:flex-shrink-0" v-on-click-outside="onClickOutsideMenu" v-element-hover="onHover">
        <div class="flex w-20 flex-col">
            <div class="flex min-h-0 flex-1 flex-col overflow-hidden bg-neutral">
                <div class="flex items-center h-16  justify-center bg-primary ">
                    <img class="h-8" src="https://tailwindui.com/img/logos/mark.svg?color=white" alt="Your Company" />

                </div>
                <nav aria-label="Sidebar"
                    class="flex flex-1 overflow-x-hidden flex-col items-center space-y-3 overflow-y-auto py-6">
                    <template v-for="(item, key) in navigation">
                        <template v-if="item.child?.length">
                            <VMenu delay="0" placement="right"
                                :distance="13" :positioning-disabled="true" skipTransition theme="sidebar">
                                <DesktopNavItem :item="item" @change:is-active="(val) => onChildActive(val, key)"
                                    @click="setActiveParent(key)"></DesktopNavItem>
                                <template #popper>
                                    <div class="h-screen w-52 bg-base-100 flex flex-col">
                                        <p
                                            class="text-xs font-thin text-base-content font-satoshi px-4 py-2 text-muted">
                                            {{ item.label }} </p>
                                        <TreeNav class="overflow-x-hidden overflow-y-auto  w-full"
                                            :navigation="item.child">
                                        </TreeNav>
                                    </div>
                                </template>
                            </VMenu>
                        </template>
                        <template v-else>
                            <DesktopNavItem :item="item" @change:is-active="(val) => onChildActive(val, key)"
                                @click="setActiveParent(key)"></DesktopNavItem>
                        </template>


                    </template>
                </nav>
            </div>
        </div>
        <aside class="overflow-hidden transition-all transform bg-base-100 z-10 absolute top-0 left-20 h-full">
        </aside>
    </div>
</template>
<script setup lang="ts">
import { Transition, ref, watch } from 'vue';
import TreeNav from './TreeNav.vue';
import DesktopNavItem from './DesktopNavItem.vue';
import { vOnClickOutside } from '@vueuse/components';
import { vElementHover } from '@vueuse/components'

defineProps<{
    navigation: Base.Component.Menu.MenuItem[],
}>()


const activeStates = ref<boolean[]>([])
const lastClicked = ref<number | null>(null)
const enableTransition = ref(true);
const activeParent = ref<number | undefined>(undefined)

function setActiveParent(key?: number) {
    activeParent.value = key
}

function onChildActive(val: boolean, key: number) {
    activeStates.value[key] = val;
    if (val) lastClicked.value = key
}

function onClickOutsideMenu() {
    lastClicked.value = null;
}

function onHover(value: boolean) {
    if (!value) { lastClicked.value = null; }
}


</script>
<style>
.v-popper__popper--no-positioning {
    position: absolute;
    z-index: 0;
    top: 0;
    left: 5rem;
    height: 100vh;
}

.v-popper--theme-sidebar .v-popper__wrapper {
    max-height: 100vh;
    overflow-y: hidden;
    box-shadow: none;
}

.v-popper--theme-sidebar .v-popper__inner {
    padding: 0px;
    border-radius: 0px;
    border: none;
    border-right: 1px solid oklch(var(--b3));
    box-shadow: none !important;
}

/* Transition */

.v-popper--theme-sidebar.v-popper__popper--hidden {
    visibility: hidden;
    opacity: 0;
    transition: opacity .15s, visibility .15s;
}

.v-popper--theme-sidebar.v-popper__popper--shown {
    visibility: visible;
    opacity: 1;
    transition: opacity .15s;
}

.v-popper--theme-sidebar.v-popper__popper--skip-transition {
    transition: none !important;
}
</style>
