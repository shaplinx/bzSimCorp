<template>
    <div>
        <div>
            <div v-if="header">
                <h2>
                    {{ header.title }}
                </h2>
                <p>
                    {{ header.description }}
                </p>
            </div>
            <table class="table " :class="{ 'table-zebra': striped }">
                <thead>
                    <tr>
                        <th v-if="selectMode === 'multiple' || selectMode === 'single'" class="w-14">
                            <div>
                                <input id="contact-selectAll" :disabled="selectMode !== 'multiple'" class="checkbox"
                                    type="checkbox" v-model="isSelectedAll">
                            </div>
                        </th>
                        <th v-for="(field, idx) in fields" :key="idx" :class="{ 'cursor-pointer': field.sortable }"
                            @click="updateSortTableField(field, field.sortValue ?? field.value, sort.sortType)">
                            <div class="flex gap-2 items-center" :class="{
                                'justify-start': field.align === 'left',
                                'justify-center': field.align === 'center',
                                'justify-end': field.align === 'right',
                            }">
                                <span v-if="!hasNamedSlot(`header-${field.value}`)" :item="field">
                                    {{ field.label }}
                                </span>
                                <slot v-else :name="`header-${field.value}`" :item="field" />
                                <span class="w-4 h-4" v-if="field.sortable">
                                    <svg v-if="(sort.sortType === 'desc' && sort.sortBy === (field.sortValue ?? field.value))"
                                        viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M24.2441 18.373L21.293 21.3232L21.3125 11C21.3125 10.4473 20.8652 10 20.3125 10C19.7617 10 19.3125 10.4473 19.3125 11L19.293 21.2529L16.4141 18.373C16.0234 17.9834 15.3906 17.9834 15 18.373C14.6094 18.7637 14.6094 19.3975 15 19.7881L19.1152 23.9033C19.3887 24.2656 19.8242 24.5 20.3125 24.5C20.6934 24.5 21.0391 24.3594 21.3027 24.127C21.3418 24.0977 21.3789 24.0654 21.4141 24.0303L25.6582 19.7881C26.0488 19.3975 26.0488 18.7637 25.6582 18.373C25.2676 17.9834 24.6348 17.9834 24.2441 18.373Z"
                                            fill="#000000" />
                                        <path
                                            d="M2 20.7002C2 21.1416 2.35742 21.5 2.80078 21.5H10.6992C10.8906 21.5 11.0664 21.4336 11.2031 21.3223C11.3848 21.1758 11.5 20.9512 11.5 20.7002V20.2998C11.5 19.8584 11.1426 19.5 10.6992 19.5H2.80078C2.56836 19.5 2.35938 19.5977 2.21289 19.7549C2.13086 19.8418 2.07031 19.9473 2.03516 20.0645C2.01172 20.1387 2 20.2178 2 20.2998V20.7002Z"
                                            fill="#000000" />
                                        <path
                                            d="M2.50586 13.4434C2.42969 13.4131 2.35938 13.3721 2.29688 13.3213C2.11523 13.1748 2 12.9512 2 12.7002V12.2998C2 11.8584 2.35742 11.5 2.80078 11.5H15.1992C15.6426 11.5 16 11.8584 16 12.2998V12.7002C16 13.1416 15.6426 13.5 15.1992 13.5H2.80078C2.69727 13.5 2.59766 13.4795 2.50586 13.4434Z"
                                            fill="#000000" />
                                        <path
                                            d="M2.07422 5.03516C2.02734 4.93359 2 4.82031 2 4.7002V4.2998C2 4.04492 2.11914 3.81738 2.30469 3.6709C2.44141 3.56348 2.61328 3.5 2.80078 3.5H24.1992C24.6426 3.5 25 3.8584 25 4.2998V4.7002C25 5.1416 24.6426 5.5 24.1992 5.5H2.80078C2.47852 5.5 2.20117 5.30957 2.07422 5.03516Z"
                                            fill="#000000" />
                                    </svg>
                                    <svg v-if="(sort.sortType === 'asc' && sort.sortBy === (field.sortValue ?? field.value))"
                                        viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M24.2441 9.62695L21.293 6.67676L21.3125 17C21.3125 17.5527 20.8652 18 20.3125 18C19.7617 18 19.3125 17.5527 19.3125 17L19.293 6.74707L16.4141 9.62695C16.0234 10.0166 15.3906 10.0166 15 9.62695C14.6094 9.23633 14.6094 8.60254 15 8.21191L19.1152 4.09668C19.3887 3.73438 19.8242 3.5 20.3125 3.5C20.6934 3.5 21.0391 3.64062 21.3027 3.87305C21.3418 3.90234 21.3789 3.93457 21.4141 3.96973L25.6582 8.21191C26.0488 8.60254 26.0488 9.23633 25.6582 9.62695C25.2676 10.0166 24.6348 10.0166 24.2441 9.62695Z"
                                            fill="#000000" />
                                        <path
                                            d="M2 7.2998C2 6.8584 2.35742 6.5 2.80078 6.5H10.6992C10.8906 6.5 11.0664 6.56641 11.2031 6.67773C11.3848 6.82422 11.5 7.04883 11.5 7.2998V7.7002C11.5 8.1416 11.1426 8.5 10.6992 8.5H2.80078C2.56836 8.5 2.35938 8.40234 2.21289 8.24512C2.13086 8.1582 2.07031 8.05273 2.03516 7.93555C2.01172 7.86133 2 7.78223 2 7.7002V7.2998Z"
                                            fill="#000000" />
                                        <path
                                            d="M2.50586 14.5566C2.42969 14.5869 2.35938 14.6279 2.29688 14.6787C2.11523 14.8252 2 15.0488 2 15.2998V15.7002C2 16.1416 2.35742 16.5 2.80078 16.5H15.1992C15.6426 16.5 16 16.1416 16 15.7002V15.2998C16 14.8584 15.6426 14.5 15.1992 14.5H2.80078C2.69727 14.5 2.59766 14.5205 2.50586 14.5566Z"
                                            fill="#000000" />
                                        <path
                                            d="M2.07422 22.9648C2.02734 23.0664 2 23.1797 2 23.2998V23.7002C2 23.9551 2.11914 24.1826 2.30469 24.3291C2.44141 24.4365 2.61328 24.5 2.80078 24.5H24.2767C24.7201 24.5 25.0775 24.1416 25.0775 23.7002V23.2998C25.0775 22.8584 24.7201 22.5 24.2767 22.5H2.80078C2.47852 22.5 2.20117 22.6904 2.07422 22.9648Z"
                                            fill="#000000" />
                                    </svg>

                                </span>
                            </div>


                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in dataForRender" :key="index" :class="{ 'hover': hover }">
                        <td v-if="selectMode === 'multiple' || selectMode === 'single'" class="w-14">
                            <div>
                                <input :id="`contact-${index}`" @change="rowSelected(item, index)"
                                    :checked="isSelected(item, index)" class="checkbox" type="checkbox">
                            </div>
                        </td>
                        <td v-for="(field, idx) in fields" @click="rowSelected(item, index)" :key="idx">
                            <span v-if="!hasNamedSlot(`item-${field.value}`)" :item="item">
                                {{ item[field.value] }}
                            </span>
                            <slot v-else :name="`item-${field.value}`" :item="item" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="hasNamedSlot('footer')">
            <slot name="footer" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { watchDeep } from '@vueuse/core';
import { getCurrentInstance, PropType, ref, watch, computed, reactive } from 'vue'
import { Data, TableField, TableProps } from '../@types/table';

const props = withDefaults(defineProps<TableProps>(),
    {
        data: () => [],
        fields: () => [],
        selected: () => [],
    }
);

const emits = defineEmits(["rowSelected", "update:selected", "update:sort"]);

const instance = getCurrentInstance();

function hasNamedSlot(slotName: any) {
    return Object.prototype.hasOwnProperty.call(instance?.slots, slotName);
}

const uniqueKey = computed(() => {
    return props.fields.find((d: TableField) => d.unique)?.value
})

const sort = reactive({
    sortBy: props.sort?.sortBy,
    sortType: props.sort?.sortType
})

watchDeep(() => props.sort, () => {
    sort.sortBy = props.sort?.sortBy
    sort.sortType = props.sort?.sortType
})

function updateSortTableField(field: TableField, newSortBy: string, oldSortType?: "asc" | "desc") {
    if (!field.sortable) return
    let sortTypes: ("asc" | "desc" | undefined)[] = [undefined, 'asc', 'desc']
    let newSortType = (sort.sortBy === newSortBy) ?
        sortTypes[(sortTypes.indexOf(oldSortType) + 1) % 3] :
        "asc"

    if (newSortBy && newSortType) {
        sort.sortBy = newSortBy
        sort.sortType = newSortType
    } else {
        sort.sortBy = undefined
        sort.sortType = undefined
    }
    emits("update:sort", sort)
}

function sortData(data: Data[], key: string, order: 'asc' | 'desc'): Data[] {
    function getNestedValue(obj: Data, key: string): any {
        return key.split('.').reduce((acc, part) => acc && acc[part], obj);
    }
    data = JSON.parse(JSON.stringify(data))
    return data.sort((a, b) => {
        const valA = getNestedValue(a, key);
        const valB = getNestedValue(b, key);

        if (valA < valB) {
            return order === 'asc' ? -1 : 1;
        } else if (valA > valB) {
            return order === 'asc' ? 1 : -1;
        } else {
            return 0;
        }
    });
}

const dataForRender = computed(() => {
    if (props.serverSide) return props.data
    if (!sort.sortBy || !sort.sortType) return props.data
    return sortData(props.data,sort.sortBy,sort.sortType)

})

function generateSelectedRef(uniqueKey?: any) {
    if (!uniqueKey) {
        let stringifyData = dataForRender.value.map((d: Data) => JSON.stringify(d))
        return props.selected.map((x: Data) => stringifyData.indexOf(JSON.stringify(x)))
    }
    return props.selected.map((x: Data) => x[uniqueKey])
}

const selected = ref(generateSelectedRef(uniqueKey.value))

watch(props.selected, () => {
    selected.value = generateSelectedRef(uniqueKey.value)
})

const selectedObject = computed(() => {
    return selected.value.map(
        (d: any) => uniqueKey.value ?
            dataForRender.value.find((x: Data) => x[uniqueKey.value!] === d) :
            dataForRender.value[d]
    )
})


const isSelectedAll = computed({
    set(val: boolean) {
        selected.value = val ? dataForRender.value.map((x, i) => uniqueKey.value ? x[uniqueKey.value] : i) : [];
        emits("update:selected", selectedObject.value);
    },
    get() {
        return dataForRender.value.every((data, i) => {
            return isSelected(data, i);
        })
    }
});

function isSelected(item: Data, index: number) {
    if (!props.selectMode) return false
    if (props.selectMode === "single") {
        return selected.value === item[uniqueKey.value ? item[uniqueKey.value] : index];
    }
    return selected.value.includes(uniqueKey.value ? item[uniqueKey.value] : index)
}

function rowSelected(item: Data, index: number) {
    if (!props.selectMode) return
    let selectedKey = uniqueKey.value ? item[uniqueKey.value] : index
    if (props.selectMode === "multiple") {
        selected.value = isSelected(item, selectedKey) ?
            selected.value.filter((x: any) => x !== selectedKey) :
            [...selected.value, selectedKey];
    }
    else {
        selected.value = selectedKey
    }
    emits("update:selected", selectedObject.value);

}


</script>
