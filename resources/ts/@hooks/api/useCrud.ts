import { AxiosResponse, AxiosError, AxiosRequestConfig, Method } from "axios"
import axios from "./useAxios"

export type Action = "index" | "create" | "update" | "delete" | "show"

export type CrudConfig = {
    baseUrl: string,
    primaryKey?: string,
    endpoints?: {
        index?: string,
        show?: string,
        create?: string,
        update?: string,
        delete?: string
    }
    generateUrl?: (endpoints: CrudConfig["endpoints"], action: string, primaryKey: string, identifier?: number | string) => string,
    methods?: {
        index?: Method,
        show?: Method,
        create?: Method,
        update?: Method,
        delete?: Method
    },
    actions?: Action[],
    overrides?: CrudResourcesInstance

}

export type IndexResponse<T = any> = {
    data: {
        data: T[],
        total: number,
        current_page: number,
        from: number,
        last_page: number,
        per_page: number,
        to: number
    }
    message: string

}

export type SuccessResponse<T = any> = {
    message: string,
    data?: T
}

export type CrudResourcesInstance<T = any> = {
    index?: (config?: AxiosRequestConfig) => Promise<AxiosResponse<IndexResponse<T>>>;
    show?: (identifier: string | number, config?: AxiosRequestConfig) => Promise<AxiosResponse<SuccessResponse<T>>>;
    create?: (config?: AxiosRequestConfig) => Promise<AxiosResponse<SuccessResponse<T>>>;
    update?: (identifier: string | number, config?: AxiosRequestConfig) => Promise<AxiosResponse<SuccessResponse<T>>>;
    delete?: (identifier: string | number, config?: AxiosRequestConfig) => Promise<AxiosResponse<SuccessResponse<T>>>;
    [key:string] : any
}

export function useCrud<T = any>(config: CrudConfig): CrudResourcesInstance<T> {

    const primaryKey = config.primaryKey ?? "id"

    const endpoints = Object.assign({}, {
        index: config.baseUrl,
        show: `${config.baseUrl}/:${primaryKey}`,
        create: config.baseUrl,
        update: `${config.baseUrl}/:${primaryKey}`,
        delete: `${config.baseUrl}/:${primaryKey}`
    }, config.endpoints ?? {})

    function generateUrl(action: Action, identifier?: number | string) {
        if (config.generateUrl) return config.generateUrl(endpoints, action, primaryKey, identifier)
        return endpoints[action].replace(`:${primaryKey}`, String(identifier))
    }



    const methods = Object.assign({}, {
        index: "get" as Method,
        show: "get" as Method,
        create: "post" as Method,
        update: "patch" as Method,
        delete: "delete" as Method
    }, config.methods ?? {})

    const actions = config.actions ?? ["index","create","delete","show","update"]

    const resources : CrudResourcesInstance<T> = {}

    if (actions.includes("index")) {
        resources.index = (config?: AxiosRequestConfig) => {
            return new Promise<AxiosResponse<IndexResponse<T>>>((reslove, reject) => {
                axios({
                    method: methods["index"],
                    url: generateUrl("index"),
                    ...config
                })
                    .then((res) => reslove(res))
                    .catch((err) => reject(err))
            })
        }
    }

    if (actions.includes("show")) {
        resources.show = (identifier: string | number, config?: AxiosRequestConfig) => {
            return new Promise<AxiosResponse<SuccessResponse<T>>>((reslove, reject) => {
                axios({
                    method: methods["show"],
                    url: generateUrl("show", identifier),
                    ...config
                })
                    .then((res) => reslove(res))
                    .catch((err) => reject(err))
            })
        }
    }

    if (actions.includes("create")) {
        resources.create = (config?: AxiosRequestConfig) => {
            return new Promise<AxiosResponse<SuccessResponse<T>>>((reslove, reject) => {
                axios({
                    method: methods["create"],
                    url: generateUrl("create"),
                    ...config
                })
                    .then((res) => reslove(res))
                    .catch((err) => reject(err))
            })
        }
    }

    if (actions.includes("update")) {
        resources.update = (identifier: string | number, config?: AxiosRequestConfig) => {
            return new Promise<AxiosResponse<SuccessResponse<T>>>((reslove, reject) => {
                axios({
                    method: methods["update"],
                    url: generateUrl("update", identifier),
                    ...config
                })
                    .then((res) => reslove(res))
                    .catch((err) => reject(err))
            })
        }
    }

    if (actions.includes("delete")) {
        resources.delete = (identifier: string | number, config?: AxiosRequestConfig) => {
            return new Promise<AxiosResponse<SuccessResponse<T>>>((reslove, reject) => {
                axios({
                    method: methods["delete"],
                    url: generateUrl("delete", identifier),
                    ...config
                })
                    .then((res) => reslove(res))
                    .catch((err) => reject(err))
            })
        }
    }

    Object.assign(resources, config.overrides?? {})

    return resources
}


