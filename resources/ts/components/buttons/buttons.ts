

export type BaseSize = "xs" | "s" | "md" | "lg" | "lg";

export type ButtonSize = BaseSize;
export type ButtonVariant =
  | "default"
  | "primary"
  | "secondary"
  | "accent"
  | "info"
  | "success"
  | "warning"
  | "error"
  | "ghost"
  | "link"
  | "glass";
export interface ButtonProp {
  loading?: boolean;
  iconClass?: string;
  label?: string;
  size?: ButtonSize;
  variant?: ButtonVariant;
  shape?: "default" | "circle" | "square";
  block?: boolean;
  wide?: boolean;
  disabled?: boolean;
  outline?: boolean;
  noAnimation?: boolean;
  tag?: string;
  active?: boolean;
  value?: Number | string;
  onClick?: Function;
}

export type ButtonGroupMode = "vertical" | "horizontal";
