<script setup lang="ts">
import ChartOne from '@/components/charts/Documents/ChartOne.vue'
import ChartThree from '@/components/charts/Documents/ChartThree.vue'
import DocumentsCount from '@/components/stats/Documents/DocumentsCount.vue'
//import TableOne from '@/components2/Tables/TableOne.vue'
</script>

<template>
    <div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-3 2xl:gap-7.5">
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
