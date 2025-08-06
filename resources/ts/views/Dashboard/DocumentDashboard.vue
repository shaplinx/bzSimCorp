<script setup lang="ts">
import { ButtonProps } from "@/components/@types/button";
import ChartOne from "@/components/charts/Documents/ChartOne.vue";
import ChartThree from "@/components/charts/Documents/ChartThree.vue";
import IndexCrudTitle from "@/components/containers/IndexCrudTitle.vue";
import DocumentsCount from "@/components/stats/Documents/DocumentsCount.vue";
import { useRouter } from "vue-router";
//import TableOne from '@/components2/Tables/TableOne.vue'
const router = useRouter();

const titleButtons: ButtonProps[] = [
  {
    label: "Institutions",
    on: {
      click: () => router.push({ name: "IndexInstitution" }),
    },
  },
  {
    label: "Classifications",
    on: {
      click: () => router.push({ name: "IndexClassification" }),
    },
  },
  {
    label: "Letters",
    on: {
      click: () => router.push({ name: "IndexLetter" }),
    },
  },
];
</script>

<template>
  <div>
    <IndexCrudTitle :buttons="titleButtons"></IndexCrudTitle>

    <div
      class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-3 2xl:gap-7.5"
    >
      <DocumentsCount></DocumentsCount>
    </div>

    <div class="mt-4 grid grid-cols-12 gap-4 md:mt-6 md:gap-6 2xl:mt-7.5 2xl:gap-7.5">
      <!-- ====== Chart One Start -->
      <ChartOne />
      <!-- ====== Chart One End -->

      <!-- ====== Chart Three Start -->
      <ChartThree />
      <!-- ====== Chart Three End -->
    </div>
  </div>
</template>
