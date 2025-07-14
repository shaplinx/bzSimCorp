import type { FormKitSchemaDefinition } from "@formkit/core"
import axiosInstance from "@/@hooks/api/useAxios"


export type ClassificationFormConfig = {
  route: "create" | "edit"
}

export function useClassificationFormSchema(
  config: ClassificationFormConfig
): FormKitSchemaDefinition {
  return [
    {
      $formkit: "text",
      name: "name",
      label: "Classification Name",
      help: "e.g. Finance, IT, Human Resources"
    },
    {
      $formkit: "text",
      name: "code",
      label: "Classification Code",
      help: "This will appear in the SN format, e.g. KEU for Finance"
    },
    {
      $formkit: "text",
      name: "classification_separator",
      label: "Classification Separator",
      help: "Used when forming the full classification code (e.g. / or -)"
    },
    {
      $formkit: "vSelect",
      name: "parent",
      label: "Parent Classification",
      displayLabel: "name",
      placeholder: "Search classification",
      object: true,
      valueProp:"id",
      "filter-results": false,
      "min-chars": 1,
      "resolve-on-load": true,
      clearOnSearch: true,
      debounce: 250,
      searchable: true,
      options: (search: string): Promise<any[]> => {
        return axiosInstance
          .get("documents/classifications", { params: { search } })
          .then((res) => res.data.data.data)
          .catch(() => []);
      },
    },

  ]
}