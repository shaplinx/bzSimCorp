<template>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold">
            {{ $route.meta.title }}
        </h2>
        <div>
            <ButtonGroup :buttons="buttons ?? []" container-class="w-full" buttonClass="flex-grow sm:flex-none">
                <template #button-actions="{ button }">
                    <DropdownMenu v-if="actions?.length" placement="bottom-end" :options="actions">
                        <Button v-bind="button" class="join-item flex-grow sm:flex-none" />
                    </DropdownMenu>
                </template>
            </ButtonGroup>
        </div>
    </div>
</template>
<script setup lang=ts>
import { ButtonProps } from '../@types/button';
import { DropdownOption } from '../dropdowns/dropdown';
import ButtonGroup from "@/components/buttons/ButtonGroup.vue";
import Button from "@/components/buttons/Button.vue";
import DropdownMenu from "@/components/dropdowns/DropdownMenu.vue";


defineProps<{
    buttons?: ButtonProps[]
    actions?: DropdownOption[]
}>()
</script>
