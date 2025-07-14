<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import { useLetterFormSchema } from '@/forms/schemas/documents/letterFormSchema';
import { useLetterResources } from '@/resources';
import { useCreateCrud } from '@/@hooks/crud/useCreateCrud';
import CardActions from '@/components/cards/CardActions.vue';
import { FormKit, FormKitSchema } from '@formkit/vue';
import { AnyNode } from 'postcss';

const formSchema = useLetterFormSchema({ route: 'create' });

const {
    editRoute,
    router,
    reactives,
    formButtons,
    createSubmit,
} = useCreateCrud({
    formId: 'CreateLetterForm',
    resources: useLetterResources(),
    indexRoute: { name: 'IndexLetter' },
    editRoute: { name: 'EditLetter' },
    createRequestConf: {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    },
    onCreateSuccess: (res) => {
        router.push({ ...editRoute, params: { id: res.data.data?.id } });
    },
    proccessCreateData: (data: any) => {
        const formData = new FormData()

        Object.entries(data).forEach(([key, value]: any) => {
            if (value && key !== "file" && key !== "institution" && key !== "classification") {
                formData.append(key, value);
            }
        });

        if (data.institution) {
            formData.append("institution_id", data.institution.id);
        }
        if (data.institution) {
            formData.append("classification_id", data.classification.id);
        }

        if (data.file[0]?.file) {
            formData.append("file", data.file[0].file);
        }
        return formData
    }
});
</script>

<template>
    <Card variant="base">
        <CardBody>
            <CardTitle>{{ $route.meta.title }}</CardTitle>
            <FormKit :disabled="reactives.isSubmitting" type="form" @submit="createSubmit" :actions="false"
                id="CreateLetterForm">
                <FormKitSchema :schema="formSchema" />
            </FormKit>
            <CardActions>
                <FormKit type="button" v-for="(button, key) in formButtons" :key="key" v-bind="button" v-on="button.on"
                    :loading="reactives.isSubmitting" />
            </CardActions>
        </CardBody>
    </Card>
</template>