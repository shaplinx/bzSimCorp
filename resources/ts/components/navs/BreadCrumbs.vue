<template>
  <div class="grid grid-cols-1  gap-1">
    <div class="text-sm breadcrumbs">
      <ul>
        <li @click="() => router.push(HomePage)" class="text-sm">
          <a>
            <FontAwesomeIcon :icon="faHardDrive" class="mr-1" />
          </a>

        </li>
        <li v-for="(crumb, key) in breadcrumbs" @click="breadcrumbClick(key)">
          <a>
            <FontAwesomeIcon :icon="key == breadcrumbs.length - 1 ? faFile : faFolderOpen"
              class="mr-1 text-sm" />
            {{ +crumb ? crumb : t(`menu.${crumb}`) }}
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { HomePage } from "@/router/index"
import {faFile, faFolderOpen, faHardDrive} from "@fortawesome/free-regular-svg-icons"


const { t } = useI18n();

const route = useRoute();
const router = useRouter();

const breadcrumbs = computed(() => {
  let bread = route.fullPath.split('/');
  bread.splice(0, 2);
  if (+bread[bread.length - 1]) {
    bread.pop()
  }
  return bread;
})

function generateURL(key: number) {
  let str = import.meta.env.VITE_ADMIN_ROOT ?? '/admin'
  for (let index = 0; index <= key; index++) {
    str = str + '/' + breadcrumbs.value[index]
  }
  return str
}

function breadcrumbClick(key: number) {
  if (key != breadcrumbs.value.length - 1) {
    router.push(generateURL(key))
  }
}
</script>
