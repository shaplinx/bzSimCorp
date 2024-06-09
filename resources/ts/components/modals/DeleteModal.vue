<template>
  <vue-final-modal
    v-slot="{ params, close }"
    v-bind="$attrs"
    classes="flex justify-center items-center"
    content-class="w-10/12 sm:w-8/12"
  >
    <Card class="bg-base-100" bordered>
      <Button @click="close" variant="ghost" class="absolute right-3 top-3"
        ><FontAwesomeIcon size="lg" :icon="faCircleXmark"></FontAwesomeIcon
      ></Button>
      <CardBody v-if="params.moduleName">
        <CardTitle>
          {{ t(`${toCamelCase(params.moduleName)}.delete-modal-title`) }}
        </CardTitle>
        {{ t(`${toCamelCase(params.moduleName)}.delete-modal-content`) }}

        <div class="flex flex-wrap gap-2">
          <template v-if="Array.isArray(params.id)">
            <Badge v-for="id in params.id">{{ id }}</Badge>
          </template>
          <template v-else>
            <Badge>{{ params.id }}</Badge>
          </template>
        </div>
        <div
          class="ml-auto w-full sm:w-8/12 mt-8 grid gap-4 grid-cols-1 sm:grid-cols-2"
        >
          <Button @click="close" variant="accent">{{
            t("menu.no")
          }}</Button>
          <Button
            :loading="isDeleting"
            @click="onYes(params, close)"
            variant="secondary"
            >{{ t("menu.yes") }}</Button
          >
        </div>
      </CardBody>
    </Card>
  </vue-final-modal>
</template>

<script lang="ts" setup>
import { CRUD } from "@/services/api/modules/crud/crud";

import { useI18n } from "vue-i18n";
import { ref } from "vue";
import { CaseConversion } from "@/hooks/helpers/string";
import Button from "../buttons/Button.vue";
import Badge from "../badges/Badge.vue";
import Card from "@/components/cards/Card.vue";
import CardBody from "@/components/cards/CardBody.vue";
import CardTitle from "@/components/cards/CardTitle.vue";
import { faCircleXmark } from "@fortawesome/free-regular-svg-icons";


interface DeleteModalParams<T> {
  id: any;
  moduleName: string;
  deleteFn: CRUD<any>["destroy"];
  onSuccess: Function;
}

const isDeleting = ref(false);

const deleteFn = (
  params: DeleteModalParams<any>,
  id: number | string,
  close: any,
  execute: boolean
) => {
  isDeleting.value = true;
  params.deleteFn!({ id })
    .then((res) => {
      if (execute) {
        close();
        params.onSuccess(res);
      }
    })
    .finally(() => {
      if (execute) {
        isDeleting.value = false;
      }
    });
};

const onYes = (params: DeleteModalParams<any>, close: any) => {
  if (Array.isArray(params.id)) {
    params.id.forEach((el, key) => {
      deleteFn(params, el, close, key === params.id.length - 1);
    });
  } else {
    deleteFn(params, params.id, close, true);
  }
};

function toCamelCase(str: string = "") {
  return new CaseConversion(str).toCamelCase().get();
}

const { t } = useI18n();
</script>
