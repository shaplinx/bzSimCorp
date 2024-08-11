import { IconDefinition } from "@fortawesome/fontawesome-svg-core";
import { computed, ComputedRef, reactive, Ref, toRefs } from "vue";
import axiosInstance from "../api/useAxios";
import { AxiosError, AxiosResponse } from "axios";
import type { FormKitSchemaDefinition } from "@formkit/core"
import { RouteLocationNamedRaw, RouteLocationRaw, Router, useRouter } from "vue-router";
import { DropdownOption } from "@/components/dropdowns/dropdown";
import { ButtonProps } from "@/components/@types/button";
import { Reactive } from "vue";
import { CrudResourcesInstance } from "../api/useCrud";
import { faCaretDown, faPencilAlt, faPlus, faTrashAlt } from "@fortawesome/free-solid-svg-icons";
import { useModal } from 'vue-final-modal'
import DeleteConfirmationModal from "@/components/containers/DeleteConfirmationModal.vue";


export type FetchParams = {
    page: number;
    pageSize: number;
    orderBy: {
        column: string,
        direction: "asc" | "desc"
    }
    [key: string]: any
}

export type IndexCrudReactives<T = any> = {
    fetchParams: FetchParams,
    isFetching: boolean
    rows: T[]
    total: number
    selected: any[],
}

export type IndexCrudConstants<T = any> = {
    filterSchema: FormKitSchemaDefinition
    titleButtons: ButtonProps[]
    columns: Column[]
    resources: CrudResourcesInstance<T>,
    rowActions: DropdownOption[],
    primaryKey: string
}


export interface IndexCrudConfig<T = any> {
    reactives?: {
        fetchParams?: FetchParams,
        isFetching?: boolean
        rows?: T[]
        total?: number
        selected?: any[],
    },
    resets?: (("titleActions" | "filterSchema" | "titleButtons" | "columns" | "rowActions"))[],
    createRoute?: RouteLocationNamedRaw,
    editRoute?: RouteLocationNamedRaw,
    filterSchema?: FormKitSchemaDefinition
    titleButtons?: ButtonProps[]
    columns?: Column[]
    primaryKey?: string
    resources: CrudResourcesInstance<T>
    rowActions?: DropdownOption[]
}


export type Column = {
    isKey?: boolean;
    field: string;
    sortField?: string;
    sortable?: boolean
    width?: string;
    headerClasses?: string;
    columnClasses?: string;
    columnStyles?: string;
    headerStyles?: string;
    label: string
}

export type IndexCrudInstance<T = any> = {
    consts: {
        filterSchema: FormKitSchemaDefinition
        titleButtons: ButtonProps[]
        columns: Column[]
        rowActions: DropdownOption[],
    }
    reactives: Reactive<{
        fetchParams: FetchParams,
        isFetching: boolean
        rows: T[]
        total: number
        selected: any[],
    }>
    computed: {
        titleActions: ComputedRef<DropdownOption[]>,
    }
    fetchAll: () => Promise<unknown>,
    deletefunction: (id: any) => Promise<unknown>,
    router: Router,
}

export type IndexCrudCallbacks<T = any> = {
    setFetchParams?: (params: FetchParams) => FetchParams,
    onFetchSuccess?: (res: AxiosResponse) => any,
    onFetchError?: (res: AxiosError) => any,
    computedActions?: (instance: IndexCrudInstance<T>) => DropdownOption[],
    mapDeleteDetails?: (instance: IndexCrudInstance<T>, data?: T) => { [key: string]: string }[],
}

export function useIndexCrud<T>(config: IndexCrudConfig<T>, callbacks?: IndexCrudCallbacks<T>) {
    const primaryKey = config.primaryKey ?? 'id'

    const defaultFilterSchema: FormKitSchemaDefinition = config.resets?.includes("filterSchema") ? [] : [
        {
            $formkit: "search",
            name: "search",
            placeholder: "search",
            label: "Search",
            delay: 300,
            outerClass: "$reset mb-0 sm:mb-4"
        },
        {
            $formkit: "date",
            name: "date_start",
            label: "From Date",
            outerClass: "$reset mb-0 sm:mb-4"

        },
        {
            $formkit: "date",
            name: "date_end",
            label: "To Date",
            outerClass: "$reset mb-0 sm:mb-4"

        }
    ]

    const filterSchema: FormKitSchemaDefinition = [...defaultFilterSchema, ...config.filterSchema as any[] ?? []]

    const defaultColumns = config.resets?.includes("columns") ? [] : [
        {
            label: "ID",
            field: "id",
            width: "3%",
            sortable: true,
            isKey: true
        },
    ]

    const columns: Column[] = [...defaultColumns, ...config.columns ?? []]

    const defaultTitleButtons: ButtonProps[] = config.resets?.includes("titleButtons") ? [] : [
        {
            value: "create",
            label: "Create New",
            variant: "primary",
            icon: faPlus,
            on: {
                click: () => router.push(config.createRoute ?? "#")
            }
        },
        {
            value: "actions",
            label: "Actions",
            icon: faCaretDown
        }
    ]

    const titleButtons = [...defaultTitleButtons, ...config.titleButtons ?? []]

    function generateDeleteObjects() {
        if (callbacks?.mapDeleteDetails) {
            return callbacks.mapDeleteDetails(instance)
        }

        return instance.reactives.rows.filter((obj: any) => instance.reactives.selected.includes(obj[primaryKey]))
            .map((obj: any) => {
                return {
                    [primaryKey]: obj[primaryKey] as string
                }
            })

    }

    const defaultRowActions = config.resets?.includes("rowActions") ? [] : [
        {
            label: "Edit",
            icon: faPencilAlt,
            action: (data: any) => {
                reactives.selected = [data[primaryKey]]
                router.push(!config.editRoute ?
                    '#'
                    : {
                        ...config.editRoute,
                        params: {
                            id: data[primaryKey]
                        }
                    })
            }

        },
        {
            label: "Delete",
            icon: faTrashAlt,
            action: (data: any) => {
                reactives.selected = [data[primaryKey]]
                const { open, close } = useModal({
                    component: DeleteConfirmationModal,
                    attrs: {
                        objects: generateDeleteObjects(),
                        onClose() {
                            close()
                        },
                        onConfirm: async (deleting) => {
                            deleting.value = true
                            try {
                                for (const id of instance.reactives.selected) {
                                    await instance.deletefunction(id);
                                    await new Promise(resolve => setTimeout(resolve, 300));
                                }
                            } catch (error) {
                                throw error;
                            } finally {
                                deleting.value = false
                                close()
                                fetchAll()
                            }
                        },

                    }
                })
                open()

            }
        }
    ]
    const rowActions = [...defaultRowActions, ...config.rowActions ?? []]

    const consts: IndexCrudConstants<T> = {
        columns,
        titleButtons,
        rowActions,
        filterSchema,
        primaryKey,
        resources: config.resources
    }


    const reactives = reactive<IndexCrudReactives<T>>(Object.assign({}, {
        fetchParams: {
            page: 1,
            pageSize: 10,
            orderBy: {
                direction: "asc" as "asc",
                column: "id"
            }
        },
        total: 0,
        isFetching: false,
        selected: [],
        rows: [],
    }, config.reactives))



    function fetchAll() {
        reactives.isFetching = true
        return new Promise((resolve, reject) => {
            consts.resources.index?.({ params: callbacks?.setFetchParams?.(reactives.fetchParams!) ?? reactives.fetchParams })
                .then(res => {
                    reactives.rows = res.data.data.data as any[]
                    reactives.total = res.data.data.total
                    callbacks?.onFetchSuccess?.(res)
                    resolve(res)
                })
                .catch(err => {
                    reactives.rows = []
                    callbacks?.onFetchError?.(err)
                    reject(err)
                })
                .finally(() => reactives.isFetching = false)

        })
    }

    function deletefunction(id: any) {
        return new Promise((resolve, reject) => {
            consts.resources.delete?.(id)
                .then(res => {
                    resolve(res)
                })
                .catch(err => {
                    reject(err)
                })
        })
    }


    const router = useRouter()

    const defaultTitleActions = config.resets?.includes("titleActions") ? { value: [] } : computed(() => {
        return [
            {
                label: "Edit",
                icon: faPencilAlt,
                disabled: instance.reactives.selected.length !== 1,
                action: () => router.push(!config.editRoute ? '#' : { ...config.editRoute, params: { id: instance.reactives.selected[0] } },)
            },
            {
                label: "Delete",
                icon: faTrashAlt,
                disabled: instance.reactives.selected.length === 0,
                action: () => {
                    const { open, close } = useModal({
                        component: DeleteConfirmationModal,
                        attrs: {
                            objects: generateDeleteObjects(),
                            onClose() {
                                close()
                            },
                            onConfirm: async (deleting) => {
                                deleting.value = true
                                try {
                                    for (const id of instance.reactives.selected) {
                                        await instance.deletefunction(id);
                                        await new Promise(resolve => setTimeout(resolve, 300));
                                    }
                                } catch (error) {
                                    throw error;
                                } finally {
                                    deleting.value = false
                                    close()
                                    fetchAll()
                                }
                            },

                        }
                    })
                    open()

                }
            }
        ]
    })

    const titleActions = callbacks?.computedActions ? computed(() => callbacks?.computedActions?.(instance) ?? []) : { value: [] }



    const instance: IndexCrudInstance<T> = {
        reactives,
        consts,
        computed: {
            titleActions: computed(() => [...defaultTitleActions.value, ...titleActions.value]),
        },
        fetchAll,
        deletefunction,
        router
    }

    return instance
}
