<?php

namespace App\Http\Requests\API\V1\Finance;

use App\Models\Finance\Transaction;
use App\Rules\FinanceCategoryHasRightType;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('create', Transaction::class);

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
            "type" => ["required", "string", "in:in,out"],
            "amount" => ["required", "numeric", "gt:0"],
            "bank_id" => ["required", "string", "exists:banks,id"],
            "transaction_category_id" => ["required", "numeric", new FinanceCategoryHasRightType($this->type)],
        ];
    }
}
