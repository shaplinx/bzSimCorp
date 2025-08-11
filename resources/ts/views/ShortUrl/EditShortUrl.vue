<script setup lang="ts">
import Card from "@/components/cards/Card.vue";
import CardBody from "@/components/cards/CardBody.vue";
import CardTitle from "@/components/cards/CardTitle.vue";
import { useShortUrlFormSchema } from "@/forms/schemas/shorturl/shortUrlFormSchema";
import { FormKit, FormKitSchema } from "@formkit/vue";
import { useShortUrlResources } from "@/resources";
import SpinnerOverlay from "@/components/loader/SpinnerOverlay.vue";
import CardActions from "@/components/cards/CardActions.vue";
import { useEditCrud } from "@/@hooks/crud/useEditCrud";
import Table from "@/components/tables/Table.vue";
import { TableField } from "@/components/@types/table";
import { quickDateFormat } from "@/@hooks/misc/useDate";
import Button from "@/components/buttons/Button.vue";

const formSchema = useShortUrlFormSchema();
const resources = useShortUrlResources();
const { reactives, formButtons, updateSubmit, fetchOne } = useEditCrud({
  primaryKey: "url_key",
  resources,
  indexRoute: { name: "IndexShortUrl" },
  formId: "EditShortUrlForm",
  generateDeleteObject: (data) => {
    return {
      destination_url: data.destination_url,
      url_key: data.url_key,
    };
  },
  proccessFetchData(data) {
    return {
      url: data.destination_url,
      shortKey: data.url_key,
      disableTracking: data.disable_tracking,
      activateAt: data.activated_at,
      deactivateAt: data.deactivated_at,
      singleUse: data.single_use,
      forwardQueryParams: data.forward_query_params,
    };
  },
});

fetchOne();

const historyTableFields: TableField[] = [
  {
    label: "IP Address",
    value: "ip_address",
  },
  {
    label: "OS",
    value: "operating_system",
  },
  {
    label: "OS Version",
    value: "operating_system_version",
  },
  {
    label: "Browser",
    value: "browser",
  },
  {
    label: "Browser Version",
    value: "browser_version",
  },
  {
    label: "Device type",
    value: "device_type",
  },
  {
    label: "Referer",
    value: "referer_url",
  },
  {
    label: "Visited At",
    value: "visited_at",
  },
];

function exportVisits() {
  return resources.export!(reactives.model.url_key).then((url) => {
    const link = document.createElement("a");
    link.href = url || "#";
    document.body.appendChild(link);
    link.click();
    link.remove();
    setTimeout(() => URL.revokeObjectURL(url!), 1000);
  });
}
</script>

<template>
  <div class="flex flex-col gap-5">
    <Card variant="base">
      <SpinnerOverlay :show="reactives.isFetching"></SpinnerOverlay>
      <CardBody>
        <CardTitle>{{ $route.meta.title }}</CardTitle>
        <FormKit
          :disabled="reactives.isSubmitting"
          type="form"
          @submit="updateSubmit"
          :actions="false"
          id="EditShortUrlForm"
        >
          <FormKitSchema :schema="formSchema"></FormKitSchema>
        </FormKit>
        <CardActions>
          <FormKit
            type="button"
            v-for="(button, key) in formButtons"
            :key="key"
            v-bind="button"
            v-on="button.on"
            :class="{ 'ml-auto': key === 2 }"
            :loading="reactives.isSubmitting"
          />
        </CardActions>
      </CardBody>
    </Card>
    <Card variant="base">
      <SpinnerOverlay :show="reactives.isFetching"></SpinnerOverlay>
      <CardBody>
        <CardTitle>Visits History</CardTitle>
        <p class="text-sm">
          Only showing latest 100 visits. Please
          <Button size="xs" variant="success" @click="exportVisits">export</Button> to see all of it
        </p>
        <Table :data="reactives.model.visits" :fields="historyTableFields">
          <template #item-visited_at="{ item }">
            {{ quickDateFormat(item.visited_at) }}
          </template>
        </Table>
      </CardBody>
    </Card>
  </div>
</template>
