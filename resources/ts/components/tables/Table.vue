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
                            @click="updateSortTableField(field, field.sortValue ?? field.value, sort.direction)">
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
                                    <FontAwesomeIcon v-if="(sort.direction === 'desc' && sort.column === (field.sortValue ?? field.value))" :icon="faCaretDown"/>
                                    <FontAwesomeIcon  v-if="(sort.direction === 'asc' && sort.column === (field.sortValue ?? field.value))" :icon="faCaretUp"/>
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
                            {{isSelected(item, index)}}
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
import { faCaretUp, faCaretDown } from '@fortawesome/free-solid-svg-icons';


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
    column: props.sort?.column,
    direction: props.sort?.direction
})

watchDeep(() => props.sort, () => {
    sort.column = props.sort?.column
    sort.direction = props.sort?.direction
})

function updateSortTableField(field: TableField, newcolumn: string, olddirection?: "asc" | "desc" | "DESC" | "ASC") {
    if (!field.sortable) return
    let directions: ("asc" | "desc" | "DESC" | "ASC" | undefined)[] = [undefined, 'asc', 'desc']
    let newdirection = (sort.column === newcolumn) ?
        directions[(directions.indexOf(olddirection) + 1) % 3] :
        "asc"

    if (newcolumn && newdirection) {
        sort.column = newcolumn
        sort.direction = newdirection
    } else {
        sort.column = undefined
        sort.direction = undefined
    }
    emits("update:sort", sort)
}

function sortData(data: Data[], key: string, order: "asc" | "desc" | "DESC" | "ASC"): Data[] {
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
    if (!sort.column || !sort.direction) return props.data
    return sortData(props.data,sort.column,sort.direction)

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
        console.log(selected.value)
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
        selected.value = [selectedKey]
    }
    emits("update:selected", selectedObject.value);

}


</script>
