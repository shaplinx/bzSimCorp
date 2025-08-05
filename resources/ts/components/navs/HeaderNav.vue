<template>
    <div class="navbar bg-base-100 align-middle justify-between border-b-2 gap-2 border-primary">
        <BreadCrumbs class="mr-auto ml-2"/>
        <ThemeSelector v-model="theme"/>
        <DropdownMenu placement="bottom-end">
            <Button variant="ghost" square>
                <FontAwesomeIcon :icon="faEllipsis" />
            </Button>
            <template #popper>
                <li>
                    <a v-close-popper @click="() => $router.push({name:'EditUser', params:{id:auth.user?.id}})">Profile</a>
                </li>

                <li>
                    <a v-close-popper @click="onLogout">Log Out</a>
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
import { useAuthStore } from '@/stores/authStore';
import { useRouter } from "vue-router";
import BreadCrumbs from "./BreadCrumbs.vue";


const auth = useAuthStore()
const router = useRouter()


function onLogout() {
    auth.logout().then(()=> router.push({name:"SignIn"}))
}


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
