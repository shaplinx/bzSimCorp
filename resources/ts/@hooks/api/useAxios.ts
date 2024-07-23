import axios from "axios";

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


axiosInstance.interceptors.response.use(
    response => {
        // Do something with response data
        return response;
    },
    error => {
        if (error.response?.status === 419 && error.response.data.message === "CSRF token mismatch.") {
            return new Promise((resolve, reject) => {
                getCsrfToken()
                    .then(res => {
                        axiosInstance(error.config)
                            .then(response=> resolve(response))
                            .catch(err => reject(err))
                    })
                    .catch(err => reject(err))
            })
        }

        // Handle other errors
        return Promise.reject(error);
    }
);

export default axiosInstance
