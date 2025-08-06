<template>
    <div>
        <IndexCrudTitle :buttons="titleButtons" :actions="titleActions"></IndexCrudTitle>
        <Collapse containerClass="bg-base-100 sm:bg-transparent max-sm:collapse max-sm:mb-4"
            checkboxClass="peer sm:hidden" titleClass="max-sm:collapse-title sm:hidden"
            contentClass="collapse-content sm:visible sm:p-0" title="Click to show/hide filters">
            <FormKit id="filter-form" type="form" @input="(val, node) => node.submit()" v-model="reactives.fetchParams"
                :actions="false" @submit="fetchAll">
                <div class="flex flex-col flex-grow sm:flex-row sm:gap-2 sm:flex-shink">
                    <FormKitSchema :schema="filterSchema"></FormKitSchema>
                </div>
            </FormKit>
        </Collapse>

        <table-lite v-model:selected="reactives.selected" :is-slot-mode="true" :is-loading="reactives.isFetching"
            :columns="columns" :rows="reactives.rows" v-model:settings="reactives.fetchParams" :total="reactives.total"
            :has-checkbox="true">
            <template v-slot:column-roles="{ item }">
                <Badge class="mr-1" variant="primary">{{ item.field }}</Badge>
            </template>
            <template v-slot:row-roles="data">
                <Badge class="mr-1" v-for="role in data.value.roles" variant="primary">{{ role.role }}</Badge>
            </template>
            <template v-slot:row-created_at="data">
                {{ quickDateFormat(data.value.created_at) }}
            </template>
            <template #row-actions="data">
                <DropdownMenu v-if="rowActions.length" placement="bottom-end">
                    <Button shape="circle" variant="neutral" size="sm"><FontAwesomeIcon :icon="faEllipsis"></FontAwesomeIcon></Button>
                    <template #popper="{ hide }">
                        <li v-for="action in rowActions" @click="() => { hide(); action.action?.(data.value, hide) }">
                            <a>
                                <FontAwesomeIcon v-if="action.icon" :icon="action.icon"></FontAwesomeIcon>{{
                                    action.label }}
                            </a>
                        </li>
                    </template>
                </DropdownMenu>
            </template>
        </table-lite>
    </div>
</template>
<script setup lang="ts">
import { IndexCrudCallbacks, IndexCrudConfig, useIndexCrud } from "@/@hooks/crud/useIndexCrud";
import TableLite from "@/components/datatables/TableLite.vue";
import Badge from "@/components/badges/Badge.vue";
import Collapse from "@/components/collapse/Collapse.vue";
import { quickDateFormat } from "@/@hooks/misc/useDate";
import IndexCrudTitle from "@/components/containers/IndexCrudTitle.vue";
import { useUserResources } from "@/resources"
import DropdownMenu from "@/components/dropdowns/DropdownMenu.vue";
import Button from "@/components/buttons/Button.vue";
import { faEllipsis } from "@fortawesome/free-solid-svg-icons";


const callbacks: IndexCrudCallbacks<App.Models.User> = {
    setFetchParams: (params: any) => {
        return {
            ...params, ...{
                date_start: params.date_start ? new Date(params.date_start).toISOString() : undefined,
                date_end: params.date_end ? new Date(params.date_end).toISOString() : undefined
            }
        }
    },
    mapDeleteDetails: (instance, data) => {

        return instance.reactives.rows.filter((obj: any) => instance.reactives.selected.includes(obj.id)).map((obj: any) => {
            return {
                id: obj.id,
                name: obj.name,
                email: obj.email,
                role: obj.roles?.map((role: any) => role.role).join(", ")
            }
        })
    }
}

const config: IndexCrudConfig<any> = {
    resources: useUserResources(),
    createRoute: {name:"CreateUser"},
    editRoute: {name:"EditUser"},
    enableExport:true,
    columns: [
        {
            label: "Name",
            field: "name",
            sortable: true,
        },
        {
            label: "Email",
            field: "email",
            sortable: true,
        },
        {
            label: "Roles",
            field: "roles",
        },
        {
            label: "Created At",
            field: "created_at",
            sortable: true,
        },
        {
            label: "Actions",
            field: "actions",
            sortable: false,
        },
    ]
}

const {
    reactives,
    consts: { filterSchema, titleButtons, columns, rowActions },
    computed: { titleActions },
    fetchAll
} = useIndexCrud(config, callbacks)


fetchAll()
</script>
