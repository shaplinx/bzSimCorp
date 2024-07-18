<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import formShowcase from "@/forms/schemas/ShowcaseSchema";
import { ref } from 'vue';

const data = ref({});
const schema =[
  {
    $formkit: 'text',
    name: 'name',
    label: 'Full Name',
    placeholder: 'Enter your name...'
  },
  {
    $formkit: 'checkbox',
    name: 'hasEmail',
    label: 'Has Email?',
  },

]
</script>

<template>
    <Card variant="base">
        <CardBody>
            <CardTitle>
                Forms Showcase
            </CardTitle>
            <FormKit type="form" v-model="data" >
                <FormKitSchema :schema="formShowcase" :data="data" />
            </FormKit>
        </CardBody>
    </Card>
</template>
