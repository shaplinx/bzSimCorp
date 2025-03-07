import type { FormKitSchemaDefinition } from "@formkit/core"
import axiosInstance from "@/@hooks/api/useAxios"

export type UserFormConfig = {
    route: "create" | "edit",
    authRole: ("admin" | "standard")[]
}

export function useUserFormSchema(config: UserFormConfig): FormKitSchemaDefinition {
    function generatePasswordSchema(route: "create" | "edit", authRole: ("admin" | "standard")[]) {
        if (route === "create") {
            return [
                {
                    $formkit: "password",
                    name: "password",
                    label: "Password"
                },
                {
                    $formkit: "password",
                    name: "password_confirmation",
                    label: "Password Confirmation"
                }
            ]
        }

        return [{
            $el: "div",
            attrs: { class: "collapse rounded-box border" },
            children: [
                {
                    $el: "input",
                    attrs: { type: "checkbox", class: "peer" }
                },
                {
                    $el: "div",
                    attrs: { class: "collapse-title" },
                    children: "Change Password"
                },
                {
                    $el: "div",
                    attrs: { class: "collapse-content" },
                    children: authRole.includes("admin") ?
                        [
                            {
                                $formkit: "password",
                                name: "password",
                                label: "New Password"
                            },
                            {
                                $formkit: "password",
                                name: "password_confirmation",
                                label: "Password Confirmation"
                            }
                        ] :
                        [
                            {
                                $formkit: "password",
                                name: "old_password",
                                label: "Old Password"
                            },
                            {
                                $formkit: "password",
                                name: "password",
                                label: "New Password"
                            },
                            {
                                $formkit: "password",
                                name: "password_confirmation",
                                label: "Password Confirmation"
                            }
                        ]
                },
            ]
        }
        ]
    }

    return [
        {
            $formkit: "email",
            name: "email",
            label: "Email Address"
        },
        {
            $formkit: "text",
            name: "name",
            label: "Full Name"
        },
        ...generatePasswordSchema(config.route, config.authRole),
        {
            $formkit: "vSelect",
            name: "roles",
            label: "Roles",
            displayLabel: "label",
            placeholder: "Search roles",
            object: true,
            mode: "tags",
            "filter-results": true,
            "min-chars": 1,
            "resolve-on-load": true,
            clearOnSearch: true,
            searchable: true,
            options: (search: string): Promise<any[]> => {
                return axiosInstance
                    .get("auth/all-roles", { params: { search } })
                    .then((res) => res.data.data?.map?.((opts: any) => opts.key))
                    .catch(() => []);
            },
        },
    ]

}
