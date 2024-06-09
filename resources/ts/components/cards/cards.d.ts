import { BaseColor, ClassesPairs } from "./../daisy.d";
export type CardImageMode = "top" | "bottom" | "full" | "side" | "responsive";
export type CardColor = BaseColor;

export type CardPadding = "compact" | "center" | "default";

export interface CardPaddingClasses {
  default: {
    body: string;
    container: string;
    figure: string;
  };
  [key: string]: {
    body: string;
    container: string;
    figure: string;
  };
}

export interface CardClasses extends ClassesPairs {}
export interface CardWidthClasses extends CardClasses {}
export interface CardColorClasses extends CardClasses {}
export interface CardImageClasses extends CardClasses {}
export interface CardPaddingClasses extends CardClasses {}
