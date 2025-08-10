<template>
  <div>
    <IndexCrudTitle :buttons="titleButtons" :actions="titleActions"></IndexCrudTitle>
    <Collapse containerClass="bg-base-100 sm:bg-transparent max-sm:collapse max-sm:mb-4" checkboxClass="peer sm:hidden"
      titleClass="max-sm:collapse-title sm:hidden" contentClass="collapse-content sm:visible sm:p-0"
      title="Click to show/hide filters">
      <FormKit id="filter-form" type="form" @input="(val, node) => node.submit()" v-model="reactives.fetchParams"
        :actions="false" @submit="customFetch">
        <div class="flex flex-col flex-grow sm:flex-row sm:gap-2 sm:flex-shink">
          <FormKitSchema :schema="filterSchema"></FormKitSchema>
        </div>
      </FormKit>
    </Collapse>
    <div class="flex flex-col sm:flex-row gap-2 items-start">
      <div class="menu bg-base-100 rounded-box w-full sm:w-56">
        <li @click="() => onMessageTypeChange('inbox')">
          <a :class="{ active: reactives.fetchParams.messageType == 'inbox' }">Inbox</a>
        </li>
        <li @click="() => onMessageTypeChange('outbox')">
          <a :class="{ active: reactives.fetchParams.messageType == 'outbox' }">Outbox</a>
        </li>
      </div>

      <table-lite class="flex-grow" v-model:selected="reactives.selected" :is-slot-mode="true"
        :is-loading="reactives.isFetching" :columns="columns" :rows="reactives.rows" v-model:settings="fetchParams"
        :total="reactives.total" :has-checkbox="true">
        <template v-slot:column-roles="{ item }">
          <Badge class="mr-1" variant="primary">{{ item.field }}</Badge>
        </template>
        <template v-slot:row-roles="data">
          <Badge class="mr-1" v-for="role in data.value.roles" variant="primary">{{
            role.role
          }}</Badge>
        </template>
        <template v-slot:row-created_at="data">
          {{ quickDateFormat(data.value.created_at) }}
        </template>
        <template #row-status="data">
          <template v-if="reactives.fetchParams.messageType == 'inbox'">
            <Badge
              :variant="data.value.recipients?.find((x: any) => x.id == auth.user?.id)?.pivot?.read_at ? 'success' : 'warning'">
              {{data.value.recipients?.find((x: any) => x.id == auth.user?.id)?.pivot?.read_at ? 'Read' : 'Unread'}}
            </Badge>
          </template>
          <template class="flex flex-wrap gaps-2" v-if="reactives.fetchParams.messageType == 'outbox'">
            <Badge
              v-for="recipient in data.value.recipients"
              v-tooltip="recipient.pivot?.read_at ? 'Read':'Unread'"
              :variant="recipient.pivot?.read_at ? 'success' : 'warning'">
              {{recipient.name}}
            </Badge>
          </template>
        </template>
        <template #row-actions="data">
          <DropdownMenu v-if="rowActions.length" placement="bottom-end">
            <Button shape="circle" variant="neutral" size="sm">
              <FontAwesomeIcon :icon="faEllipsis"></FontAwesomeIcon>
            </Button>
            <template #popper="{ hide }">
              <li v-for="action in rowActions" @click="
                () => {
                  hide();
                  action.action?.(data.value, hide);
                }
              ">
                <a>
                  <FontAwesomeIcon v-if="action.icon" :icon="action.icon"></FontAwesomeIcon>{{ action.label }}
                </a>
              </li>
            </template>
          </DropdownMenu>
        </template>
      </table-lite>
    </div>
  </div>
</template>
<script setup lang="ts">
import {
  IndexCrudCallbacks,
  IndexCrudConfig,
  useIndexCrud,
} from "@/@hooks/crud/useIndexCrud";
import TableLite from "@/components/datatables/TableLite.vue";
import Badge from "@/components/badges/Badge.vue";
import Collapse from "@/components/collapse/Collapse.vue";
import { quickDateFormat } from "@/@hooks/misc/useDate";
import IndexCrudTitle from "@/components/containers/IndexCrudTitle.vue";
import { useMessagingResources } from "@/resources";
import DropdownMenu from "@/components/dropdowns/DropdownMenu.vue";
import Button from "@/components/buttons/Button.vue";
import { faEllipsis, faCaretDown, faPlus, faEye } from "@fortawesome/free-solid-svg-icons";
import { useModal } from "vue-final-modal";
import ComposeMessageModal from "@/components/containers/ComposeMessageModal.vue";
import router from "@/router";
import { useAuthStore } from "@/stores/authStore";

const auth = useAuthStore();

const callbacks: IndexCrudCallbacks<App.Models.User> = {
  setFetchParams: (params: any) => {
    return {
      ...params,
      ...{
        date_start: params.date_start
          ? new Date(params.date_start).toISOString()
          : undefined,
        date_end: params.date_end ? new Date(params.date_end).toISOString() : undefined,
      },
    };
  },
  mapDeleteDetails: (instance, data) => {
    return instance.reactives.rows
      .filter((obj: any) => instance.reactives.selected.includes(obj.id))
      .map((obj: any) => {
        return {
          id: obj.id,
          subject: obj.subject,
        };
      });
  },
};

const config: IndexCrudConfig<any> = {
  resources: useMessagingResources(),
  resets: ["titleButtons", "rowActions", "titleActions"],
  titleButtons: [
    {
      label: "Compose New",
      icon: faPlus,
      variant: "primary",
      on: {
        click: () => {
          const { open, close } = useModal({
            component: ComposeMessageModal,
            attrs: {
              onClose() {
                close()
              },
            }
          })
          open();
        }
      }
    },
    {
      value: "actions",
      label: "Actions",
      icon: faCaretDown,
    },
  ],
  reactives: {
    fetchParams: {
      messageType: "inbox",
      orderBy: {
        column: "created_at",
        direction: "desc",
      },
    },
  },
  columns: [
    {
      label: "Subject",
      field: "subject",
      sortable: true,
    },
    {
      label: "Sender",
      field: "sender_id",
      sortable: true,
    },
    {
      label: "Created At",
      field: "created_at",
      sortable: true,
    },

    {
      label: "Status",
      field: "status",
      sortable: false,
    },
    {
      label: "Actions",
      field: "actions",
      sortable: false,
    },
  ],
};

const {
  reactives,
  consts: { filterSchema, titleButtons, columns },
  computed: { titleActions, fetchParams },
  fetchAll,
} = useIndexCrud(config, callbacks);

function customFetch() {
  fetchAll();
}

const rowActions = [
  {
    label: "Read",
    icon: faEye,
    action: (data: any) => router.push({ name: "ReadMessage", params: { id: data.id } })
  },
]

function onMessageTypeChange(type: "inbox" | "outbox") {
  reactives.fetchParams.messageType = type;
}

fetchAll();
</script>
