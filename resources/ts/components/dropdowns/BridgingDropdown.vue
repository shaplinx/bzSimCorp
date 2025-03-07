<template>
    <DropdownMenuVue placement="auto-start">
        <ButtonVue variant="primary" block>
            <div class="align-middle items-center flex gap-1 py-1 px-1">
                <div class="avatar-group -space-x-3 rtl:space-x-reverse">
                    <div class="avatar bg-base-100 border-primary p-1 border-2">
                        <BpjsSvg class="h-3 w-3"></BpjsSvg>
                    </div>
                    <div class="avatar bg-base-100  border-primary p-1 border-2">
                        <SatuSehatSvg class="h-3 w-3"></SatuSehatSvg>
                    </div>
                </div>
                <p class="hidden md:block">{{t("menu.bridging")}}</p>
            </div>
        </ButtonVue>
        <template #popper="{ hide }">
            <TreeNav :navigation="navs"></TreeNav>
        </template>
    </DropdownMenuVue>
</template>
<script setup lang="ts">
import ButtonVue from '../buttons/Button.vue';
import TreeNav from '../navs/TreeNav.vue';
import DropdownMenuVue from "@/components/dropdowns/DropdownMenu.vue";
import BpjsSvg from '../badges/BpjsSvg.vue';
import SatuSehatSvg from '../badges/SatuSehatSvg.vue';
import { useI18n } from 'vue-i18n';

const {t} = useI18n();

const { log} =console;

const navs : Base.Component.Menu.MenuItem[] = [
    {
        label: t("menu.satu-sehat"),
        child:[
            {
                label:t("menu.bridging"),
                to: {name:"SatuSehat"},
            },
            {
                label:t("menu.logs"),
                to: {name:"SatuSehatLogs"}
            }
        ]
    },

]
</script>
