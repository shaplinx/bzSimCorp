<?php

namespace App\Http\Requests;

use App\Rules\ColumnExists;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            "search" => ["nullable", "string"],
            "orderBy" => ["nullable", "required_array_keys:column,direction"],
            "orderBy.column" => ["required_with:orderBy", new ColumnExists("users")],
            "orderBy.direction" => ["required_with:orderBy", "in:asc,desc"],
            "pageSize" => ["nullable", "numeric"],
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        if ($this->has('orderBy')) {
            $this->merge(['orderBy' => $this->orderBy["column"] .'|'. $this->orderBy["direction"] ]);
        }
    }
}
