<template>
    <Card variant="base" class="shadow-md">
        <BaseTable v-model:selected="selected" :data="data" :fields="fields" striped hover select-mode="single">
        </BaseTable>
    </Card>
</template>
<script setup lang="ts">
import Card from "@/components/cards/Card.vue";
import BaseTable from "@/components/tables/Table.vue"
import {ref} from "vue"
const fields = [
    {
        label: "ID",
        value: "id"
    },
    {
        label: "name",
        value: "name"
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

const selected = ref([])
</script>
