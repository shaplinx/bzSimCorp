<template>
    <Card variant="base">
        <Table
            v-bind="props"
            :data="dataForRender"
            @update:sort="(val) => $emit('update:sort',val)"
            @update:selected="(val) => $emit('update:selected',val)"
        >
            <template v-for="field in fields" #[`header-${field.value}`]="{ item }">
                <span v-if="!hasNamedSlot(`header-${field.value}`)" :item="item">
                    {{ item.label }}
                </span>
                <slot v-else :name="`header-${field.value}`" :item="item" />
            </template>
            <template v-for="field in fields" #[`item-${field.value}`]="{ item }">
                <span v-if="!hasNamedSlot(`item-${field.value}`)" :item="item">
                    {{ item[field.label] }}
                </span>
                <slot v-else :name="`item-${field.value}`" :item="item" />
            </template>
        </Table>
        <div class="flex items-center w-full justify-between px-3">
            <div class="label">
    <span class="label-text">Pick the best fantasy franchise</span>
    <span class="label-text-alt">Alt label</span>
  </div>
            <select v-model="pagination.per_page" class="select select-bordered select-sm w-full max-w-20">
                <option v-for="(page,i) in [10,15,20,25]" :value="page" :selected="i=== 0"> {{ page }}</option>
            </select>
        <Pagination v-if="pagination.page && pagination.per_page && pagination.total"
            class="my-4"
            buttonSize="sm"
            v-model:page="pagination.page"
            v-model:per_page="pagination.per_page"
            v-model:total="pagination.total"
            @update:page="(val) => $emit('update:pagination', pagination)"
            @update:per_page="(val) => $emit('update:pagination', pagination)"
            @update:total="(val) => $emit('update:pagination', pagination)"
            position="right"
            ></Pagination>
        </div>
    </Card>
</template>
<script setup lang="ts">
import Table from './Table.vue';
import { TableProps } from "../@types/table"
import { computed, getCurrentInstance, reactive, ref } from 'vue';
import Card from '../cards/Card.vue';
import Pagination from '../buttons/Pagination.vue';
import { watchDeep } from '@vueuse/core';

const props = defineProps<TableProps & {
    pagination?: {
        page: number,
        per_page: number,
        total: number,
    }

}>()

const instance = getCurrentInstance();

function hasNamedSlot(slotName: any) {
    return Object.prototype.hasOwnProperty.call(instance?.slots, slotName);
}

const pagination = reactive({
    page: props.pagination?.page,
    per_page: props.pagination?.per_page,
    total: props.pagination?.total
})
watchDeep(() => props.pagination, (val) => {
    pagination.page= val?.page,
    pagination.per_page= val?.per_page,
    pagination.total= val?.total
})


const dataForRender = computed(()=> {
    if (props.serverSide) return props.data
    if(!(pagination.page && pagination.per_page && pagination.total)) return props.data
    const totalPages = Math.ceil(pagination.total / pagination.per_page);

    if (pagination.page < 1) {
        pagination.page = 1;
    } else if (pagination.page > totalPages) {
        pagination.page = totalPages;
    }

    const startIndex = (pagination.page - 1) * pagination.per_page;
    const endIndex = Math.min(startIndex + pagination.per_page, pagination.total);
    return props.data.slice(startIndex, endIndex);
})



</script>
