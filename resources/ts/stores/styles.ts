import { defineStore } from "pinia";
import { ref, computed } from 'vue'



export const useStylesStore = defineStore('styles', () => {
    const styles = ref(getComputedStyle(document.body));
    const colors = computed(() => {
        return {
            primary: `oklch(${styles.value.getPropertyValue("--p")})`,
            primaryFocus: `oklch(${styles.value.getPropertyValue("--pf")})`,
            primaryContent: `oklch(${styles.value.getPropertyValue("--pc")})`,
            secondary: `oklch(${styles.value.getPropertyValue("--s")})`,
            secondaryFocus: `oklch(${styles.value.getPropertyValue("--sf")})`,
            secondaryContent: `oklch(${styles.value.getPropertyValue("--sc")})`,
            accent: `oklch(${styles.value.getPropertyValue("--a")})`,
            accentFocus: `oklch(${styles.value.getPropertyValue("--af")})`,
            accentContent: `oklch(${styles.value.getPropertyValue("--ac")})`,
            neutral: `oklch(${styles.value.getPropertyValue("--n")})`,
            neutralFocus: `oklch(${styles.value.getPropertyValue("--nf")})`,
            neutralContent: `oklch(${styles.value.getPropertyValue("--nc")})`,
            base100: `oklch(${styles.value.getPropertyValue("--b1")})`,
            base200: `oklch(${styles.value.getPropertyValue("--b2")})`,
            base300: `oklch(${styles.value.getPropertyValue("--b3")})`,
            baseContent: `oklch(${styles.value.getPropertyValue("--bc")})`,
            info: `oklch(${styles.value.getPropertyValue("--in")})`,
            infoContent: `oklch(${styles.value.getPropertyValue("--inc")})`,
            success: `oklch(${styles.value.getPropertyValue("--su")})`,
            successFocus: `oklch(${styles.value.getPropertyValue("--suf")})`,
            successContent: `oklch(${styles.value.getPropertyValue("--suc")})`,
            warning: `oklch(${styles.value.getPropertyValue("--wa")})`,
            warningContent: `oklch(${styles.value.getPropertyValue("--wac")})`,
            error: `oklch(${styles.value.getPropertyValue("--er")})`,
            errorContent: `oklch(${styles.value.getPropertyValue("--erc")})`
        }
    })

    function refreshStyles() {
        styles.value = getComputedStyle(document.body)

    }

    return {
        colors,
        refreshStyles
    }

});

