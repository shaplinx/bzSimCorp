<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import { useClassificationFormSchema } from '@/forms/schemas/documents/classificationFormSchema';
import { useClassificationResources } from '@/resources';
import SpinnerOverlay from '@/components/loader/SpinnerOverlay.vue';
import CardActions from '@/components/cards/CardActions.vue';
import { FormKit, FormKitSchema } from '@formkit/vue';
import { useEditCrud } from '@/@hooks/crud/useEditCrud';

const formSchema = useClassificationFormSchema({ route: 'edit' });

const {
    reactives,
    formButtons,
    updateSubmit,
    fetchOne
} = useEditCrud<App.Models.DocumentsClassification>({
    resources: useClassificationResources(),
    indexRoute: { name: 'IndexClassification' },
    formId: 'EditClassificationForm',
    proccessUpdateData: (data) => {
        return Object.assign({}, data, {
            parent_id: data.parent?.id
        })
    },
    generateDeleteObject: (data) => {
        return {
            id: String(data.id),
            name: data.name,
            code: data.code,
            classification_separator: data.classification_separator,
            parent_id: String(data.parent_id) ?? "null",
        };
    },

});

fetchOne();
</script>

<template>
    <Card variant="base">
        <SpinnerOverlay :show="reactives.isFetching" />
        <CardBody>
            <CardTitle>{{ $route.meta.title }}</CardTitle>
            <FormKit :disabled="reactives.isSubmitting" type="form" @submit="updateSubmit" :actions="false"
                id="EditClassificationForm">
                <FormKitSchema :schema="formSchema" />
            </FormKit>
            <CardActions>
                <FormKit type="button" v-for="(button, key) in formButtons" :key="key" v-bind="button" v-on="button.on"
                    :class="{ 'ml-auto': key === 2 }" :loading="reactives.isSubmitting" />
            </CardActions>
        </CardBody>
    </Card>
</template>