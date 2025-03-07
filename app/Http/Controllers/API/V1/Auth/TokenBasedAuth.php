<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TokenBasedAuth extends ApiController
{
    public function createToken(Request $request)
    {
        {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (auth()->attempt($credentials)) {
                $token = $request->user()->createToken($request->header('User-Agent') ?? '');

                return $this->sendResponse(__('Login Successful'), [
                    'token' => $token->plainTextToken,
                    'user' => auth()->user()
                ]);
            }

            throw ValidationException::withMessages([
                'email' => __('The provided credentials do not match our records.'),
            ]);
        }

    }
    public function revokeToken(Request $request)
    {
        $user = auth()->user();
        $token = $user->currentAccessToken();
        if (!$request->id || $request->id == $token->id) {
            $token->delete();
            return $this->sendResponse(__('Logout Successful'));
        }
        if ($token = $user->tokens()->where('id', $request->id)->first()) {
            $token->delete();
            return $this->sendResponse(__('Token Revoked'));
        }
        return $this->sendError(__("Token's not found"));

    }
}
