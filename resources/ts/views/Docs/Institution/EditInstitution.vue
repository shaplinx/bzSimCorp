<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import { useInstitutionFormSchema } from '@/forms/schemas/documents/institutionFormSchema';
import { useAuthStore } from '@/stores/authStore';
import { FormKit, FormKitSchema } from '@formkit/vue';
import { useInstitutionResources } from '@/resources';
import SpinnerOverlay from '@/components/loader/SpinnerOverlay.vue';
import CardActions from '@/components/cards/CardActions.vue';
import { useEditCrud } from '@/@hooks/crud/useEditCrud';


const formSchema = useInstitutionFormSchema({
    route: "edit"
})

const {
        reactives,
        formButtons,
        updateSubmit,
        fetchOne
} = useEditCrud<App.Models.DocumentsInstitution>({
    resources:useInstitutionResources(),
    indexRoute: {name:"IndexInstitution"},
    formId:"EditInstitutionForm",
    generateDeleteObject: (data) => {
        return {
                id: String(data.id),
                name: data.name,
                code: data.code,
                reset_sn_period: data.reset_sn_period,
                sn_template: data.sn_template,
            }
    }
})

fetchOne();


</script>

<template>
    <Card variant="base">
        <SpinnerOverlay :show="reactives.isFetching"></SpinnerOverlay>
        <CardBody>
            <CardTitle>{{ $route.meta.title }}</CardTitle>
            <FormKit :disabled="reactives.isSubmitting" type="form" @submit="updateSubmit" :actions="false" id="EditInstitutionForm">
                <FormKitSchema :schema="formSchema"></FormKitSchema>
            </FormKit>
        <CardActions >
            <FormKit type="button" v-for="(button,key) in formButtons" :key="key" v-bind="button" v-on="button.on" :class="{'ml-auto': key === 2}" :loading="reactives.isSubmitting" />
        </CardActions>
        </CardBody>
    </Card>
</template>
