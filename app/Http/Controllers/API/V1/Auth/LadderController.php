<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\ApiController;
use Illuminate\Http\Request;
use Ladder\Ladder;


class LadderController extends ApiController
{
    function getAllRoles() {
        return $this->sendResponse(__("Fetched Successfully"),array_values(Ladder::$roles));
    }

    function getAllPermissions() {
        return $this->sendResponse(__("Fetched Successfully"),Ladder::$permissions);
    }
}
