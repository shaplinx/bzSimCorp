<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import { useUserFormSchema } from '@/forms/schemas/userFormSchema';
import { useAuthStore } from '@/stores/authStore';
import { FormKit, FormKitSchema } from '@formkit/vue';
import { useUserResources } from '@/resources';
import SpinnerOverlay from '@/components/loader/SpinnerOverlay.vue';
import CardActions from '@/components/cards/CardActions.vue';
import { useEditCrud } from '@/@hooks/crud/useEditCrud';


const auth = useAuthStore()

const formSchema = useUserFormSchema({
    route: "edit",
    authRole: auth.user.roles.map((role:any) => role.role)
})

const {
        reactives,
        formButtons,
        updateSubmit,
        fetchOne
} = useEditCrud<App.Models.User>({
    resources:useUserResources(),
    indexRoute: {name:"UserIndex"},
    formId:"EditUserForm",
    proccessFetchData: (data) => {
        return Object.assign({},data,{
        roles: data?.roles?.map(r => ({ value: r.role, label: r.role }))
    })},
    proccessUpdateData: (data) => {
        return Object.assign({},data,{
        roles: data?.roles?.map(r => r.value)
    })},
    generateDeleteObject: (data) => {
        return {
                id: String(data.id),
                name: data.name,
                email: data.email,
                role: data.roles?.map((role: any) => role.role).join(", ") ?? ''
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
            <FormKit :disabled="reactives.isSubmitting" type="form" @submit="updateSubmit" :actions="false" id="EditUserForm">
                <FormKitSchema :schema="formSchema"></FormKitSchema>
            </FormKit>
        <CardActions >
            <FormKit type="button" v-for="(button,key) in formButtons" :key="key" v-bind="button" v-on="button.on" :class="{'ml-auto': key === 2}" :loading="reactives.isSubmitting" />
        </CardActions>
        </CardBody>
    </Card>
</template>
