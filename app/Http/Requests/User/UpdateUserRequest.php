<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use Illuminate\Validation\Rule;
use Ladder\Ladder;


class UpdateUserRequest extends StoreUserRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            "name" => ["required", "string"],
            "email" => ["required", "email", Rule::unique('users')->ignore($this->user->id)],
            "password" => [ "sometimes", $this->passwordCriteria(), "confirmed"],
            "password_confirmation" => ["sometimes","required_with:password"],
            "roles" => "sometimes|required|array",
            "roles.*" => "sometimes|required_with:roles|in:". implode(',', collect(array_values(Ladder::$roles))->pluck("key")->all())
        ];

        if ($this->user()->id === $this->user->id) $rules['old_password'] = ["sometimes","required_with:password", "current_password"];

        return $rules;
    }
}
