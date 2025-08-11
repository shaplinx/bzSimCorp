<script setup lang="ts">
import { VueFinalModal } from 'vue-final-modal'
import Button from '../buttons/Button.vue';
import ColumnWrapper from '../columns/ColumnWrapper.vue';
import ColumnItem from '../columns/ColumnItem.vue';
import { sentenceCase } from '@/@hooks/misc/changeCase';
import { ref } from 'vue';
import AccordionWrapper from '../accordion/AccordionWrapper.vue';
import AccordionItem from '../accordion/AccordionItem.vue';
import Badge from '../badges/Badge.vue';


const props = withDefaults(defineProps<{
    title?: string,
    message?: string,
    objectName?: string,
    objects?: { [key: string]: string }[],
    confirmlabel?: string,
    cancellabel?: string,
    accordionLabel?: string,
    keyColumn?:string
}>(), {
    title: "Delete Confirmation",
    message: "Are you sure you want to delete followings :objectName?",
    objectName: "item(s)",
    confirmlabel: "Confirm",
    cancellabel: "Cancel",
    accordionLabel: "Click to view details",
    keyColumn: "id",
})

const deleting = ref(false);


const emit = defineEmits(["close","confirm"])

function onConfirm() {
    emit("confirm",deleting)
}

</script>

<template>
    <VueFinalModal class="flex justify-center items-center px-8" overlay-transition="vfm-fade"
        content-transition="vfm-fade" content-class="card max-h-[90vh] overflow-y-auto max-w-xl bg-base-100 text-base-content card-body">
        <div class="flex flex-col gap-2"> </div>
        <div class="card-title">
            {{ title }}
        </div>
        <slot>
            <p>
                {{ message.replace(/:objectName/g, objectName) }}
            </p>
            <AccordionWrapper v-if="objects?.length">
                <AccordionItem v-for="(object, key) in objects" name="delete-items" :modelValue="key === 0">
                    <template #title>{{ accordionLabel }} <Badge variant="secondary"> {{ keyColumn }} : {{ object[keyColumn] }}</Badge> </template>
                    <template #content>
                        <ColumnWrapper>
                            <ColumnItem v-for="keys in Object.keys(object)" :field="sentenceCase(keys)"
                                :value="object[keys]" />
                        </ColumnWrapper>
                    </template>
                </Accordionitem>
            </AccordionWrapper>
        </slot>
        <div class="flex justify-center sm:justify-end gap-2">
            <Button variant="error" :disabled="deleting" @click="emit('close')">
                Cancel
            </Button>
            <Button variant="success" :loading="deleting" :disabled="deleting"  @click="onConfirm">
                Confirm
            </Button>
        </div>

    </VueFinalModal>
</template>
