<template>
  <div>
    <input
      type="text"
      id="search"
      @update:value="onSearchChange"
      :value="modelValue"
      :placeholder="searchPlaceholder"
      autocomplete="off"
      @input="(e)=>onSearchChange((e.target as HTMLInputElement).value)"
      :class="classes.input"
      @keyup.enter.prevent="
        selectItem(option[hovered][valueKey], option[hovered])
      "
      @keydown.up="onHover(hovered - 1)"
      @keydown.down="onHover(hovered + 1)"
      @keydown.esc.prevent="setOpenOptions(false)"
    />
    <ul v-if="openOptions && option.length" :class="classes.options">
      <SpinnerOverlay v-show="isLoading" />
      <li
        v-for="(item, key) in option"
        @mouseover.prevent="onHover(key)"
        @click="selectItem(item[valueKey], item)"
        :class="[
          key === hovered ? 'bg-primary text-primary-content' : '',
          classes.option,
        ]"
      >
        <span>{{ item[labelKey] }}</span>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import {debounce} from "@/@hooks/misc/debounce"
import { ref, defineProps, onUnmounted } from "vue";
import SpinnerOverlay from "../loader/SpinnerOverlay.vue";

const props = defineProps([
  "items",
  "labelKey",
  "valueKey",
  "searchPlaceholder",
  "classes",
  "modelValue",
  "debounce"
]);
const emits = defineEmits(["update:modelValue", "selected", "search"]);

const isLoading = ref(false);

const loading = (bool: boolean) => {
  isLoading.value = bool;
};

const option = ref<any[]>([]);

const setOptions = (options: any[]) => {
  option.value = options;
};

const openOptions = ref(false);

const setOpenOptions = (bool: boolean) => {
  openOptions.value = bool;
};

const hovered = ref(0);

const onHover = (key: number) => {
  if (key < 0 || key >= option.value.length) return;
  hovered.value = key;
};

const selectItem = (value: string, item: any) => {
  emits("update:modelValue", value, item);
  setOpenOptions(false);
  emits("selected", value, item);
};

const onSearchChange = debounce((search: string) => {
  emits("update:modelValue", search);
  emits("search", search, loading, setOptions);
  setOpenOptions(true);
}, props.debounce);
document.addEventListener("click", () => setOpenOptions(false));

onUnmounted(() => {
  document.removeEventListener("click", () => setOpenOptions(false));
});

</script>
