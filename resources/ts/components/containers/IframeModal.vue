<script setup lang="ts">
import { VueFinalModal } from 'vue-final-modal'
import Button from '../buttons/Button.vue';


const props = defineProps<{
    pdfUrl?: string,
}>();


const emit = defineEmits(["close", "confirm"])

function download() {
    const link = document.createElement('a');
    link.href = props.pdfUrl?? "";
    link.click();
    URL.revokeObjectURL(link.href);
}

</script>

<template>
    <VueFinalModal class="flex justify-center items-center px-8 py-8" overlay-transition="vfm-fade"
        content-transition="vfm-fade" content-class="card w-full h-full bg-base-100 text-base-content card-body">
        <div class="flex flex-col gap-2"> </div>
        <slot>
            <iframe class="py-4" v-if="pdfUrl" :src="pdfUrl" width="100%" height="100%"/>
        </slot>
        <div class="flex justify-center sm:justify-end gap-2">
            <Button variant="success" @click="download">
                Download
            </Button>
            <Button variant="error" @click="emit('close')">
                Close
            </Button>
        </div>

    </VueFinalModal>
</template>
