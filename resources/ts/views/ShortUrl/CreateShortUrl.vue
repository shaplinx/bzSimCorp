<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import { useShortUrlFormSchema } from '@/forms/schemas/shorturl/shortUrlFormSchema';
import { FormKit, FormKitSchema } from '@formkit/vue';
import { useShortUrlResources } from '@/resources';
import { useCreateCrud } from '@/@hooks/crud/useCreateCrud';
import CardActions from '@/components/cards/CardActions.vue';

const formSchema = useShortUrlFormSchema()

const {
        editRoute,
        router,
        reactives,
        formButtons,
        createSubmit,
    } = useCreateCrud({
        formId:"CreateShortUrlForm",
        resources:useShortUrlResources(),
        indexRoute: {name:"IndexShortUrl"},
        editRoute: {name:"EditShortUrl"},
        onCreateSuccess: (res) =>  {
            router.push({...editRoute, params: {id:res.data.data?.url_key}})
        },
    })

</script>



<template>
    <Card variant="base">
        <CardBody>
            <CardTitle>{{ $route.meta.title }}</CardTitle>
            <FormKit :disabled="reactives.isSubmitting" type="form" @submit="createSubmit" :actions="false" id="CreateShortUrlForm">
                <FormKitSchema :schema="formSchema"></FormKitSchema>
            </FormKit>
            <CardActions>
            <FormKit type="button" v-for="(button,key) in formButtons" :key="key" v-bind="button" v-on="button.on" :loading="reactives.isSubmitting" />
        </CardActions>
        </CardBody>
    </Card>
</template>
