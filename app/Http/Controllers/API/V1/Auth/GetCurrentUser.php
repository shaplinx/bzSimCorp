<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\ApiController;
use Illuminate\Http\Request;

class GetCurrentUser extends ApiController
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
