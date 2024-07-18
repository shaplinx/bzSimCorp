<template>
    <div class="buttons-pagination">
      <div
        v-for="(item, i) in paginationItemsForRender"
        :key="i"
        class="item"
        :class="{
          button: item.type === 'button',
          active: item.type === 'button' && item.active,
          'active-prev': item.type === 'button' && item.activePrev,
          omission: item.type === 'omission',
        }"
        @click="changePage(item)"
      >
        {{ item.type === 'button' ? item.page : '...' }}
      </div>
    </div>
  </template>

  <script lang="ts" setup>
  import { computed, inject } from 'vue';

  const emits = defineEmits(['updatePage']);

  const props = defineProps({
    maxPaginationNumber: { type: Number, required: true },
    currentPaginationNumber: { type: Number, required: true },
  });

  const totalVisible = 7;

  type PaginationItem = {
    type: 'button',
    page: number,
    active: boolean,
    activePrev: boolean,
  } | {
    type: 'omission',
  };

  const changePage = (item: PaginationItem) => {
    if (item.type === 'button' && !item.active) emits('updatePage', item.page);
  };

  const paginationItemsForRender = computed((): PaginationItem[] => {
    const paginationItems: PaginationItem[] = [];
    if (props.maxPaginationNumber <= totalVisible) {
      // x,x,x,x
      for (let i = 1; i <= props.maxPaginationNumber; i += 1) {
        paginationItems.push({
          type: 'button',
          page: i,
          active: i === props.currentPaginationNumber,
          activePrev: (i + 1) === props.currentPaginationNumber,
        });
      }
    } else if ([1, 2, props.maxPaginationNumber, props.maxPaginationNumber - 1].includes(props.currentPaginationNumber)) {
      // x,x,x,...,x,x,x
      for (let i = 1; i <= totalVisible; i += 1) {
        if (i <= 3) {
          paginationItems.push({
            type: 'button',
            page: i,
            active: i === props.currentPaginationNumber,
            activePrev: (i + 1) === props.currentPaginationNumber,
          });
        } else if (i === 4) {
          paginationItems.push({
            type: 'omission',
          });
        } else {
          const page = props.maxPaginationNumber - (totalVisible - i);
          paginationItems.push({
            type: 'button',
            page,
            active: page === props.currentPaginationNumber,
            activePrev: (page + 1) === props.currentPaginationNumber,
          });
        }
      }
    } else if ([3, 4].includes(props.currentPaginationNumber)) {
      // x,x,x,x,x,...,x
      for (let i = 1; i <= totalVisible; i += 1) {
        if (i <= 5) {
          paginationItems.push({
            type: 'button',
            page: i,
            active: i === props.currentPaginationNumber,
            activePrev: (i + 1) === props.currentPaginationNumber,
          });
        } else if (i === 6) {
          paginationItems.push({
            type: 'omission',
          });
        } else {
          paginationItems.push({
            type: 'button',
            page: props.maxPaginationNumber,
            active: props.maxPaginationNumber === props.currentPaginationNumber,
            activePrev: (i + 1) === props.currentPaginationNumber,
          });
        }
      }
    } else if ([props.maxPaginationNumber - 2, props.maxPaginationNumber - 3].includes(props.currentPaginationNumber)) {
      // x,...,x,x,x,x,x
      for (let i = 1; i <= totalVisible; i += 1) {
        if (i === 1) {
          paginationItems.push({
            type: 'button',
            page: 1,
            active: props.currentPaginationNumber === 1,
            activePrev: (i + 1) === props.currentPaginationNumber,
          });
        } else if (i === 2) {
          paginationItems.push({
            type: 'omission',
          });
        } else {
          const page = props.maxPaginationNumber - (totalVisible - i);
          paginationItems.push({
            type: 'button',
            page,
            active: page === props.currentPaginationNumber,
            activePrev: (page + 1) === props.currentPaginationNumber,
          });
        }
      }
    } else {
      // x,...,x,x,x,...,x
      for (let i = 1; i <= totalVisible; i += 1) {
        if (i === 1) {
          paginationItems.push({
            type: 'button',
            page: 1,
            active: props.currentPaginationNumber === 1,
            activePrev: (i + 1) === props.currentPaginationNumber,
          });
        } else if (i === 2 || i === 6) {
          paginationItems.push({
            type: 'omission',
          });
        } else if (i === 7) {
          paginationItems.push({
            type: 'button',
            page: props.maxPaginationNumber,
            active: props.maxPaginationNumber === props.currentPaginationNumber,
            activePrev: (i + 1) === props.currentPaginationNumber,
          });
        } else {
          const diff = 4 - i;
          const page = props.currentPaginationNumber - diff;
          paginationItems.push({
            type: 'button',
            page,
            active: page === props.currentPaginationNumber,
            activePrev: (page + 1) === props.currentPaginationNumber,
          });
        }
      }
    }
    return paginationItems;
  });

</script>
