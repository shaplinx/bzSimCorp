import { RouteLocationNamedRaw, useRoute, useRouter } from "vue-router"
import { CrudResourcesInstance, SuccessResponse } from "../api/useCrud"
import { reactive } from "vue"
import { getNode } from '@formkit/core'
import { ButtonProps } from "@/components/@types/button"
import { faChevronLeft, faPaperPlane, faTrashAlt } from "@fortawesome/free-solid-svg-icons"
import { AxiosError, AxiosRequestConfig, AxiosResponse } from "axios"


export type CreateCrudConfig<T> = {
    resources: CrudResourcesInstance<T>,
    formId:string,
    primaryKey?:string,
    formButtons?:ButtonProps[],
    resets?: ("formButtons")[],
    createRequestConf?: AxiosRequestConfig,
    indexRoute: RouteLocationNamedRaw,
    editRoute: RouteLocationNamedRaw,
    reactives?: {
        isSubmitting?:boolean,
        isFetching?: boolean,
        model?: T
    },
    proccessCreateData?: (data: T) => any
    proccessFetchData?: (data: T) => any
    onCreateSuccess?: (res : AxiosResponse<SuccessResponse<T>>) => any
    onCreateError?: (err : AxiosError) => any
    generateDeleteObject?: (model : T) => {[key:string] : string }
}

export type CreateCrudReactives<T> = {
        isSubmitting:boolean,
        model: T
}

export function useCreateCrud<T = any>(config: CreateCrudConfig<T>) {
    const resources= config.resources
    const formId = config.formId
    const indexRoute = config.indexRoute
    const editRoute = config.editRoute
    const primaryKey = config.primaryKey ?? "id"
    const route = useRoute()
    const router = useRouter()
    const reactives = reactive<CreateCrudReactives<T>>(Object.assign({},{
        isSubmitting : false,
        model:{}
    },config.reactives ?? {}))


    const formButtons : ButtonProps[] = config.resets?.includes("formButtons") ? [] : [
        {
            variant: "success",
            icon: faPaperPlane,
            label:"Submit",
            on: {
                click: () => getNode(config.formId)?.submit()
            }
        },
        {
            variant: "warning",
            icon: faChevronLeft,
            label:"Back",
            on: {
                click: () => router.push(indexRoute)
            }
        },
    ]

    if (config.formButtons) {
        formButtons.push(...config.formButtons)
    }

    function proccessFetchData(data:T) {
        return config.proccessFetchData?.(data) ?? data
    }

    function proccessCreateData(data:T) {
        return config.proccessCreateData?.(data) ?? data
    }

    function createSubmit(data : T) {
        getNode(formId)?.clearErrors()
        reactives.isSubmitting = true
        return new Promise((resolve,reject) => {
            resources.create?.({data: proccessCreateData(data), ...(config.createRequestConf ?? {})})
            .then(res => {
                config.onCreateSuccess?.(res)
                reactives.model = res.data.data as any
                resolve(res)
            })
            .catch((err) => {
                config.onCreateError?.(err)
                getNode(formId)?.setErrors(err.response.data.errors)
                reject(err)
            })
            .finally(() => reactives.isSubmitting = false)
        })

    }

    return {
        resources,
        formId,
        indexRoute,
        editRoute,
        primaryKey,
        route,
        router,
        reactives,
        formButtons,
        createSubmit,
    }

}
