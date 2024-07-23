import { computed, reactive, ref } from 'vue'
import axios from './useAxios'

const state = reactive({
    authenticated: false,
    user: {}
})

export default function useAuth() {
    const authenticated = computed(() => state.authenticated)
    const user = computed(() => state.user)

    const setAuthenticated = (authenticated: boolean) => {
        state.authenticated = authenticated
    }

    const setUser = (user: any) => {
        state.user = user
    }

    const login = async (credentials: { email: string, password: string }) => {
        return axios.post('/auth/login', credentials)
            .then(res => setAuthenticated(true))
            .catch(err => setAuthenticated(false))
    }

    const attempt = async () => {
        return axios.get("/auth/user")
            .then(res=> setUser(res.data.user))
    }

    return {
        authenticated,
        user,
        login,
        attempt
    }
}
