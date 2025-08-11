import type { FormKitSchemaDefinition } from "@formkit/core"

export function useShortUrlFormSchema(): FormKitSchemaDefinition {
  return [
    {
      $formkit: 'url',
      name: 'url',
      label: 'URL',
      placeholder: 'https://example.com',
    },
    {
      $formkit: 'text',
      name: 'shortKey',
      label: 'Short Key',
      placeholder: 'Optional custom short key',
    },
    {
      $formkit: 'datepicker',
      name: 'activateAt',
      label: 'Activate At',
    },
    {
      $formkit: 'datepicker',
      name: 'deactivateAt',
      label: 'Deactivate At',
    },
    {
      $formkit: 'checkbox',
      name: 'disableTracking',
      label: 'Disable Tracking',
    },
    {
      $formkit: 'checkbox',
      name: 'singleUse',
      label: 'Single Use Only',
    },
    {
      $formkit: 'checkbox',
      name: 'forwardQueryParams',
      label: 'Forward Query Parameters',
    },
    

  ]
}