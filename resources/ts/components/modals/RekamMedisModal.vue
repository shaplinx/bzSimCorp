<template>
  <vue-final-modal
    v-slot="{ params, close }: { params: RekamMedisParams, close: any }"
    v-bind="$attrs"
    classes="flex justify-center items-center"
    content-class="w-10/12 h-4/5"
  >
    <card class="bg-base-100 w-full h-full" bordered>
      <div class="w-full h-full overflow-auto">
        <Button @click="close" variant="ghost" class="absolute z-10 right-3 top-3"
          ><FontAwesomeIcon size="lg" :icon="faCircleXmark"></FontAwesomeIcon
        ></Button>
        <card-body v-if="params.pasienId">
          <RekamMedis :pasien="params.pasien" :pasien-id="params.pasienId"> </RekamMedis>
        </card-body>
      </div>
    </card>
  </vue-final-modal>
</template>
<script setup lang="ts">
import RekamMedis from "@/views/admin/rekam-medis/index.vue";
import Button from "../buttons/Button.vue";
import Card from "@/components/cards/Card.vue";
import CardBody from "@/components/cards/CardBody.vue";
import { faCircleXmark } from "@fortawesome/free-regular-svg-icons";


interface RekamMedisParams {
  pasienId: string | number;
  pasien: App.Models.Pasien
}
</script>
