<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import { useUserFormSchema } from '@/forms/schemas/userFormSchema';
import { useAuthStore } from '@/stores/authStore';
import { FormKit, FormKitSchema } from '@formkit/vue';
import { useUserResources } from '@/resources';
import { getNode } from '@formkit/core'
import { ref } from 'vue';
import { useCreateCrud } from '@/@hooks/crud/useCreateCrud';
import CardActions from '@/components/cards/CardActions.vue';

const auth = useAuthStore()

const formSchema = useUserFormSchema({
    route: "create",
    authRole: auth.user.roles.map((role:any) => role.role)
})

const {
        editRoute,
        primaryKey,
        router,
        reactives,
        formButtons,
        createSubmit,
    } = useCreateCrud({
        formId:"CreateUserForm",
        resources:useUserResources(),
        indexRoute: {name:"UserIndex"},
        editRoute: {name:"EditUser"},
        onCreateSuccess: (res) =>  {
            router.push({...editRoute, params: {id:res.data.data?.id}})
        },
        proccessCreateData: (data) => {
            return Object.assign({},data,{
                roles: data.roles?.map(r => r.value)
            })
        }
    })

</script>



<template>
    <Card variant="base">
        <CardBody>
            <CardTitle>{{ $route.meta.title }}</CardTitle>
            <FormKit :disabled="reactives.isSubmitting" type="form" @submit="createSubmit" :actions="false" id="CreateUserForm">
                <FormKitSchema :schema="formSchema"></FormKitSchema>
            </FormKit>
            <CardActions>
            <FormKit type="button" v-for="(button,key) in formButtons" :key="key" v-bind="button" v-on="button.on" :loading="reactives.isSubmitting" />
        </CardActions>
        </CardBody>
    </Card>
</template>
