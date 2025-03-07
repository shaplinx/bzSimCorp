

import { BaseColor } from "./common";

export interface CardProps  {
  bordered?: boolean,
  side?: boolean,
  imageFull?:boolean,
  compact?: boolean,
  glass?: boolean,
  image?: string,
  title?: string,
  description?: string,
  variant?: BaseColor | "base"
};
