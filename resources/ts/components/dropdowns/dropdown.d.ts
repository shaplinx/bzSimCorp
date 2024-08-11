import { IconDefinition } from "@fortawesome/fontawesome-svg-core";
import { BaseColor, ClassesPairs } from "./../daisy.d";
export type DropdownImageMode =
  | "top"
  | "bottom"
  | "full"
  | "side"
  | "responsive";
export type DropdownColor = BaseColor;

declare interface DropdownOption {
  value?: any;
  label?: string;
  disabled?:boolean;
  icon?: string | IconDefinition;
  action?: Function;
  [key: string]: any
}
export interface DropdownClasses extends ClassesPairs {}
export interface DropdownColorClasses extends DropdownClasses {}
