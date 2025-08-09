<template>
  <div>
    <IndexCrudTitle :buttons="titleButtons" :actions="titleActions" />

    <Collapse
      containerClass="bg-base-100 sm:bg-transparent max-sm:collapse max-sm:mb-4"
      checkboxClass="peer sm:hidden"
      titleClass="max-sm:collapse-title sm:hidden"
      contentClass="collapse-content sm:visible sm:p-0"
      title="Click to show/hide filters"
    >
      <FormKit
        id="filter-form"
        type="form"
        @input="(val, node) => node.submit()"
        v-model="reactives.fetchParams"
        :actions="false"
        @submit="fetchAll"
      >
        <div class="flex flex-col flex-grow sm:flex-row sm:gap-2 sm:flex-shrink">
          <FormKitSchema :schema="filterSchema" />
        </div>
      </FormKit>
    </Collapse>

    <table-lite
      v-model:selected="reactives.selected"
      :is-slot-mode="true"
      :is-loading="reactives.isFetching"
      :columns="columns"
      :rows="reactives.rows"
      v-model:settings="fetchParams"
      :total="reactives.total"
      :has-checkbox="true"
    >
      <template #row-parent="data">
        <Badge v-if="data.value.parent" class="mr-1" variant="primary">{{
          data.value.parent.name
        }}</Badge>
      </template>
      <template #row-actions="data">
        <DropdownMenu v-if="rowActions.length" placement="bottom-end">
          <Button shape="circle" variant="neutral" size="sm">
            <FontAwesomeIcon :icon="faEllipsis" />
          </Button>
          <template #popper="{ hide }">
            <li
              v-for="action in rowActions"
              @click="
                () => {
                  hide();
                  action.action?.(data.value, hide);
                }
              "
              :key="action.label"
            >
              <a>
                <FontAwesomeIcon v-if="action.icon" :icon="action.icon" />
                {{ action.label }}
              </a>
            </li>
          </template>
        </DropdownMenu>
      </template>
    </table-lite>
  </div>
</template>

<script setup lang="ts">
import { IndexCrudCallbacks, IndexCrudConfig, useIndexCrud } from "@/@hooks/crud/useIndexCrud"
import TableLite from "@/components/datatables/TableLite.vue"
import Collapse from "@/components/collapse/Collapse.vue"
import IndexCrudTitle from "@/components/containers/IndexCrudTitle.vue"
import DropdownMenu from "@/components/dropdowns/DropdownMenu.vue"
import Button from "@/components/buttons/Button.vue"
import { faEllipsis } from "@fortawesome/free-solid-svg-icons"
import { useClassificationResources } from "@/resources"
import Badge from "@/components/badges/Badge.vue"

const callbacks: IndexCrudCallbacks<App.Models.DocumentsClassification> = {
  mapDeleteDetails: (instance, data) => {
    return instance.reactives.rows
      .filter((obj: any) => instance.reactives.selected.includes(obj.id))
      .map((obj: any) => ({
        id: obj.id,
        name: obj.name,
        code: obj.code,
        classification_separator: obj.classification_separator,
      }))
  },
}

const config: IndexCrudConfig<any> = {
  resources: useClassificationResources(),
  enableExport:true,
  createRoute: { name: "CreateClassification" },
  editRoute: { name: "EditClassification" },
  columns: [
    { label: "Code", field: "code", sortable: true },
    { label: "Name", field: "name", sortable: true },
    { label: "Separator", field: "classification_separator", sortable: true },
    { label: "Parent", field: "parent", sortable: false },
    { label: "Actions", field: "actions", sortable: false },
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
