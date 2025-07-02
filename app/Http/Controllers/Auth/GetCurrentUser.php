<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetCurrentUser extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return $this->sendResponse(__("Fetched Successfully"), [
            "user"=> $request->user()->append(["allPermissions"])
        ]);
    }
}
