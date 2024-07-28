import {type FormKitSchemaDefinition} from "@formkit/core"
export default [
    {
        name: 'text',
        label: 'Text Input',
        $formkit: 'text',
    },
    {
        name: 'email',
        label: 'Email Input',
        $formkit: 'email',
    },
    {
        name: 'password',
        label: 'Password Input',
        $formkit: 'password',
    },
    {
        name: 'url',
        label: 'URL Input',
        $formkit: 'url',
    },
    {
        name: 'tel',
        label: 'Telephone Input',
        $formkit: 'tel',
    },
    {
        name: 'number',
        label: 'Number Input',
        $formkit: 'number',
    },
    {
        name: 'date',
        label: 'Date Input',
        $formkit: 'date',
    },
    {
        name: 'time',
        label: 'Time Input',
        $formkit: 'time',
    },
    {
        name: 'datetime-local',
        label: 'DateTime-local Input',
        $formkit: 'datetime-local',
    },
    {
        name: 'month',
        label: 'Month Input',
        $formkit: 'month',
    },
    {
        name: 'week',
        label: 'Week Input',
        $formkit: 'week',
    },
    {
        name: 'select',
        label: 'Select Dropdown',
        $formkit: 'select',
        options: [
            { label: 'Option 1', value: 'option1' },
            { label: 'Option 2', value: 'option2' },
            { label: 'Option 3', value: 'option3' },
        ],
    },
    {
        name: 'checkbox',
        label: 'Checkbox',
        $formkit: 'checkbox',
    },
    {
        name: 'radio',
        label: 'Radio Buttons',
        $formkit: 'radio',
        options: [
            { label: 'Option A', value: 'optionA' },
            { label: 'Option B', value: 'optionB' },
            { label: 'Option C', value: 'optionC' },
        ],
    },
    {
        name: 'file',
        label: 'File Upload',
        $formkit: 'file',
    },
    {
        name: 'color',
        label: 'Color Picker',
        $formkit: 'color',
    },
    {
        name: 'textarea',
        label: 'Textarea',
        $formkit: 'textarea',
    },
    {
        name: 'submit',
        label: 'Submit',
        variant:'primary',
        block: true,
        $formkit: 'submit',
    },
] as FormKitSchemaDefinition
