<script setup lang="ts">
import { VueFinalModal } from "vue-final-modal";
import Button from "../buttons/Button.vue";
import { ref } from "vue";
import { getNode, type FormKitSchemaDefinition } from "@formkit/core";
import axiosInstance from "@/@hooks/api/useAxios";
import { useMessagingResources, useUserResources } from "@/resources";

const formId = "ComposeMessageForm"

const props = withDefaults(
    defineProps<{
        title?: string;
    }>(),
    {
        title: "Compose New Message",
    }
);


const emit = defineEmits(["close"]);

const formSchema: FormKitSchemaDefinition = [
    {
        $formkit: "text",
        name: "subject",
        label: "Subject",
    },
    {
        $formkit: "vSelect",
        name: "recipients",
        label: "Recipients",
        displayLabel: "name",
        placeholder: "Search users",
        object: true,
        valueProp: "id",
        mode: "tags",
        "filter-results": true,
        "min-chars": 1,
        "resolve-on-load": true,
        clearOnSearch: true,
        searchable: true,
        debounce: 250,
        options: (search: string): Promise<any[]> => {
            return useUserResources().index!({ params: { search } })
                .then((res) => res.data.data?.data ?? [])
                .catch(() => []);
        },
    },
    {
        $formkit: "textarea",
        name: "body",
        label: "Message Body",
    },
];

const isProcessing = ref(false);
const resources = useMessagingResources();
function sendMessage(data: any) {
    getNode(formId)?.clearErrors()
    isProcessing.value = true
    return new Promise((resolve, reject) => {
        resources.create?.({ 
            data,  
        })
            .then(res => {
                resolve(res)
                emit('close')
            })
            .catch((err) => {
                getNode(formId)?.setErrors(err.response.data.errors)
                reject(err)
            })
            .finally(() => isProcessing.value = false)
    })
};
function onConfirm() {
    getNode(formId)?.submit();
}
</script>

<template>
    <VueFinalModal class="flex justify-center items-center px-8" overlay-transition="vfm-fade"
        content-transition="vfm-fade" content-class="card max-w-xl bg-base-100 text-base-content card-body">
        <div class="flex flex-col gap-2"></div>
        <div class="card-title">
            {{ title }}
        </div>
        <slot>
            <FormKit :disabled="isProcessing" @submit="sendMessage" type="form" :actions="false" id="ComposeMessageForm">
                <FormKitSchema :schema="formSchema"></FormKitSchema>
            </FormKit>
        </slot>
        <div class="flex justify-center sm:justify-end gap-2">
            <Button variant="error" :disabled="isProcessing" @click="emit('close')">
                Cancel
            </Button>
            <Button variant="success" :loading="isProcessing" :disabled="isProcessing" @click="onConfirm">
                Send
            </Button>
        </div>
    </VueFinalModal>
</template>
