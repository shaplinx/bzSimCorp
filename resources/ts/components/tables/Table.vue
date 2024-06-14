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
            <table class="table " :class="{'table-zebra':striped}">
                <thead>
                    <tr>
                        <th v-if="selectMode === 'multiple' || selectMode === 'single' " class="w-14">
                            <div>
                                <input id="contact-selectAll" :disabled ="selectMode !== 'multiple'" class="checkbox" type="checkbox" v-model="isSelectedAll">
                            </div>
                        </th>
                        <th v-for="(field, idx) in fields" :key="idx">
                            <span v-if="!hasNamedSlot(`header-${field.value}`)" :item="field">
                                {{ field.label }}
                            </span>
                            <slot v-else :name="`header-${field.value}`" :item="field" />
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in data" :key="index" :class="{'hover':hover}">
                        <td v-if="selectMode === 'multiple' || selectMode === 'single'" class="w-14">
                            <div>
                                <input :id="`contact-${index}`" @change="rowSelected(item)" :checked="isSelected(item)" class="checkbox" type="checkbox">
                            </div>
                        </td>
                        <td v-for="(field, idx) in fields" @click="rowSelected(item)" :key="idx">
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
import { getCurrentInstance, PropType, ref, watch,computed } from 'vue'

type Field = {
    label: string
    value: string
    sortable?: boolean
    fixed?: boolean
    width?: number
}

type Data = {
    [key: string]: any
}

type TableProps = {
    data:Data[],
    header?: {
        title: string,
        description:string
    },
    fields:Field[],
    selectMode?: "single"| "multiple"
    selected?: any | any[],
    keyField?: string,
    striped?: boolean,
    hover?: boolean
}

const props = withDefaults(defineProps<TableProps>(),
{
    data: () => [],
    fields:() =>[],
    selected: () => [],
    keyField: "id",
}
);



const emits = defineEmits(["rowSelected","update:selected"]);

const instance = getCurrentInstance();

const isSelectedAll = computed({
    set(val :boolean) {
        let selected = val ? props.data.map(x=> x[props.keyField]) : [];
        emits("update:selected", selected);
    },
    get() {
        return props.data.every(data => {
            return isSelected(data);
        })
    }
});

function hasNamedSlot(slotName: any) {
    return Object.prototype.hasOwnProperty.call(instance?.slots, slotName);
}

function isSelected(item :Data) {
    if (!props.selectMode) return false
    if (props.selectMode === "single") {
        return props.selected === item[props.keyField];
    }
    return props.selected.includes(item[props.keyField])
}

function rowSelected(item :Data) {
    if (!props.selectMode) return
    let selected
    if (props.selectMode === "multiple") {
        selected = isSelected(item) ? props.selected.filter((x :Data)=> x !== item[props.keyField]): [...props.selected, item[props.keyField]];
    }
    else {
        selected = item[props.keyField]
    }
    emits("update:selected", selected);

}
</script>
