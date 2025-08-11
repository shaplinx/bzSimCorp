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
            :columns="columns" :rows="reactives.rows" v-model:settings="fetchParams" :total="reactives.total"
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
                        <template v-slot:row-activated_at="data">
                {{ quickDateFormat(data.value.activated_at) }}
            </template>
                        <template v-slot:row-deactivated_at="data">
                {{ quickDateFormat(data.value.deactivated_at) }}
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
import { useShortUrlResources } from "@/resources"
import DropdownMenu from "@/components/dropdowns/DropdownMenu.vue";
import Button from "@/components/buttons/Button.vue";
import { faEllipsis } from "@fortawesome/free-solid-svg-icons";


const callbacks: IndexCrudCallbacks = {
    mapDeleteDetails: (instance, data) => {
        return instance.reactives.rows.filter((obj: any) => instance.reactives.selected.includes(obj.url_key)).map((obj: any) => {
            return {
                destination_url: obj.destination_url,
                url_key: obj.url_key,
            }
        })
    }
}

const config: IndexCrudConfig<any> = {
    primaryKey:"url_key",
    resources: useShortUrlResources(),
    createRoute: {name:"CreateShortUrl"},
    editRoute: {name:"EditShortUrl"},
    resets:["columns"],
    columns: [
        {
            label: "Key",
            field: "url_key",
            sortable: true,
            isKey:true,
        },
        {
            label: "Destination URL",
            field: "destination_url",
            sortable: true,
        },
        {
            label: "Short URL",
            field: "short_url",
            sortable: false,
        },
        {
            label: "Activated At",
            field: "activated_at",
            sortable: true,
        },
        {
            label: "Dectivated At",
            field: "deactivated_at",
            sortable: true,
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
    computed: { titleActions, fetchParams },
    fetchAll
} = useIndexCrud(config, callbacks)


fetchAll()
</script>
