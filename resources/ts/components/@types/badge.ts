import type { BaseColor } from "./common"
import type { BaseSize } from "./common"


export interface BadgeProps {
    outline?:boolean,
    variant?:BaseColor | "ghost" | "default",
    size?:BaseSize,
    label?:string
}
