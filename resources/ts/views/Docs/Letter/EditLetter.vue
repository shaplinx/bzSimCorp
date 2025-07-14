<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import { useLetterFormSchema } from '@/forms/schemas/documents/letterFormSchema';
import { useLetterResources } from '@/resources';
import SpinnerOverlay from '@/components/loader/SpinnerOverlay.vue';
import CardActions from '@/components/cards/CardActions.vue';
import { FormKit, FormKitSchema } from '@formkit/vue';
import { useEditCrud } from '@/@hooks/crud/useEditCrud';
import { faDownload } from '@fortawesome/free-solid-svg-icons';
import { computed } from 'vue';
import IframeModal from '@/components/containers/IframeModal.vue';
import { useModal } from 'vue-final-modal';


const formSchema = useLetterFormSchema({ route: 'edit' });
const letterResource = useLetterResources();

const {
    reactives,
    formButtons,
    updateSubmit,
    fetchOne
} = useEditCrud<App.Models.DocumentsLetter>({
    resources: letterResource,
    indexRoute: { name: 'IndexLetter' },
    formId: 'EditLetterForm',
    updateRequestConf: { params: { _method: "PATCH" }, method: "POST" },
    proccessUpdateData: (data: any) => {
        const formData = new FormData()

        Object.entries(data).forEach(([key, value]: any) => {
            if (value && key !== "file" && key !== "institution" && key !== "classification") {
                formData.append(key, value);
            }
        });

        if (data.institution) {
            formData.append("institution_id", data.institution.id);
        }
        if (data.institution) {
            formData.append("classification_id", data.classification.id);
        }

        if (data.file[0]?.file) {
            formData.append("file", data.file[0].file);
        }
        return formData
    },
    generateDeleteObject: (data) => {
        return {
            id: String(data.id),
            serial_number: String(data.sn) ?? "",
            subject: data.subject,
            recipient: data.recipient ?? "",
            letter_date: data.letter_date,
        };
    },
});

const overridedFormButtons = computed(() => [
    formButtons[0],
    formButtons[1],
    {
        variant: "accent",
        disabled: !reactives.model.file_path,
        icon: faDownload,
        label: "Download Attached File",
        on: {
            click: () => {
                return letterResource.download(reactives.model?.id)
                    .then((file: string) => {
                        const { open, close } = useModal({
                            component: IframeModal,
                            attrs: {
                                pdfUrl: file,
                                onClose() {
                                    close()
                                },
                            }
                        })
                        open()
                    })
            }
        }
    },
    formButtons[2],
])



fetchOne();
</script>

<template>
    <Card variant="base">
        <SpinnerOverlay :show="reactives.isFetching" />
        <CardBody>
            <CardTitle>{{ $route.meta.title }}</CardTitle>
            <FormKit :disabled="reactives.isSubmitting" type="form" @submit="updateSubmit" :actions="false"
                id="EditLetterForm">
                <FormKitSchema :schema="formSchema" :data="reactives.model" />
            </FormKit>
            <CardActions>
                <FormKit type="button" v-for="(button, key) in overridedFormButtons" :key="key" v-bind="button"
                    v-on="button.on" :class="{ 'ml-auto': key === overridedFormButtons.length - 1 }"
                    :loading="reactives.isSubmitting" />
            </CardActions>
        </CardBody>
    </Card>
</template>
