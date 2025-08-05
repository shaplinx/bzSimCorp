<script setup lang="ts">
import Button from '@/components/buttons/Button.vue';
import Card from '@/components/cards/Card.vue';
import CardActions from '@/components/cards/CardActions.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import { useRoute } from 'vue-router';
const route = useRoute()

</script>

<template>
  <div class="grid min-h-screen place-items-center px-6 py-24 sm:py-32 lg:px-8 text-center">
    <Card variant="base">
    <CardBody>
        <p class="font-semibold text-primary text-center w-full">{{ route.meta.code || "Undefined Code"}}</p>
        <CardTitle class="text-5xl text-center w-full">
          <p>{{ route.meta.title || "Generic Error Has Occured" }}</p>
        </CardTitle>
          <p class="mt-6 text-lg font-medium text-pretty sm:text-xl/8 text-center w-full">{{ route.meta.message || "Sorry, something went wrong" }}</p>

        <CardActions class="mt-10 flex items-center justify-center gap-x-6">
          <Button variant="primary" @click = "() => $router.push({name:'Dashboard'})">Homepage</Button>
        </CardActions>

      </CardBody>
    </Card>
  </div>
</template>