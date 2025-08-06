<script setup lang="ts">
import DataStatsOne from "@/components/stats/DataStatsOne.vue";
import ChartOne from "@/components/charts/ChartOne.vue";
import ChartThree from "@/components/charts/ChartThree.vue";
import ChartTwo from "@/components/charts/ChartTwo.vue";
//import ChatCard from '@/components2/ChatCard.vue'
import MapOne from "@/components/maps/MapOne.vue";
import IndexCrudTitle from "@/components/containers/IndexCrudTitle.vue";
//import TableOne from '@/components2/Tables/TableOne.vue'
</script>

<template>
  <div>
    <IndexCrudTitle></IndexCrudTitle>
    <div
      class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5"
    >
      <DataStatsOne />
    </div>

    <div class="mt-4 grid grid-cols-12 gap-4 md:mt-6 md:gap-6 2xl:mt-7.5 2xl:gap-7.5">
      <!-- ====== Chart One Start -->
      <ChartOne />
      <!-- ====== Chart One End -->

      <!-- ====== Chart Two Start -->
      <ChartTwo />
      <!-- ====== Chart Two End -->

      <!-- ====== Chart Three Start -->
      <ChartThree />
      <!-- ====== Chart Three End -->

      <!-- ====== Map One Start -->
      <MapOne />
      <!-- ====== Map One End -->

      <!-- ====== Table One Start
            <div class="col-span-12 xl:col-span-8">
                <TableOne />
            </div>
            ====== Table One End -->

      <!-- ====== Chat Card Start
            <ChatCard />
             ====== Chat Card End -->
    </div>
  </div>
</template>
