import { IconDefinition } from "@fortawesome/free-solid-svg-icons"
import { BaseColor } from "./common"

export type ButtonProps = {
    block?: boolean,
    wide?: boolean,
    disabled?: boolean,
    outline?: boolean,
    active?: boolean,
    loading?: boolean,
    icon?:IconDefinition,
    noAnimation?: boolean,
    label?: string,
    variant?: BaseColor | "ghost" | "link" | "glass"
    shape?: "default" | "circle" | "square",
    size?: 'xs' | 'sm' | 'md' | 'lg',
    tag?: 'button' | 'a' | 'input',
    onClick?: (e: MouseEvent) => any,
    value?:any,
    on?: any
}
