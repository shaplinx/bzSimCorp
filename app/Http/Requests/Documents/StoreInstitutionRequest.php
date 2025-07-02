<?php

namespace App\Http\Requests\Documents;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstitutionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:institutions,code',
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
