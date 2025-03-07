<?php

namespace App\Http\Requests\API\V1\Finance;

use App\Models\Finance\Document;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {

        return Gate::authorize('create', Document::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'number'       => 'required|string|max:50|unique:documents,number',
            'author'       => 'required|string|max:255',
            'about'        => 'required|string',
            'status'       => 'required|boolean',
            'signed_by'    => 'required|string|max:255',
            'signed_place' => 'required|string|max:255',
            'signed_at'    => 'required|date',
            'category_id'  => 'required|exists:categories,id',
            'revised'   => 'nullable|array',
            'revised.*.id'  => 'bail|exists:documents,id',
            'revising'  => 'nullable|array',
            'revising.*.id' => 'bail|exists:documents,id',
        ];
    }
}
