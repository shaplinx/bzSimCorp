<template>
    <!-- eslint-disable @typescript-eslint/no-explicit-any -->
    <Card variant="base" class="overflow-hidden shadow-md">
        <div>
            <SpinnerOverlay :show="isLoading"></SpinnerOverlay>
            <div class="overflow-x-auto h-full w-full overflox-y-hidden">
                <table class="table table-zebra overflow-auto" ref="localTable">
                    <thead class="rounded-t-box">
                        <tr>
                            <th v-if="hasCheckbox">
                                <div>
                                    <input type="checkbox" class="checkbox" :indeterminate="setting.isIndeterminate"
                                        v-model="setting.isCheckAll" />
                                </div>
                            </th>
                            <th v-for="(col, index) in (columns as Array<any>)" :key="index" :style="Object.assign(
                                {
                                    width: col.width ? col.width : 'auto',
                                },
                                col.headerStyles
                            )
                                ">
                                <div :class="{
                                    'vtl-sortable': col.sortable,
                                    'vtl-both': col.sortable,
                                    'vtl-asc': setting.order === col.field && setting.sort === 'asc',
                                    'vtl-desc': setting.order === col.field && setting.sort === 'desc',
                                }" @click.prevent="col.sortable ? doSort(col.sortField ?? col.field) : false">
                                    <span v-if="!setting.isSlotMode || !slots[`column-${col.field}`]" :item="col">
                                        {{ col.label }}
                                    </span>
                                    <slot v-else :name="`column-${col.field}`" :item="col" />
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <template v-if="rows.length > 0">

                        <tbody v-if="isStaticMode" :set="(templateRows = groupingKey == '' ? [localRows] : localRows)">
                            <template v-for="(rows, groupingIndex) in templateRows" :key="groupingIndex">
                                <tr v-if="groupingKey != ''">
                                    <td :colspan="hasCheckbox ? columns.length + 1 : columns.length">
                                        <div class="flex">
                                            <div v-if="hasGroupToggle" class="animation">
                                                <a :ref="(el: any) => (toggleButtonRefs[groupingIndex] as any) = el"
                                                    class="cursor-pointer"
                                                    @click.prevent="toggleGroup(groupingIndex.toString())">▼</a>
                                            </div>
                                            <div class="ml-2" v-html="groupingDisplay
                                                    ? groupingDisplay(groupingIndex)
                                                    : groupingIndex
                                                "></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(row, i) in rows" :key="row[setting.keyColumn] ? row[setting.keyColumn] : i"
                                    :ref="(el: any) => {
                                            if (!groupingRowsRefs   [groupingIndex]) {
                                                groupingRowsRefs[groupingIndex] = [];
                                            }
                                            groupingRowsRefs[groupingIndex][i] = el;
                                        }
                                        " :name="'vtl-group-' + groupingIndex" :class="typeof rowClasses === 'function' ? rowClasses(row) : rowClasses
                                " @mouseenter="addHoverClassToTr" @mouseleave="removeHoverClassFromTr"
                                    @click="$emit('row-clicked', row)">
                                    <td v-if="hasCheckbox">
                                        <div>

                                            <input type="checkbox" class="checkbox" :ref="(el: any) => {
                                                    (rowCheckbox as Array<HTMLElement>).push(el);
                                                }
                                                " :value="row[setting.keyColumn]" :checked="isChecked.includes(row[setting.keyColumn])" @click="checked(row, $event)" />
                                        </div>
                                    </td>
                                    <td v-for="(col, j) in (columns as Array<any>)" :key="j" :class="col.columnClasses"
                                        :style="col.columnStyles" @mouseover="addVerticalHighlight(j)"
                                        @mouseleave="removeVerticalHighlight(j)">
                                        <div v-if="col.display" v-html="col.display(row)"></div>
                                        <div v-else>
                                            <div v-if="setting.isSlotMode && slots[`row-${col.field}`]">
                                                <slot :name="`row-${col.field}`" :value="row"></slot>
                                            </div>
                                            <span v-else>{{ row[col.field] }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tbody v-else :set="(templateRows = groupingKey == '' ? [rows] : groupingRows)">
                            <template v-for="(rows, groupingIndex) in templateRows" :key="groupingIndex">
                                <tr v-if="groupingKey != ''">
                                    <td :colspan="hasCheckbox ? columns.length + 1 : columns.length">
                                        <div class="flex">
                                            <div v-if="hasGroupToggle" class="animation">
                                                <a :ref="(el: any) => (toggleButtonRefs[groupingIndex] as any) = el"
                                                    class="cursor-pointer"
                                                    @click.prevent="toggleGroup(groupingIndex.toString())">▼</a>
                                            </div>
                                            <div class="ml-2" v-html="groupingDisplay
                                                    ? groupingDisplay(groupingIndex)
                                                    : groupingIndex
                                                "></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(row, i) in rows" :ref="(el: any) => {
                                        if (!groupingRowsRefs[groupingIndex]) {
                                            groupingRowsRefs[groupingIndex] = [];
                                        }
                                        groupingRowsRefs[groupingIndex][i] = el;
                                    }
                                    " :name="'vtl-group-' + groupingIndex"
                                    :key="row[setting.keyColumn] ? row[setting.keyColumn] : i" :class="typeof rowClasses === 'function' ? rowClasses(row) : rowClasses
                                        " @mouseenter="addHoverClassToTr" @mouseleave="removeHoverClassFromTr"
                                    @click="$emit('row-clicked', row)">
                                    <td v-if="hasCheckbox">
                                        <div>
                                            <input type="checkbox" class="checkbox"
                                                :ref="(el: any) => (rowCheckbox as Array<HTMLElement>).push(el)"
                                                :value="row[setting.keyColumn]" @click="checked(row, $event)" :checked="isChecked.includes(row[setting.keyColumn])"/>
                                        </div>
                                    </td>
                                    <td v-for="(col, j) in (columns as Array<any>)" :key="j" :class="col.columnClasses"
                                        :style="col.columnStyles" @mouseover="addVerticalHighlight(j)"
                                        @mouseleave="removeVerticalHighlight(j)">
                                        <div v-if="col.display" v-html="col.display(row)"></div>
                                        <div v-else>
                                            <div v-if="setting.isSlotMode && slots[`row-${col.field}`]">
                                                <slot :name="`row-${col.field}`" :value="row"></slot>
                                            </div>
                                            <span v-else>{{ row[col.field] }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </template>
                </table>
            </div>

        </div>
        <div v-if="rows.length > 0">
            <div class="flex flex-col gap-1 sm:flex-row justify-between mx-2 my-3 text-sm  items-center" v-if="!setting.isHidePaging">
                <div>
                    {{
                        stringFormat(messages.pagingInfo, setting.offset, setting.limit, total)
                    }}
                </div>
                <div>
                    <span>{{ messages.pageSizeChangeLabel }} </span>
                    <select class="select mx-1 select-xs select-bordered" v-model="setting.pageSize">
                        <option v-for="pageOption in (setting.pageOptions as Array<pageOption>)" :value="pageOption.value"
                            :key="pageOption.value">
                            {{ pageOption.text }}
                        </option>
                    </select>
                    <span>{{ messages.gotoPageLabel }} </span>
                    <select class="select mx-1 select-xs select-bordered" v-model="setting.page">
                        <option v-for="n in setting.maxPage" :key="n" :value="parseInt(n.toString())">
                            {{ n }}
                        </option>
                    </select>
                </div>
                <div class="vtl-paging-pagination-div col-sm-12 col-md-4">
                    <div class="dataTables_paginate">
                        <ul class="join join-horizontal">
                            <button  @click.prevent="setting.page = 1" class="btn btn-sm join-item" :class="{ disabled: setting.page <= 1 }">
                                <a class="text-xs"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">First</span>
                                </a>
                            </button>
                            <button @click.prevent="prevPage" class="btn btn-sm join-item" :class="{ disabled: setting.page <= 1 }">
                                <a class="text-xs"
                                    aria-label="Previous" >
                                    <span aria-hidden="true">&lt;</span>
                                    <span class="sr-only">Prev</span>
                                </a>
                            </button>
                            <button  @click.prevent="movePage(n)" class="btn btn-sm join-item" v-for="n in setting.paging" :key="n"
                                :class="{ 'btn-primary disabled:bg-primary disabled:text-primary-content': setting.page === n }"
                                :disabled="setting.page === n">
                                <a class="text-xs"
                                   >{{ n }} </a>
                            </button>
                            <button @click.prevent="nextPage" class="btn btn-sm join-item" :class="{ disabled: setting.page >= setting.maxPage }">
                                <a class="text-xs"
                                    aria-label="Next" >
                                    <span aria-hidden="true">&gt;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </button>
                            <button   @click.prevent="setting.page = setting.maxPage" class="btn btn-sm join-item" :class="{ disabled: setting.page >= setting.maxPage }">
                                <a class="text-xs"
                                    aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Last</span>
                                </a>
                            </button>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="vtl-row" v-else>
            <div class="vtl-empty-msg col-sm-12 text-center py-8">
               {{ messages.noDataAvailable }}
            </div>
        </div>
    </Card>

    <!-- eslint-disable @typescript-eslint/no-explicit-any -->
</template>
<script lang="ts">
import {
    defineComponent,
    ref,
    reactive,
    computed,
    watch,
    onBeforeUpdate,
    nextTick,
    onMounted,
    PropType
} from "vue";
import SpinnerOverlay from "../loader/SpinnerOverlay.vue";
import Card from "../cards/Card.vue";
import CardBody from "../cards/CardBody.vue";
import { watchDeep } from "@vueuse/core";
import { faBan } from "@fortawesome/free-solid-svg-icons";


interface pageOption {
    value: number;
    text: number | string;
}

interface tableSetting {
    isSlotMode: boolean;
    isCheckAll: boolean;
    isIndeterminate: boolean;
    isHidePaging: boolean;
    keyColumn: string;
    page: number;
    pageSize: number;
    maxPage: number;
    offset: number;
    limit: number;
    paging: Array<number>;
    order: string;
    sort: string;
    pageOptions: Array<pageOption>;
    isVerticalHighlight: boolean;
}

interface column {
    isKey: string;
    field: string;
    sortField:string
}

export default defineComponent({
    name: "my-table",
    components: { SpinnerOverlay, Card, CardBody },
    emits: {
        // eslint-disable-next-line
        "update:selected": (_rows: any[]) => true,
        // eslint-disable-next-line
        "do-search": (_page: number, _per_page: number, _order: string, _sort: string) => true,
        // eslint-disable-next-line
        "is-finished": (_elements: HTMLCollectionOf<Element>) => true,
        // eslint-disable-next-line
        "get-now-page": (_pageNo: number) => true,
        // eslint-disable-next-line
        "row-clicked": (_row: any) => true,
        // eslint-disable-next-line
        "row-toggled": (_rows: any[], _isCollapsed: boolean) => true,
        "update:settings": (settings :any)=>true
    },
    props: {
        selected: {
            default : () => [],
            type: Array,
            require: false
        },
        settings: {
            type: Object as PropType<{
                pageSize: number,
                page: number,
                orderBy: {
                    column: string
                    direction: "asc" | "desc"
                }
                [string:string] : any
            }>,
            require:false,
            default: () => {
                return {
                    pageSize: 10,
                    page: 10,
                    orderBy: {
                        column: "id",
                        direction: "asc"
                }}
            }
        },
        // 是否讀取中 (is data loading)
        isLoading: {
            type: Boolean,
            require: true,
        },
        // 是否執行了重新查詢 (Whether to perform a re-query)
        isReSearch: {
            type: Boolean,
            require: true,
        },
        // 有無Checkbox (Presence of Checkbox)
        hasCheckbox: {
            type: Boolean,
            default: false,
        },
        // Checkbox勾選後返回資料的型態 (Returns data type for checked of Checkbox)
        checkedReturnType: {
            type: String,
            default: "key",
        },
        // 標題 (title)
        title: {
            type: String,
            default: "",
        },
        // 是否鎖定第一欄位位置 (Fixed first column's position)
        isFixedFirstColumn: {
            type: Boolean,
            default: false,
        },
        // 欄位 (Field)
        columns: {
            type: Array,
            default: () => {
                return [];
            },
        },
        // 資料 (data)
        rows: {
            type: Array,
            default: () => {
                return [];
            },
        },
        // 資料列類別 (data row classes)
        rowClasses: {
            type: [Array, Function],
            default: () => {
                return [];
            },
        },
        // 一頁顯示筆數 (Display the number of items on one page)
        pageSize: {
            type: Number,
            default: 10,
        },
        // 總筆數 (Total number of transactions)
        total: {
            type: Number,
            default: 100,
        },
        // 現在頁數 (Current page number)
        page: {
            type: Number,
            default: 1,
        },
        // 排序條件 (Sort condition)
        sortable: {
            type: Object,
            default: () => {
                return {
                    order: "id",
                    sort: "asc",
                };
            },
        },
        // 顯示文字 (Display text)
        messages: {
            type: Object,
            default: () => {
                return {
                    pagingInfo: "Showing {0}-{1} of {2}",
                    pageSizeChangeLabel: "Row count:",
                    gotoPageLabel: "Go to page:",
                    noDataAvailable: "No data",
                };
            },
        },
        // 靜態模式 (Static mode(no refresh server data))
        isStaticMode: {
            type: Boolean,
            default: false,
        },
        // 插槽模式 (V-slot mode)
        isSlotMode: {
            type: Boolean,
            default: false,
        },
        // 是否隱藏換頁資訊 (Hide paging)
        isHidePaging: {
            type: Boolean,
            default: false,
        },
        // 一頁顯示筆數下拉選單 (Dropdown of Display the number of items on one page)
        pageOptions: {
            type: Array,
            default: () => [
                {
                    value: 10,
                    text: 10,
                },
                {
                    value: 25,
                    text: 25,
                },
                {
                    value: 50,
                    text: 50,
                },
            ],
        },
        // 分類條件 (Key of grouping)
        groupingKey: {
            type: String,
            default: "",
        },
        // 是否顯示隱藏分類開關 (Has hide group rows toggle)
        hasGroupToggle: {
            type: Boolean,
            default: false,
        },
        // 客製化分類顯示 (Customize grouping title)
        groupingDisplay: {
            type: Function,
            default: null,
        },
        // 設定表格高度 (Table's max height)
        maxHeight: {
            default: "auto",
        },
        // 預設群組顯示時為折疊 (Grouping collapsed on start)
        startCollapsed: {
            type: Boolean,
            default: false,
        },
        // 保持刷新後折疊狀態 (Keep collapsed status after refresh)
        isKeepCollapsed: {
            type: Boolean,
            default: false,
        },
        // 選擇行是否高亮（Vertical highlight switch）
        isVerticalHighlight: {
            type: Boolean,
            default: false,
        },
    },
    setup(props, { emit, slots }) {
        let localTable = ref<HTMLElement | null>(null);

        //(Validate dropdown's values have page-size value or not)
        let tmpPageOptions = props.pageOptions as Array<pageOption>;

        let defaultPageSize =ref(props.settings.pageSize);

        if (tmpPageOptions.length > 0 && tmpPageOptions.filter(x => x.value === defaultPageSize.value).length) {
            tmpPageOptions.forEach((v: pageOption) => {
                if (
                    Object.prototype.hasOwnProperty.call(v, "value") &&
                    Object.prototype.hasOwnProperty.call(v, "text") &&
                    props.pageSize == v.value
                ) {
                    defaultPageSize.value = v.value;
                }
            });
        }

        //(Internal set value for components)
        const setting: tableSetting = reactive({
            // (Enable slot mode)
            isSlotMode: props.isSlotMode,
            // (Whether to select all)
            isCheckAll: false,
            // (checkbox indeterminate state)
            isIndeterminate: false,
            // (Hide paging)
            isHidePaging: props.isHidePaging,
            // (KEY field name)
            keyColumn: computed(() => {
                let key = "";
                Object.assign(props.columns).forEach((col: column) => {
                    if (col.isKey) {
                        key = col.field;
                    }
                });

                return key;
            }),
            //(current page number)
            page: props.settings.page,
            //(Display count per page)
            pageSize: defaultPageSize.value,
            //(Maximum number of pages)
            maxPage: computed(() => {
                if (props.total <= 0) {
                    return 0;
                }
                let maxPage = Math.floor(props.total / setting.pageSize);
                let mod = props.total % setting.pageSize;
                if (mod > 0) {
                    maxPage++;
                }
                return maxPage;
            }),
            // (The starting value of the page number)
            offset: computed(() => {
                return (setting.page - 1) * setting.pageSize + 1;
            }),
            //(Maximum number of pages0
            limit: computed(() => {
                let limit = setting.page * setting.pageSize;
                return props.total >= limit ? limit : props.total;
            }),
            // (Paging array)
            paging: computed(() => {
                let startPage = setting.page - 2 <= 0 ? 1 : setting.page - 2;
                if (setting.maxPage - setting.page <= 2) {
                    startPage = setting.maxPage - 4;
                }
                startPage = startPage <= 0 ? 1 : startPage;
                let pages = [];
                for (let i = startPage; i <= setting.maxPage; i++) {
                    if (pages.length < 5) {
                        pages.push(i);
                    }
                }
                return pages;
            }),
            // (Sortable for local)
            order: props.settings.orderBy.column,
            sort: props.settings.orderBy.direction,
            pageOptions: computed(() => {
                if (!props.pageOptions.filter((o :any) => o.value === props.settings.pageSize).length) {
                    return [...props.pageOptions, {
                        value: props.settings.pageSize,
                        text: props.settings.pageSize
                    }] as pageOption[]
                }
                return  props.pageOptions as pageOption[];
            }),
            isVerticalHighlight: props.isVerticalHighlight,
        });
        // (Checked rows)
        const isChecked = ref<Array<any>>(props.selected);



        watchDeep(()=> props.selected, (val) => {
            isChecked.value = val
        })

        //  (Data rows for local)
        const localRows = computed(() => {
            // sort rows
            let rows = props.rows as Array<any>;
            // refs https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Intl/Collator/compare
            var collator = new Intl.Collator(undefined, {
                numeric: true,
                sensitivity: "base",
            });
            let sortOrder = setting.sort === "desc" ? -1 : 1;
            // eslint-disable-next-line @typescript-eslint/no-explicit-any
            rows.sort((a: any, b: any): number => {
                return collator.compare(a[setting.order], b[setting.order]) * sortOrder;
            });

            let result = null;
            if (props.groupingKey) {
                // If have set grouping-key create group temp data
                let tmp = {} as any;
                rows.forEach((v: any) => {
                    if (!tmp[v[props.groupingKey]]) {
                        tmp[v[props.groupingKey]] = [];
                    }
                    tmp[v[props.groupingKey]].push(v);
                });

                result = {} as any;
                for (let index = setting.offset - 1; index < setting.limit; index++) {
                    result[rows[index][props.groupingKey]] = tmp[rows[index][props.groupingKey]];
                }
            } else {
                result = [];
                for (let index = setting.offset - 1; index < setting.limit; index++) {
                    result.push(rows[index]);
                }
            }

            nextTick(function () {
                // 資料完成渲染後回傳私有元件
                callIsFinished();
            });

            return result;
        });

        ////////////////////////////
        //
        //  Checkbox 相關操作
        //  (Checkbox related operations)
        //

        // 定義Checkbox參照 (Define Checkbox reference)
        const rowCheckbox = ref([]);

        /**
         * 重新渲染前執行 (Execute before re-rendering)
         */
        onBeforeUpdate(() => {
            // 每次更新前都把值全部清空 (Clear all values before each update)
            rowCheckbox.value = [];
        });

        /**
         * 監聽全勾選Checkbox (Check all checkboxes for monitoring)
         */
        watch(
            () => setting.isCheckAll,
            (state: boolean) => {
                if (props.hasCheckbox) {
                    setting.isIndeterminate = false;
                    isChecked.value = [];
                    if (state) {
                        let tmpRows = (props.isStaticMode) ? props.rows.slice((setting.offset - 1), setting.limit) : props.rows;
                        if (props.checkedReturnType == "row") {
                            isChecked.value = tmpRows;
                        } else {
                            tmpRows.forEach((val: any) => {
                                isChecked.value.push(val[setting.keyColumn]);
                            });
                        }
                    }
                    rowCheckbox.value.forEach((val: HTMLInputElement) => {
                        if (val) {
                            val.checked = state;
                        }
                    });
                    // 回傳畫面上選上的資料 (Return the selected data on the screen)
                    emit("update:selected", isChecked.value);
                }
            }
        );

        /**
         * 監控有無顯示Checkbox變化 (hasCeckbox props for monitoring)
         */
        watch(
            () => props.hasCheckbox,
            (v) => {
                if (!v) {
                    setting.isCheckAll = false;
                }
            }
        );

        /**
         * Checkbox點擊事件 (Checkbox click event)
         */
        const checked = (row: any, event: MouseEvent): void => {
            event.stopPropagation();
            setting.isIndeterminate = false;
            let checkboxValue = row[setting.keyColumn];
            if (props.checkedReturnType == "row") {
                checkboxValue = row;
            }
            if ((event.target as HTMLInputElement).checked) {
                isChecked.value.push(checkboxValue);
            } else {
                const index = isChecked.value.indexOf(checkboxValue);
                if (index >= 0) {
                    isChecked.value.splice(index, 1);
                }
            }
            if (isChecked.value.length == props.rows.length) {
                if (setting.isCheckAll) {
                    emit("update:selected", isChecked.value);
                }
                setting.isCheckAll = true;
            } else {
                if (isChecked.value.length > 0) {
                    setting.isIndeterminate = true;
                }
                // 回傳畫面上選上的資料 (Return the selected data on the screen)
                emit("update:selected", isChecked.value);
            }
        };

        /**
         * 清空畫面上所有選擇資料 (Clear all selected data on the screen)
         */
        const clearChecked = () => {
            isChecked.value = [];
            rowCheckbox.value.forEach((val: HTMLInputElement) => {
                if (val && val.checked) {
                    val.checked = false;
                }
            });
            // 回傳畫面上選上的資料 (Return the selected data on the screen)
            emit("update:selected", isChecked.value);
        };

        ////////////////////////////
        //
        //  排序·換頁等 相關操作
        //  (Sorting, page change, etc. related operations)
        //

        /**
         * 呼叫執行排序 (Call execution sequencing)
         */
        const doSort = (order: string) => {
            let sort = "asc";
            if (order == setting.order) {
                // 排序中的項目時 (When sorting items)
                if (setting.sort == "asc") {
                    sort = "desc";
                }
            }
            let page = setting.page
            let pageSize = setting.pageSize;
            setting.order = order;
            setting.sort = sort;
            emit("do-search", page , pageSize, order, sort);
            emit("update:settings", {
                page, pageSize, orderBy : {column:order,direction:sort}
            });

            // 清空畫面上選擇的資料 (Clear the selected data on the screen)
            if (setting.isCheckAll) {
                // 取消全選時自然會清空 (It will be cleared when you cancel all selections)
                setting.isCheckAll = false;
            } else {
                if (props.hasCheckbox) {
                    clearChecked();
                }
            }
        };

        /**
         * 切換頁碼 (Switch page number)
         *
         * @param page      number  新頁碼    (New page number)
         * @param prevPage  number  現在頁碼  (Current page number)
         */
        const changePage = (page: number, prevPage: number) => {
            setting.isCheckAll = false;
            setting.isIndeterminate = false;
            if (props.hasCheckbox) {
                isChecked.value = [];
            }
            let order = setting.order;
            let sort = setting.sort;
            let pageSize = setting.pageSize;
            if (!props.isReSearch || page > 1 || page == prevPage) {
                // 非重新查詢發生的頁碼變動才執行呼叫查詢 (Call query will only be executed if the page number is changed without re-query)
                emit("do-search", page, pageSize, order, sort);
            }
            emit("update:settings", {
                page, pageSize, orderBy : {column:order,direction:sort}
            });

        };
        // 監聽頁碼切換 (Monitor page switching)
        watch(() => setting.page, changePage);
        // 監聽手動頁碼切換 (Monitor manual page switching)
        watch(
            () => props.page,
            (val) => {
                if (val <= 1) {
                    setting.page = 1;
                    emit("get-now-page", setting.page);
                } else if (val >= setting.maxPage) {
                    setting.page = setting.maxPage;
                    emit("get-now-page", setting.page);
                } else {
                    setting.page = val;
                }
            }
        );

        /**
         * 切換顯示筆數 (Switch display number)
         */
        const changePageSize = () => {
            if (setting.page === 1) {
                // 沒自動觸發 changePage()，所以手動觸發
                changePage(setting.page, setting.page);
            } else {
                // 強制返回第一頁,並自動觸發 changePage()
                setting.page = 1;
                setting.isCheckAll = false;
                setting.isIndeterminate = false;
            }
        };
        // 監聽組件內顯示筆數切換 (Monitor display number switch from component)
        watch(() => setting.pageSize, changePageSize);
        // 監聽來自Prop的顯示筆數切換 (Monitor display number switch from prop)
        watch(
            () => props.pageSize,
            (newPageSize) => {
                setting.pageSize = newPageSize;
            }
        );

        /**
         * 上一頁 (Previous page)
         */
        const prevPage = () => {
            if (setting.page == 1) {
                // 如果是第一頁，不予執行 (If it is the first page, it will not be executed)
                return false;
            }
            setting.page--;
        };

        /**
         * 移動至指定頁數 (Move to the specified number of pages)
         */
        const movePage = (page: number) => {
            setting.page = page;
        };

        /**
         * 下一頁 (Next page)
         */
        const nextPage = () => {
            if (setting.page >= setting.maxPage) {
                // 如果等於大於最大頁數，不與執行 (If it is equal to or greater than the maximum number of pages, no execution)
                return false;
            }
            setting.page++;
        };

        // 監聽資料變更 (Monitoring data changes)
        watch(
            () => props.rows,
            () => {
                if (props.isReSearch || props.isStaticMode) {
                    setting.page = 1;
                }
                nextTick(function () {
                    // 資料完成渲染後回傳私有元件 (Return the private components after the data is rendered)
                    if (!props.isStaticMode) {
                        callIsFinished();
                    }
                });
            },
            { deep: true }
        );

        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        const stringFormat = (template: string, ...args: any[]) => {
            return template.replace(/{(\d+)}/g, function (match, number) {
                return typeof args[number] != "undefined" ? args[number] : match;
            });
        };

        // Call 「is-finished」 Method
        const callIsFinished = () => {
            if (localTable.value) {
                let localElement = localTable.value.getElementsByClassName("is-rows-el");
                emit("is-finished", localElement);
            }
            emit("get-now-page", setting.page);
        };

        // Toggle button elements
        const toggleButtonRefs = ref<{ [key: string]: HTMLElement }>({});
        // Grouping rows
        const groupingRowsRefs = ref<{ [key: string]: Array<HTMLElement> }>({});
        // Saved toggle status
        const groupingToggleStatus = ref<{ [key: string]: boolean }>({});

        // Data rows for grouping (Default-mode only)
        const groupingRows = computed(() => {
            let result = {} as any;
            props.rows.forEach((v: any) => {
                if (!result[v[props.groupingKey]]) {
                    result[v[props.groupingKey]] = [];
                }
                result[v[props.groupingKey]].push(v);
            });

            nextTick(function () {
                if (props.startCollapsed || props.isKeepCollapsed) {
                    for (const [groupIndex, el] of Object.entries(toggleButtonRefs.value)) {
                        if (el && el.parentElement) {
                            let isOpen = !props.startCollapsed;
                            if (
                                props.isKeepCollapsed &&
                                groupingToggleStatus.value[groupIndex] !== undefined
                            ) {
                                isOpen = !groupingToggleStatus.value[groupIndex];
                            }
                            if (
                                (isOpen && el.parentElement.classList.contains("rotated-90")) ||
                                (!isOpen && !el.parentElement.classList.contains("rotated-90"))
                            ) {
                                el.parentElement.classList.toggle("rotated-90");
                            }
                            if (!isOpen) {
                                groupingRowsRefs.value[groupIndex].forEach((element) => {
                                    if (element) {
                                        element.classList.add("hidden");
                                    }
                                });
                            }
                        }
                    }
                }
                callIsFinished();
            });

            return result;
        });

        /**
         * Toggle Group rows
         *
         * @param {String} groupIndex
         */
        const toggleGroup = (groupIndex: string) => {
            let targetEl = toggleButtonRefs.value[groupIndex];
            if (targetEl && targetEl.parentElement) {
                targetEl.parentElement.classList.toggle("rotated-90");
                const isClose = targetEl.parentElement.classList.contains("rotated-90");
                groupingRowsRefs.value[groupIndex].forEach((element) => {
                    if (isClose) {
                        element.classList.add("hidden");
                    } else {
                        element.classList.remove("hidden");
                    }
                });
                groupingToggleStatus.value[groupIndex] = isClose;
                emit("row-toggled", groupingRows.value[groupIndex], isClose);
            }
        };

        /**
         * Add hover class to tr
         *
         * @param {MouseEvent} mouseEvent
         */
        const addHoverClassToTr = (mouseEvent: MouseEvent) => {
            if (mouseEvent.target instanceof HTMLElement) {
                mouseEvent.target.classList.add("hover");
            }
        };

        /**
         * Remove hover class from tr
         *
         * @param {MouseEvent} mouseEvent
         */
        const removeHoverClassFromTr = (mouseEvent: MouseEvent) => {
            if (mouseEvent.target instanceof HTMLElement) {
                mouseEvent.target.classList.remove("hover");
            }
        };

        /**
         * Add hover class to td
         *
         * @param {Number} index
         */
        const addVerticalHighlight = (index: number) => {
            if (!setting.isVerticalHighlight) {
                return;
            }
            if (!localTable.value) {
                return;
            }
            let elements = localTable.value.querySelectorAll(".vtl-tbody-td" + index);
            for (let i = 0; i < elements.length; i++) {
                elements[i].classList.add("hover");
            }
        };

        /**
         * Remove hover class from td
         *
         * @param {Number} index
         */
        const removeVerticalHighlight = (index: number) => {
            if (!setting.isVerticalHighlight) {
                return;
            }
            if (!localTable.value) {
                return;
            }
            let elements = localTable.value.querySelectorAll(".vtl-tbody-td" + index);
            for (let i = 0; i < elements.length; i++) {
                elements[i].classList.remove("hover");
            }
        };

        /**
         * 組件掛載後事件 (Mounted Event)
         */
        onMounted(() => {
            nextTick(() => {
                if (props.rows.length > 0) {
                    callIsFinished();
                }
            });
        });

        const templateRows= ref<any>()
        return {
            isChecked,
            slots,
            localTable,
            localRows,
            setting,
            rowCheckbox,
            checked,
            clearChecked,
            doSort,
            prevPage,
            movePage,
            nextPage,
            stringFormat,
            toggleButtonRefs,
            groupingRowsRefs,
            groupingRows,
            toggleGroup,
            addHoverClassToTr,
            removeHoverClassFromTr,
            addVerticalHighlight,
            removeVerticalHighlight,
            templateRows
        };
    },
    watch: {
        pageSize() {
            this.setting.pageSize = this.pageSize;
        },
    },
});
</script>

<style scoped>
.vtl-both {
    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAQAAADYWf5HAAAAkElEQVQoz7X QMQ5AQBCF4dWQSJxC5wwax1Cq1e7BAdxD5SL+Tq/QCM1oNiJidwox0355mXnG/DrEtIQ6azioNZQxI0ykPhTQIwhCR+BmBYtlK7kLJYwWCcJA9M4qdrZrd8pPjZWPtOqdRQy320YSV17OatFC4euts6z39GYMKRPCTKY9UnPQ6P+GtMRfGtPnBCiqhAeJPmkqAAAAAElFTkSuQmCC");
}

.vtl-sortable {
    cursor: pointer;
    background-position: right;
    background-repeat: no-repeat;
    padding-right: 30px !important;
}

.vtl-asc {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAYAAAByUDbMAAAAZ0lEQVQ4y2NgGLKgquEuFxBPAGI2ahhWCsS/gDibUoO0gPgxEP8H4ttArEyuQYxAPBdqEAxPBImTY5gjEL9DM+wTENuQahAvEO9DMwiGdwAxOymGJQLxTyD+jgWDxCMZRsEoGAVoAADeemwtPcZI2wAAAABJRU5ErkJggg==);
}

.vtl-desc {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAYAAAByUDbMAAAAZUlEQVQ4y2NgGAWjYBSggaqGu5FA/BOIv2PBIPFEUgxjB+IdQPwfC94HxLykus4GiD+hGfQOiB3J8SojEE9EM2wuSJzcsFMG4ttQgx4DsRalkZENxL+AuJQaMcsGxBOAmGvopk8AVz1sLZgg0bsAAAAASUVORK5CYII=);
}
</style>
