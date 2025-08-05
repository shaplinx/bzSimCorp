<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import { faEnvelope, faFolderTree, faInstitution } from '@fortawesome/free-solid-svg-icons';
import { ref } from 'vue'

const cardItems = ref([
  {
    icon: faInstitution,
    title: 'Total Institutions',
    total: '$3.456K',
    growthRate: 0.43
  },
  {
    icon: faFolderTree,
    title: 'Total Classifications',
    total: '$45,2K',
    growthRate: 4.35
  },
  {
    icon: faEnvelope,
    title: 'Total Letters',
    total: '2.450',
    growthRate: 2.59
  },
])
</script>

<template>
  <!-- Card Item Start -->
  <Card v-for="(item, index) in cardItems" :key="index" variant="base" class="shadow-default">
    <CardBody>
      <div class="flex  h-11.5 w-11.5 items-center justify-center rounded-full bg-neutral">
      <font-awesome-icon :icon="item.icon"></font-awesome-icon>

      </div>

      <div class="mt-4 flex items-end justify-between">
        <div>
          <h4 class="text-title-md font-bold text-base-content">{{ item.total }}</h4>
          <span class="text-sm font-medium">{{ item.title }}</span>
        </div>

        <span class="flex items-center gap-1 text-sm font-medium"
          :class="{ 'text-success': item.growthRate > 0, 'text-error': item.growthRate < 0 }">
          {{ item.growthRate }}%
          <svg v-if="item.growthRate > 0" class="fill-success" width="10" height="11" viewBox="0 0 10 11" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z"
              fill="" />
          </svg>

          <svg v-if="item.growthRate < 0" class="fill-error" width="10" height="11" viewBox="0 0 10 11" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M5.64284 7.69237L9.09102 4.33987L10 5.22362L5 10.0849L-8.98488e-07 5.22362L0.908973 4.33987L4.35716 7.69237L4.35716 0.0848701L5.64284 0.0848704L5.64284 7.69237Z"
              fill="" />
          </svg>
        </span>
      </div>
    </CardBody>
  </Card>
  <!-- Card Item End -->
</template>
