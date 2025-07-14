<?php

namespace App\Http\Requests\Documents;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust as needed
    }

    public function rules(): array
    {
        return [
            'institution_id' => 'exists:institutions,id',
            'classification_id' => 'exists:classifications,id',
            'subject' => 'string|max:255',
            'recipient' => 'string|max:255',
            'letter_date' => 'date',
            'status' => 'in:draft,issued,void',
            'file' =>['nullable', 'file', 'mimes:pdf,doc,docx', 'max:4096'],

        ];
    }

}
