<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import { ref, reactive } from 'vue'
import { useAuthStore } from '@/stores/authStore';
import { useRouter } from 'vue-router';
import { getNode } from '@formkit/core'

const router = useRouter();

const pageTitle = ref('Sign In')

const loginForm = reactive({
    email: '',
    password: ''
})


const {
    login,
    attempt,
    getCsrfToken
} = useAuthStore();



const loggingIn = ref(false)

async function submitLogin(credentials: { email: string, password: string }) {
    const formNode = getNode("loginForm")
    loggingIn.value = true

    return new Promise((resolve,reject) => {
        getCsrfToken()
            .then(()=> {
                login(credentials)
                    .then(() => {
                        attempt()
                            .then((res)=> {
                                resolve(res)
                                router.push({name:"Dashboard"})
                            })
                            .catch(reject)
                            .finally(()=> loggingIn.value = false)
                    })
                    .catch((err)=> {
                        reject(err)
                        formNode?.setErrors(err.response?.data?.message, err.response?.data?.errors || {})
                        loggingIn.value = false
                    })
            })
            .catch((err) => {
                reject(err)
                loggingIn.value = false
            })
    })

}



</script>

<template>
    <div class="h-screen w-full flex items-center justify-center align-middle">
        <Card variant="base" class="max-w-lg w-full">
            <CardBody>
                <CardTitle>Sign In to TailAdmin</CardTitle>
                <FormKit id="loginForm" type="form" @submit="submitLogin" v-model="loginForm" #default="{ disabled }" :actions="false" :disabled="loggingIn">
                    <FormKit type="email" name="email" label="E-Mail Address"></FormKit>
                    <FormKit type="password" name="password" label="Password"></FormKit>
                    <FormKit type="submit" block :loading="disabled" variant="primary" label="Sign in"/>
                </FormKit>

                <div class="mt-6 text-center">
                    <p class="font-medium">
                        Don’t have any account? {{ loggingIn }}
                        <router-link to="/auth/signup" class="text-primary">Sign up</router-link>
                    </p>
                </div>
            </CardBody>
        </Card>
    </div>
</template>
