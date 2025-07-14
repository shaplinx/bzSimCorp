<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import { useClassificationFormSchema } from '@/forms/schemas/documents/classificationFormSchema';
import { useClassificationResources } from '@/resources';
import { useCreateCrud } from '@/@hooks/crud/useCreateCrud';
import CardActions from '@/components/cards/CardActions.vue';
import { FormKit, FormKitSchema } from '@formkit/vue';

const formSchema = useClassificationFormSchema({ route: 'create' });

const {
  editRoute,
  router,
  reactives,
  formButtons,
  createSubmit,
} = useCreateCrud({
  formId: 'CreateClassificationForm',
  resources: useClassificationResources(),
  indexRoute: { name: 'IndexClassification' },
  editRoute: { name: 'EditClassification' },
  onCreateSuccess: (res) => {
    router.push({ ...editRoute, params: { id: res.data.data?.id } });
  },
  proccessCreateData: (data) => {
    return Object.assign({}, data, {
      parent_id: data.parent?.id
    })
  }
});
</script>

<template>
  <Card variant="base">
    <CardBody>
      <CardTitle>{{ $route.meta.title }}</CardTitle>
      <FormKit :disabled="reactives.isSubmitting" type="form" @submit="createSubmit" :actions="false"
        id="CreateClassificationForm">
        <FormKitSchema :schema="formSchema" />
      </FormKit>
      <CardActions>
        <FormKit type="button" v-for="(button, key) in formButtons" :key="key" v-bind="button" v-on="button.on"
          :loading="reactives.isSubmitting" />
      </CardActions>
    </CardBody>
  </Card>
</template>
