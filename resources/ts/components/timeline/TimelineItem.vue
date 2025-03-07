<template>
    <li class="mb-10 ms-6">
        <TimelineIcon :icon="icon" :variant="variant" :iconClass="iconClass"/>
        <TimelineContent :variant="variant"> <template #title>
                <slot name="title"></slot>
            </template>
            <template #default>
                <slot></slot>
            </template>
        </TimelineContent>
    </li>
</template>
<script setup lang="ts">
import TimelineIcon from "./TimelineIcon.vue";
import TimelineContent from "./TimelineContent.vue";
import type { TimelineItemProps } from "@/components/@types/timeline"

defineProps<TimelineItemProps>()
</script>
