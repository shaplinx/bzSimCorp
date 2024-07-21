<?php

namespace App\Http\Requests\API\V1;

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
            "orderBy.direction" => ["required_with:orderBy", "in:ASC,DESC,asc,desc"],
            "per_page" => ["nullable", "numeric"],
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        $this->merge(['orderBy' => implode('|', $this->orderBy)]);
    }
}
