<?php

namespace App\Http\Requests\API\V1\Finance;

use App\Models\Finance\TransactionCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreTransactionCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return Gate::authorize("create",TransactionCategory::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required","string","max:255"],
            "hex_color" => ["required","string","regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i"],
            "type" => ["required", "string", "in:in,out"],
            "bank_id" => ["required","string", "exists:banks,id"] 
        ];
    }
}
