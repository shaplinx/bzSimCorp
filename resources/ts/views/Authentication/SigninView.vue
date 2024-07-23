<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import DefaultAuthCard from '@/components2/Auths/DefaultAuthCard.vue'
import InputGroup from '@/components2/Auths/InputGroup.vue'
import useAuth from '@/@hooks/api/useAuth';
import { ref, reactive } from 'vue'

const loginForm = reactive({
    email: '',
    password: ''
})

const {
    authenticated,
    user,
    login,
    attempt
} = useAuth();

const pageTitle = ref('Sign In')
</script>

<template>
    <div class="h-screen w-full flex items-center justify-center align-middle">
        <Card variant="base" class="max-w-lg w-full">
            <CardBody>
                <CardTitle>Sign In to TailAdmin</CardTitle>
                <FormKit type="form" @submit="login" v-model="loginForm" submit="false" #default="{ disabled }" :actions="false">
                    <FormKit type="email" name="email" label="E-Mail Address"></FormKit>
                    <FormKit type="password" name="password" label="Password"></FormKit>
                    <FormKit type="submit" inputClass="w-full" label="Sign in" :disabled="(disabled as boolean)" />
                </FormKit>

                <div class="mt-6 text-center">
                    <p class="font-medium">
                        Don’t have any account?
                        <router-link to="/auth/signup" class="text-primary">Sign up</router-link>
                    </p>
                </div>
            </CardBody>
        </Card>
    </div>
</template>
