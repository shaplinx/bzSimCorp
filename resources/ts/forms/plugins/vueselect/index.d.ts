import Vue from "vue";

declare class Multiselect extends Vue {}
declare class multiselectMixin extends Vue {}
declare class pointerMixin extends Vue {}

declare module "vue-multiselect" {
  export default Multiselect;
  export { Multiselect, multiselectMixin, pointerMixin };
}
