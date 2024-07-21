<?php

namespace App\Exceptions;

use Exception;

class AlreadyAuthenticatedException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => __('Already Authenticated')], 403);
    }
}
