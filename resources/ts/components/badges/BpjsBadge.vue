<template>
    <Badge v-bind="badgeProps">
        <BpjsSvg :class="iconClass ?? 'h-3 w-3'"></BpjsSvg>
    </Badge>
</template>
<script setup lang="ts">
import { BadgeProps } from '../@types/badge';
import Badge from './Badge.vue';
import BpjsSvg from './BpjsSvg.vue';

defineProps<{
    badgeProps?:BadgeProps,
    iconClass?:string
}>()
</script>
