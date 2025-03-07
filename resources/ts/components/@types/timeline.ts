import { IconDefinition } from "@fortawesome/free-solid-svg-icons";
import { BaseColor } from "./common";

export interface TimelineContentProps {
    variant?: BaseColor | "base"
  };

  export interface TimelineIconProps{
    icon: IconDefinition,
    variant?: BaseColor | "base",
    iconClass?:string

  };


  export interface TimelineItemProps {
    variant?: BaseColor | "base",
    icon: IconDefinition,
    iconClass?:string


  };

  export interface TimelineContainerProps {
    variant?: BaseColor | "base"
  };
