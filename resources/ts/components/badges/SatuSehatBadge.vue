<template>
    <Badge v-bind="badgeProps">
        <SatuSehatSvg :class="iconClass ?? 'h-3 w-3'"></SatuSehatSvg>
    </Badge>
</template>
<script setup lang="ts">
import { BadgeProps } from '../@types/badge';
import Badge from './Badge.vue';
import SatuSehatSvg from './SatuSehatSvg.vue';

defineProps<{
    badgeProps?:BadgeProps,
    iconClass?:string
}>()
</script>
