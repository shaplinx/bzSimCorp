<?php

namespace App\Http\Requests\API\V1\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;


class UpdateLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return Gate::authorize('update', $this->loan);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255", "min:3"],
            "note" => ["required", "string", "max:1000", "min:3"],
            "date" => ["required", "date"], 
            "completed" => ["required", "boolean"],
            "type" => ["required", "string", "in:in,out"],
            "bank_id" => ["required", "string", "exists:banks,id"],
            "amount" => ["required", "numeric", "gt:0"],
        ];
    }
}
