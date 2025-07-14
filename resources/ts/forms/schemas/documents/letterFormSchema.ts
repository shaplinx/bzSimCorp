import axiosInstance from "@/@hooks/api/useAxios";
import type { FormKitSchemaDefinition } from "@formkit/core"

export type LetterFormConfig = {
  route: "create" | "edit"
}

export function useLetterFormSchema(config: LetterFormConfig): FormKitSchemaDefinition {
  function generateStatusOptions() {
    return config.route === "edit" ? {
      draft: "Draft",
      issued: "Issued",
      void: "Void"
    } :
      {
        draft: "Draft",
        issued: "Issued"
      }
  }
  return [
    {
      $formkit: "text",
      readOnly: true,
      label: "Letter's Number",
      help: "Will be generated automatically when letter is issued",
      name: "letter_number"
    },
    {
      $formkit: "vSelect",
      name: "institution",
      label: "Institution",
      displayLabel: "name",
      placeholder: "Search institution",
      object: true,
      valueProp: "id",
      "filter-results": false,
      "min-chars": 1,
      "resolve-on-load": true,
      clearOnSearch: true,
      debounce: 250,
      searchable: true,
      options: (search: string): Promise<any[]> => {
        return axiosInstance
          .get("documents/institutions", { params: { search } })
          .then((res) => res.data.data.data)
          .catch(() => []);
      },
    },
    {
      $formkit: "vSelect",
      name: "classification",
      label: "Classification",
      displayLabel: "name",
      placeholder: "Search classification",
      object: true,
      valueProp: "id",
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
    {
      $formkit: "text",
      name: "subject",
      label: "Subject",
      help: "Enter the subject or title of the letter."
    },
    {
      $formkit: "text",
      name: "recipient",
      label: "Recipient",
      help: "Optional: Enter the recipient's name."
    },
    {
      $formkit: "date",
      name: "letter_date",
      label: "Letter Date",
      help: "Date the letter is created or signed."
    },
    {
      $formkit: "select",
      name: "status",
      label: "Letter Status",
      options: generateStatusOptions(),
      help: "Choose whether the letter is still a draft or ready to be issued."
    },
    {
      $formkit: "file",
      name: "file",
      label: "Attach File",
      multiple: "false",
      help: "Upload the official document (PDF, DOC, or DOCX). You can issue letter first to get it's number then upload once when it's issued",
    },
  ]
}