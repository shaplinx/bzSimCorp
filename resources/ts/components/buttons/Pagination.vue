<template>
    <div class="flex items-center"
    :class="[
        !position || position === 'center' ? 'flex-col justify-start' : '',
        {
            'flex-row gap-2 justify-start': position === 'left',
            'flex-row-reverse gap-2 justify-start': position === 'right'
        }
    ]">
    <div class="flex gap-1">
        <Button :size="buttonSize" v-if="page > 1 && page <= total_page" @click="$emit('update:page', page-1)">
            <font-awesome-icon :icon="faChevronLeft"></font-awesome-icon>
        </Button>
        <ButtonGroupInput :model-value="page" @update:modelValue="(val) => $emit('update:page', val)"
            :buttons="buttons">
        </ButtonGroupInput>
        <Button :size="buttonSize" v-if="page < total_page && page >= 1" @click="$emit('update:page', page+1)">
            <font-awesome-icon :icon="faChevronRight"></font-awesome-icon>
        </Button>
    </div>
    <span class="my-1 text-xs"> {{ currentRange }} from {{ total }}</span>
    </div>

</template>
<script setup lang="ts">
import { faChevronLeft, faChevronRight } from "@fortawesome/free-solid-svg-icons";
import { ButtonProps } from "../@types/button";
import ButtonGroupInput from "./ButtonGroupInput.vue"
import { computed } from "vue"
import Button from "./Button.vue";


const props = defineProps<{
    page: number,
    per_page: number,
    total: number,
    buttonSize?: "xs" | "md" | "lg" | "sm",
    position?: "center" | "left" | "right"
}>()

const total_page = computed(() => {
    return Math.floor(props.total / props.per_page) === props.total / props.per_page ? props.total / props.per_page : Math.floor(props.total / props.per_page) + 1
})

const currentRange= computed(()=> {
    let min = (props.page * props.per_page) - (props.per_page-1)
    let max = min + (props.per_page-1)
    return `${min} - ${max}`
})

const buttons = computed((): ButtonProps[] => {
    if (total_page.value <= 9) {
        return Array.from({ length: total_page.value }).map((val, key) => {
            return {
                label: (key + 1).toString(),
                shape: "square",
                variant: "primary",
                value: key + 1,
                size: props.buttonSize
            }
        })
    }
    let start = [1, 2, 3]
    let middle = [props.page - 1, props.page, props.page + 1].filter(x => x > 0 && x <= total_page.value)
    let end = [total_page.value - 2, total_page.value - 1, total_page.value]
    let prev = 0
    return Array.from(new Set([...start, ...middle, ...end])).sort((a, b) => a - b).flatMap(val => {
        if (val === prev + 1) {
            prev = val
            return {
                label: (val).toString(),
                shape: "square",
                variant: "primary",
                value: val,
                size: props.buttonSize
            }
        } else {
            prev = val
            return [
                {
                    label: "...",
                    disabled: true,
                    shape: "square",
                    size: props.buttonSize

                },
                {
                    label: (val).toString(),
                    shape: "square",
                    variant: "primary",
                    value: val,
                    size: props.buttonSize
                }
            ]
        }
    })

})
</script>
