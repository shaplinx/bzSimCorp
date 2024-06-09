import { ClassesPairs, BaseSize } from './../daisy.d';
import { BaseColor } from "../daisy";
declare export type BadgeColor = BaseColor | "ghost" ;
declare export type BadgeSize = BaseSize;

declare export interface BadgeProp {
  color?: BadgeColor;
  iconClass?: string;
  label?: string;
  size?: BadgeSize;
  outline?: boolean;
}

declare export interface BadgeColorClasses extends ClassesPairs {}
declare export interface BadgeSizeClasses extends ClassesPairs {}
declare export interface BadgeWidthClasses extends ClassesPairs {}
declare export interface BadgeVariantClasses extends ClassesPairs {}



