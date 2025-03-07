import { defineStore } from 'pinia'
import { computed, reactive, ref } from 'vue'
import axios from '@/@hooks/api/useAxios'
import { AxiosError, AxiosResponse } from 'axios'

export const useAuthStore = defineStore('sidebar', () => {
    const user = ref<{ id?: number, [key: string]: any }>({})
    const isAuthenticated = ref(false)
    const attemptCount = ref(0)

    const login = (credentials: { email: string, password: string }) => {
        return new Promise((resolve,reject) => {
            return axios.post('/auth/login', credentials)
            .then(( res :AxiosResponse) => {
                isAuthenticated.value =true
                resolve(res)
            })
            .catch((err : AxiosError) => {
                reject(err)
            })
        })

    }

    function logout() {
        return new Promise((resolve,reject) => {
            return axios.post('/auth/logout')
            .then(( res :AxiosResponse) => {
                isAuthenticated.value =false
                user.value= {}
                resolve(res)
            })
            .catch((err : AxiosError) => {
                reject(err)
            })
        })
    }

    function attempt() {
        attemptCount.value++
        return new Promise((resolve,reject) => {
            return axios.get("/auth/user")
            .then(( res :AxiosResponse) => {
                user.value= res.data.data.user
                isAuthenticated.value =true
                resolve(res)
            })
            .catch((err : AxiosError) => {
                reject(err)
            })
        })
    }

    function getCsrfToken() {
        return new Promise((resolve, reject) => {
            axios.get("/api/csrf-cookie", { baseURL: "/" })
                .then(res => resolve(res))
                .catch(err => reject(err))
        })
    }

    return {
        isAuthenticated,
        user,
        login,
        attempt,
        logout,
        attemptCount,
        getCsrfToken
    }
})
