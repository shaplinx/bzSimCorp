<template>
    <div class="navbar bg-base-100 align-middle justify-between border-b-2  border-primary">
        <!-- <BreadCrumbs class="ml-4 mr-auto" /> -->
        <ThemeSelector v-model="theme" class="ml-auto mr-2" />
        <DropdownMenu>
            <Button variant="ghost" square>
                <FontAwesomeIcon :icon="faEllipsis" />
            </Button>
            <template #popper>
                <li>
                    <a v-close-popper>Profile</a>
                </li>

                <li>
                    <a v-close-popper >Log Out</a>
                </li>
            </template>
        </DropdownMenu>

    </div>
</template>
<script setup lang="ts">
// import BreadCrumbs from "@/components/navs/BreadCrumbs.vue";
import { computed } from "vue";
import { ThemeName, usePreferencesStore } from "@/stores/preferences";
import ThemeSelector from "@/components/inputs/ThemeSelector.vue";
import Button from "../buttons/Button.vue";
import { faEllipsis } from "@fortawesome/free-solid-svg-icons";
import DropdownMenu from "../dropdowns/DropdownMenu.vue";


const preferences = usePreferencesStore();



const theme = computed({
    get() {
        return preferences.theme
    },
    set(val) {
        preferences.changeTheme(val as ThemeName)
    }
})



</script>
