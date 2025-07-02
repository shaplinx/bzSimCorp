<?php

namespace App\Http\Requests\Documents;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateLetterRequest extends FormRequest
{
 public function authorize(): bool
    {
        return true; // Adjust as needed
    }

    public function rules(): array
    {
        $letterId = $this->route('letter')?->id;

        return [
            'institution_id' => 'required|exists:institutions,id',
            'classification_id' => 'required|exists:classifications,id',
            'subject' => 'required|string|max:255',
            'recipient' => 'nullable|string|max:255',
            'letter_date' => 'required|date',
            'file_path' => 'nullable|string|max:255',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);
        $data['created_by'] = Auth::id(); // Inject authenticated user ID
        return $data;
    }
}
