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


const userResources = useUserResources()
const auth = useAuthStore()

const formSchema = useUserFormSchema({
    route: "create",
    authRole: auth.user.roles
})

const isSubmitting = ref(false)

function submitForm(data) {
    isSubmitting.value = true
    userResources.create?.({
        data: {
            ...data,
            roles: data.roles?.map?.(r=>r.value)
        }
    })
    .then()
    .catch((err) => {
        const formNode = getNode("CreateUserForm")
        formNode?.setErrors(err.response.data.errors)
    })
    .finally(()=> isSubmitting.value = false)
}
</script>



<template>
    <Card variant="base">
        <CardBody>
            <CardTitle>{{ $route.meta.title }}</CardTitle>
            <FormKit :disabled="isSubmitting" type="form" @submit="submitForm" :actions="false" id="CreateUserForm">
                <FormKitSchema :schema="formSchema"></FormKitSchema>
                <FormKit type="submit" label="Submit" :loading="isSubmitting"></FormKit>
            </FormKit>
        </CardBody>
    </Card>
</template>
