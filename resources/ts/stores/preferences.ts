import { defineStore } from "pinia";
import { useStorage } from '@vueuse/core'
import { ref } from 'vue'
import {useStylesStore} from "@/stores/styles"


export type ThemeName =
    | "light"
    | "dark"
    | "cupcake"
    | "bumblebee"
    | "emerald"
    | "corporate"
    | "synthwave"
    | "retro"
    | "cyberpunk"
    | "valentine"
    | "halloween"
    | "garden"
    | "forest"
    | "aqua"
    | "lofi"
    | "pastel"
    | "fantasy"
    | "wireframe"
    | "black"
    | "luxury"
    | "dracula"
    | "cmyk"
    | "autumn"
    | "business"
    | "acid"
    | "lemonade"
    | "night"
    | "coffee"
    | "winter";

export type MenuCompact = boolean;





export const usePreferencesStore = defineStore('preferences', () => {
    const menuCompact = useStorage('menuCompact', ref(true));
    const theme = useStorage<ThemeName>('theme', ref<ThemeName>("light"));
    const mobileSidebarShown = ref(false);
    const {refreshStyles} = useStylesStore()

    function toggleMenu(state?: boolean) {
        if (typeof state === "boolean") {
            menuCompact.value = state;
        } else {
            menuCompact.value = !menuCompact.value;
        }
    }

    function toggleMobileSidebar(state?: boolean) {
        if (typeof state === "boolean") {
            mobileSidebarShown.value = state
        } else {
            mobileSidebarShown.value = !mobileSidebarShown.value
        }

    }

    function changeTheme(themeName: ThemeName) {

        theme.value = themeName;
        document.documentElement.setAttribute("data-theme", themeName);
        refreshStyles()
    }

    changeTheme(theme.value)

    return {
        mobileSidebarShown,
        toggleMenu,
        toggleMobileSidebar,
        changeTheme,
        menuCompact,
        theme
    }
});

