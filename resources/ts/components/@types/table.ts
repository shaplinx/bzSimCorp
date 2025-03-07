export type TableField = {
    label: string
    value: string
    sortValue?: string;
    sortable?: boolean
    fixed?: boolean
    unique?: boolean
    align?: "left" | "center" | "right"
}

export type Data = {
    [key: string]: any
}

export type TableProps = {
    data: Data[],
    header?: {
        title: string,
        description: string
    },
    fields: TableField[],
    selectMode?: "single" | "multiple"
    selected?: any | any[],
    striped?: boolean,
    hover?: boolean
    sort?: {
        column?: string,
        direction?: "asc" | "desc" | "DESC" | "ASC"
    },
    serverSide?: boolean,
    loading?:boolean
}

export interface DataTableProps extends TableProps {

}
