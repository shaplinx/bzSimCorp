<script setup lang="ts">
import Card from '@/components/cards/Card.vue';
import CardBody from '@/components/cards/CardBody.vue';
import CardTitle from '@/components/cards/CardTitle.vue';
import { FormKit, FormKitSchema } from '@formkit/vue';
import { useMessagingResources } from '@/resources';
import SpinnerOverlay from '@/components/loader/SpinnerOverlay.vue';
import CardActions from '@/components/cards/CardActions.vue';
import { useEditCrud } from '@/@hooks/crud/useEditCrud';
import ColumnWrapper from '@/components/columns/ColumnWrapper.vue';
import ColumnItem from '@/components/columns/ColumnItem.vue';
import Badge from '@/components/badges/Badge.vue';
import { quickDateFormat } from '@/@hooks/misc/useDate';


const {
    reactives,
    formButtons,
    fetchOne
} = useEditCrud<App.Models.MessagingMessage>({
    resources: useMessagingResources(),
    indexRoute: { name: "IndexMessaging" },
    formId: "",
    generateDeleteObject: (data) => {
        return {
            id: String(data.id),
            subject: data.subject,
            body: data.body,
        }
    }
})

fetchOne();

const [, ...modifiedFormButtons] = formButtons;


</script>

<template>
    <Card variant="base">
        <SpinnerOverlay :show="reactives.isFetching"></SpinnerOverlay>
        <CardBody>
            <CardTitle>{{ $route.meta.title }}</CardTitle>
            <ColumnWrapper>
                <ColumnItem>
                    <template #key>ID</template>
                    <template #value>{{ reactives.model.id }}</template>
                </ColumnItem>
                <ColumnItem>
                    <template #key>Sender</template>
                    <template #value>
                        <Badge variant="success"> {{ reactives.model.sender?.name }} </Badge>
                    </template>
                </ColumnItem>
                <ColumnItem>
                    <template #key>Recipients</template>
                    <template #value>
                        <div class="flex gaps-2">
                            <Badge v-for="recipient in reactives.model.recipients"
                                :variant="recipient.pivot?.read_at ? 'success' : 'warning'"> {{ recipient.name }}
                            </Badge>
                        </div>
                    </template>
                </ColumnItem>

                <ColumnItem>
                    <template #key>Subject</template>
                    <template #value>{{ reactives.model.subject }}</template>
                </ColumnItem>
                <ColumnItem>
                    <template #key>Body</template>
                    <template #value>{{ reactives.model.body }}</template>
                </ColumnItem>

                <ColumnItem>
                    <template #key>Sent at</template>
                    <template #value>{{ quickDateFormat(reactives.model.created_at) }}</template>
                </ColumnItem>
            </ColumnWrapper>
            <CardActions>
                <FormKit type="button" v-for="(button, key) in modifiedFormButtons" :key="key" v-bind="button"
                    v-on="button.on" :class="{ 'ml-auto': key === 1 }" :loading="reactives.isSubmitting" />
            </CardActions>
        </CardBody>
    </Card>
</template>
