<?php

namespace App\Http\Requests\API\V1\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use Hash;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            [
                "name" => ["required", "string"],
                "email" => ["required", "email", "unique:users,email"],
                "password" => ["required", $this->passwordCriteria(), "confirmed"],
                "password_confirmation" => ["required"],
            ],
        ];
    }

    /**
     * get Password criteria.
     */
    public function passwordCriteria(): Password
    {
        return Password::min(8)->letters();
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        $this->merge(["password" => Hash::make($this->password)]);
    }
}
