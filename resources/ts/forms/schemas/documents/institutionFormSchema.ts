import type { FormKitSchemaDefinition } from "@formkit/core"

export type InstitutionFormConfig = {
    route: "create" | "edit",
}

export function useInstitutionFormSchema(config: InstitutionFormConfig): FormKitSchemaDefinition {

    return [
        {
            $formkit: "text",
            name: "name",
            label: "Instance Name"
        },
        {
            $formkit: "text",
            name: "code",
            label: "Instance Code",
            help: "Will be written in letter's number"

        },
        {
            $formkit: "text",
            name: "sn_template",
            label: "Serial number (SN) Template",
            help: "Available placeholders : [SN], [INSTANCE], [CLASSIFICATION], [FULL_CLASSIFICATION], [DAY], [DAY_ROMAN], [MONTH], [MONTH_ROMAN], [YEAR], [YEAR_ROMAN]"
        },
                {
            $formkit: "select",
            name: "reset_sn_period",
            label: "Reset SN Period",
            options: {
                y:"Yearly",
                m:"Monthly",
                d:"Dialy"
            }
        }
    ]

}
