<template>
  <div>
    <component :is="element" :key="item.id" :to="item.to">
      <div class="flex flex-col items-center align-middle">
        <div class="btn"  :class="[isActive ? 'btn-primary' : '']">
          <FontAwesomeIcon :icon="item.icon" aria-hidden="true" class="w-5">
          </FontAwesomeIcon>
          <span class="sr-only">{{ item.label }}</span>
        </div>
      </div>
    </component>
  </div>
</template>

<script setup lang="ts">
import AppLink from "./AppLink.vue";
import { useMenuItem } from "./hooks/navigation";
import { watch, ref, computed } from "vue";

const props = defineProps<{
  item: Base.Component.Menu.MenuItem;
}>();

const element = computed(() => (props.item.to ? AppLink : "div"));

const emits = defineEmits(["change:isActive"]);

const { isActive } = useMenuItem(props.item);

watch(
  () => isActive.value,
  (val) => {
    emits("change:isActive", val);
  },
  {
    immediate: true,
  }
);
</script>
