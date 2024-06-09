
import type {BaseColor, BaseSize} from "./common"

export type LoadingColor = BaseColor;
export type LoadingSize = BaseSize;
export type LoadingVariant = 'spinner' | 'dots' | 'ring' | 'ball' | 'bars' | 'infinity';

export type LoadingProps = {
    variant?: LoadingVariant
    size?: BaseSize
    color?: BaseColor

}
