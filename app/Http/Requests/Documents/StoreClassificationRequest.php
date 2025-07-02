<?php

namespace App\Http\Requests\Documents;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClassificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $classification = $this->route('classification');

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('classifications', 'code')->ignore($classification?->id),
            ],
            'name' => 'required|string|max:255',
            'parent_id' => [
                'nullable',
                'exists:classifications,id',
                'not_in:' . $classification?->id, // prevent self-referencing
            ],
            'separator' => 'required|string|min:1|max:5',
        ];
    }
}
