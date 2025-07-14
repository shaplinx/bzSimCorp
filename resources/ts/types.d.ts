declare type NavItem = {
  label: string;
  childs?: NavItem[];
  id?: string;
  isOpen?: boolean;
  icon?: string;
  to?: string;
  type?: "name" | "path" | "external" | "none";
};

declare interface LoginFormError {
  email?: string[];
  password?: string[];
  remember?: string[];
}

declare interface LoginForm {
  email?: string;
  password?: string;
  remember?: boolean;
}


declare namespace Base {
  export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    profesi_id: number;
    created_at: string | null;
    updated_at: string | null;
    profesi?: App.Models.Administrasi.Profesi | null;
    readonly fullname?: any;
    readonly full_name?: any;
    readonly all_permissions?: any;
    readonly is_admin?: any;
  }
  namespace Component {
    namespace Menu {
      export type MenuItem = {
        id?: string | number,
        to?: import("vue-router").RouteLocationRaw,
        label: string,
        icon?: import("@fortawesome/free-brands-svg-icons").IconDefinition | string,
        child?: MenuItem[],
        active?: boolean,
        childActive?: boolean,
        isOpen?: boolean,
        onSuccess?: Function
      }
    }
  }
}
