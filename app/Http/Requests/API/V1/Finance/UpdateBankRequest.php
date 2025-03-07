<?php

namespace App\Http\Requests\API\V1\Finance;

use App\Models\Finance\Bank;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;



class UpdateBankRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return Gate::authorize('update', $this->bank);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "id" => ["required","string","alpha_num", Rule::unique('banks')->ignore($this->bank->id)],
            "name" => "required|string|min:3|max:225",
            "hex_color" =>  ['required', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'],
            "users"=> "required|array",
            "users.*" => "exists:users,id"
        ];
    }
}
