<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import { useInstitutionFormSchema } from '@/forms/schemas/documents/institutionFormSchema';
import { useAuthStore } from '@/stores/authStore';
import { FormKit, FormKitSchema } from '@formkit/vue';
import { useInstitutionResources } from '@/resources';
import { useCreateCrud } from '@/@hooks/crud/useCreateCrud';
import CardActions from '@/components/cards/CardActions.vue';


const formSchema = useInstitutionFormSchema({route: "create"})

const {
        editRoute,
        router,
        reactives,
        formButtons,
        createSubmit,
    } = useCreateCrud({
        formId:"CreateInstitutionForm",
        resources:useInstitutionResources(),
        indexRoute: {name:"IndexInstitution"},
        editRoute: {name:"EditInstitution"},
        onCreateSuccess: (res) =>  {
            router.push({...editRoute, params: {id:res.data.data?.id}})
        },
    })

</script>



<template>
    <Card variant="base">
        <CardBody>
            <CardTitle>{{ $route.meta.title }}</CardTitle>
            <FormKit :disabled="reactives.isSubmitting" type="form" @submit="createSubmit" :actions="false" id="CreateInstitutionForm">
                <FormKitSchema :schema="formSchema"></FormKitSchema>
            </FormKit>
            <CardActions>
            <FormKit type="button" v-for="(button,key) in formButtons" :key="key" v-bind="button" v-on="button.on" :loading="reactives.isSubmitting" />
        </CardActions>
        </CardBody>
    </Card>
</template>
