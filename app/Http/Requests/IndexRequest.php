<?php

namespace App\Http\Requests;

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

    public function prepareForValidation()
    {
        if (!$this->filled('orderBy.column')) {
            $this->merge([
                'orderBy' => array_merge(
                    ['column' => 'id'],
                    $this->input('orderBy', [])
                )
            ]);
        }
        if (!$this->filled('orderBy.direction')) {
            $this->merge([
                'orderBy' => array_merge(
                    ['direction' => 'desc'],
                    $this->input('orderBy', [])
                )
            ]);
        }
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
            "orderBy" => ["required", "required_array_keys:column,direction"],
            "orderBy.direction" => ["required_with:orderBy", "in:asc,desc"],
            "pageSize" => ["nullable", "numeric"],
            "dateAfter" => ["nullable", "date", 'date_format:Y-m-d'],
            "dateBefore" => ["nullable", "date", 'date_format:Y-m-d'],
            "user" => ["nullable", "exists:users,id"]

        ];
    }
}
