import { RouteLocationNamedRaw, useRoute, useRouter } from "vue-router"
import { CrudResourcesInstance, SuccessResponse } from "../api/useCrud"
import { reactive } from "vue"
import { getNode } from '@formkit/core'
import { ButtonProps } from "@/components/@types/button"
import { faChevronLeft, faPaperPlane, faTrashAlt } from "@fortawesome/free-solid-svg-icons"
import { useModal } from "vue-final-modal"
import DeleteConfirmationModal from "@/components/containers/DeleteConfirmationModal.vue"
import { AxiosError, AxiosRequestConfig, AxiosResponse } from "axios"


export type EditCrudConfig<T> = {
    resources: CrudResourcesInstance<T>,
    formId:string,
    primaryKey?:string,
    formButtons?:ButtonProps[],
    resets?: ("formButtons")[],
    indexRoute: RouteLocationNamedRaw
    updateRequestConf?: AxiosRequestConfig,
    reactives?: {
        isSubmitting?:boolean,
        isFetching?: boolean,
        model?: T
    },
    proccessFetchData?: (data: T) => any
    onFetchSuccess?: () => any
    onFetchError?: () => any
    proccessUpdateData?: (data: T) => any
    onUpdateSuccess?: (res : AxiosResponse<SuccessResponse<T>>) => any
    onUpdateError?: (err: AxiosError) => any
    generateDeleteObject?: (model : T) => {[key:string] : string }
}

export type EditCrudReactives<T> = {
        isSubmitting:boolean,
        isFetching: boolean,
        model: T
}

export function useEditCrud<T = any>(config: EditCrudConfig<T>) {
    const resources= config.resources
    const formId = config.formId
    const indexRoute = config.indexRoute
    const primaryKey = config.primaryKey ?? "id"
    const route = useRoute()
    const router = useRouter()
    const reactives = reactive<EditCrudReactives<T>>(Object.assign({},{
        isSubmitting : false,
        isFetching: false,
        model:{}
    },config.reactives ?? {}))

    function generateDeleteObjects(data: any) {
       return config.generateDeleteObject?.(data) ?? {[primaryKey] : data[primaryKey] as string}
    }

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
        {
            variant: "error",
            icon: faTrashAlt,
            label:"Delete",
            on: {
                click: () => {
                    const { open, close } = useModal({
                        component: DeleteConfirmationModal,
                        attrs: {
                            objects: [generateDeleteObjects(reactives.model)],
                            onClose() {
                                close()
                            },
                            onConfirm: (deleting) => {
                                deleting.value = true
                                deleteFunction().then(()=> {
                                    router.push(indexRoute)
                                })
                                .finally(() => {
                                    deleting.value = false
                                    close()
                                })
                            },
                        }
                    })
                    open()
                }
            }
        }
    ]

    if (config.formButtons) {
        formButtons.push(...config.formButtons)
    }

    function proccessFetchData(data:T) {
        return config.proccessFetchData?.(data) ?? data
    }

    function proccessUpdateData(data:T) {
        return config.proccessUpdateData?.(data) ?? data
    }

    function updateSubmit(data : T) {
        getNode(formId)?.clearErrors()
        reactives.isSubmitting = true
        return new Promise((resolve,reject) => {
            resources.update?.(route.params.id as string, {data: proccessUpdateData(data), ...(config.updateRequestConf ?? {})})
            .then(res => {
                config.onUpdateSuccess?.(res)
                getNode(formId)?.input(proccessFetchData(res.data.data as T))
                reactives.model = res.data.data as any
                resolve(res)
            })
            .catch((err) => {
                config.onUpdateError?.(err)
                getNode(formId)?.setErrors(err.response.data.errors)
                reject(err)
            })
            .finally(() => reactives.isSubmitting = false)
        })

    }

    function fetchOne(id?: number | string) {
        reactives.model = {} as any
        id = id ? String(id) : route.params.id as string
        reactives.isFetching = true
        return new Promise((resolve, reject) => {
            resources.show?.(route.params.id as string)
                .then((res) => {
                    getNode(formId)?.input(proccessFetchData(res.data.data as T))
                    reactives.model = res.data.data as any
                    resolve(res)
                })
                .catch(reject)
                .finally(() => reactives.isFetching = false)
        })
    }

    function deleteFunction(id?: number | string) {
        id = id ? String(id) : route.params.id as string
        return new Promise((resolve, reject) => {
            resources.delete?.(id)
                .then(res => {
                    resolve(res)
                })
                .catch(err => {
                    reject(err)
                })
        })
    }

    return {
        resources,
        formId,
        indexRoute,
        primaryKey,
        route,
        router,
        reactives,
        formButtons,
        updateSubmit,
        fetchOne
    }

}
