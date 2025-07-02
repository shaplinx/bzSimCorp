<?php

namespace App\Http\Requests\Documents;

use Illuminate\Foundation\Http\FormRequest;

class StoreLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust if needed
    }

    public function rules(): array
    {
        return [
            'institution_id' => 'required|exists:institutions,id',
            'classification_id' => 'required|exists:classifications,id',
            'subject' => 'required|string|max:255',
            'recipient' => 'nullable|string|max:255',
            'letter_date' => 'required|date',
            'file_path' => 'nullable|string|max:255',
            'status' => 'required|in:draft,issued'
        ];
    }

}
