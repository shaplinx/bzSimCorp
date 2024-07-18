<template>
        <DataTable
            v-model:pagination="pagination"
            class="shadow-md"
            v-model:selected="selected"
            :data="data"
            :fields="fields"
            select-mode="multiple" striped hover>
        <template #header-id="{item}"> {{ item.label }} </template>
        <template #item-id="{item}"> {{ item.id }} </template>

        </DataTable>
        {{ selected }}
</template>
<script setup lang="ts">
import { TableField } from "@/components/@types/table";

import DataTable from "@/components/tables/DataTable.vue";
import {ref} from "vue";
const fields : TableField[] = [
    {
        label: "ID",
        value: "id",
        sortable:true,
        align:"center",
        sortValue:"id",
        unique:true
    },
    {
        label: "name",
        value: "name",
    },
]

const data = [
    {
        id: "1",
        name: "Reza"
    },
    {
        id: "2",
        name: "irfan"
    },
    {
        id: "3",
        name: "raditya"
    },
]

const selected = ref([{
        id: "1",
        name: "Reza"
    },])

const pagination = ref({
    total:3,
    per_page:10,
    page:1
})
</script>
