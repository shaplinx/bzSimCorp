import axios from "axios";
import {useAuthStore} from "@/stores/authStore"
import { toast } from 'vue3-toastify';
import { useRouter } from "vue-router";

const axiosInstance = axios.create({
    baseURL: "/api/v1/",
    withCredentials: true,
    withXSRFToken: true
});


function getCsrfToken() {
    return new Promise((resolve, reject) => {
        axiosInstance.get("/api/csrf-cookie", { baseURL: "/" })
            .then(res => resolve(res))
            .catch(err => reject(err))
    })
}
toast.POSITION.BOTTOM_RIGHT

axiosInstance.interceptors.response.use(
    response => {
        if (response.data.message === "Fetched Successfully" || !response.data.message) return response
        toast.success(response.data.message)
        return response
    },
    error => {
        toast.error(error.response?.data?.message || "Undefined Error",{
            toastId: error.response?.status === 401 ? 401 : undefined
        })
        if ((error.lastError  ===419)  && (error.response?.status === 419)) {
            toast.error("Unable to fetch correct CSRF token, please contact your administrator")
            return Promise.reject(error);
        }
        if (error.response?.status === 419 && error.response.data.message === "CSRF token mismatch.") {
            const loadingToast = toast.loading("Fetching new CSRF token...")
            return new Promise((resolve, reject) => {
                getCsrfToken()
                    .then(() => {
                        toast.update(loadingToast, {
                            render:"CSRF token fetched successfully",
                            type:"success",
                            autoClose: true,
                            closeOnClick: true,
                            closeButton: true,
                            isLoading: false,
                        })
                        axiosInstance(error.config)
                            .then(response=> resolve(response))
                            .catch(err => reject(err))
                    })
                    .catch(err => {
                        toast.update(loadingToast, {
                            render:err.response.data.message || "Undefined Error",
                            type:"error",
                            autoClose: true,
                            closeOnClick: true,
                            closeButton: true,
                            isLoading: false,
                        })
                        reject({...err, lastError : 419})
                    })
                    .finally(()=>toast.done(loadingToast))
            })
        }
        const router = useRouter()

        if (error.response?.status === 401) {
            const authStore = useAuthStore()
            authStore.setUser({})
            authStore.setAuthenticated(false)
            router.push({name:"signin"})
        }

        // Handle other errors
        return Promise.reject(error);
    }
);

export default axiosInstance
