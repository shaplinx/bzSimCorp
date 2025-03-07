<template>
    <div class="grid grid-cols-1  gap-1">
        <div class="text-sm breadcrumbs">
            <ul>
                <router-link v-for="(bread, key) in breadcrumbs" :to="bread.to" custom v-slot="{ navigate, href }">
                    <li @click="navigate" class="text-sm" v-if="!key">
                        <a>
                            <FontAwesomeIcon :icon="faHardDrive" class="mr-1" />
                        </a>
                    </li >
                    <li v-else @click="navigate" :href="href" class="text-sm">
                         <FontAwesomeIcon :icon="key == breadcrumbs.length - 1 ? faFile : faFolderOpen" class="mr-1 text-sm"></FontAwesomeIcon>
                        <a>{{ bread.label }}</a>
                    </li>
                </router-link>
            </ul>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { computed } from 'vue';
import { faFile, faFolderOpen, faHardDrive } from "@fortawesome/free-regular-svg-icons"


const route = useRoute();
const breadcrumbs = computed(() => {
    const matchedRoutes = route.matched;

    return matchedRoutes.map((routeItem) => ({
        label: routeItem.meta.title || routeItem.name,
        to: routeItem,
    }));
})




</script>
