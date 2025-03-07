<template>
  <vue-final-modal
    v-slot="{ params, close }: { params: MutasiObatParams, close: any }"
    v-bind="$attrs"
    classes="flex justify-center items-center"
    content-class="w-10/12 h-4/5"
  >
    <card class="bg-base-100 w-full h-full" bordered>
      <div class="w-full h-full overflow-auto">
        <Button @click="close" variant="ghost" class="absolute z-10 right-3 top-3"
          ><FontAwesomeIcon size="lg" :icon="faCircleXmark"></FontAwesomeIcon
        ></Button>
        <card-body v-if="params.obatId">
          <MutasiObat :obat="params.obat" :obat-id="params.obatId"> </MutasiObat>
        </card-body>
      </div>
    </card>
  </vue-final-modal>
</template>
<script setup lang="ts">
import MutasiObat from "@/views/admin/fasilitas/farmasi/obat/mutation.vue";
import { faCircleXmark } from "@fortawesome/free-solid-svg-icons";
import Card from "@/components/cards/Card.vue";
import CardBody from "@/components/cards/CardBody.vue";
import Button from "../buttons/Button.vue";


interface MutasiObatParams {
  obatId: string | number;
  obat: App.Models.Fasilitas.Farmasi.Obat
}
</script>
