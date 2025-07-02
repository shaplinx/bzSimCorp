<?php

namespace App\Http\Requests\Documents;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstitutionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('institutions', 'code')->ignore($this->institution),
            ],
            'name' => 'required|string|max:255',
            'reset_sn_period' => 'required|in:d,m,y',
            'sn_template' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'reset_sn_period.in' => 'Reset period must be one of: daily (d), monthly (m), or yearly (y).',
        ];
    }
}
